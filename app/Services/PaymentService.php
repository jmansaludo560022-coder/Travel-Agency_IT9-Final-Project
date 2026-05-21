<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function recordPayment(array $data): Payment
    {
        $booking = Booking::findOrFail($data['booking_id']);

        $totalPaid = $booking->payments()->sum('amount_paid');
        $newTotal  = $totalPaid + $data['amount_paid'];

        if ($newTotal > $booking->total_amount) {
            throw ValidationException::withMessages([
                'amount_paid' => [
                    'Payment would exceed the total booking amount. Remaining balance: $'
                    . number_format($booking->total_amount - $totalPaid, 2)
                ]
            ])->status(422);
        }

        if (Payment::where('payment_ref_no', $data['payment_ref_no'])->exists()) {
            throw ValidationException::withMessages([
                'payment_ref_no' => ['This payment reference number already exists.']
            ])->status(409);
        }

        if ($data['payment_method'] === 'installment' && empty($data['schedules'])) {
            throw ValidationException::withMessages([
                'schedules' => ['Installment payments require at least one payment schedule.']
            ])->status(422);
        }

        $payment = DB::transaction(function () use ($data, $booking) {
            $payment = Payment::create([
                'booking_id'      => $data['booking_id'],
                'amount_paid'     => $data['amount_paid'],
                'payment_method'  => $data['payment_method'],
                'payment_date'    => $data['payment_date'],
                'payment_status'  => 'pending',
                'payment_ref_no'  => $data['payment_ref_no'],
            ]);

            if ($data['payment_method'] === 'installment' && !empty($data['schedules'])) {
                foreach ($data['schedules'] as $schedule) {
                    PaymentSchedule::create([
                        'payment_id' => $payment->id,
                        'due_date'   => $schedule['due_date'],
                        'amount_due' => $schedule['amount_due'],
                        'status'     => 'pending',
                    ]);
                }
            }

            // Re-query inside transaction to get accurate total including this new payment
            $totalPaid = $booking->payments()->sum('amount_paid');
            if ($totalPaid >= $booking->total_amount) {
                $booking->update(['booking_status' => 'fully_paid']);
            }

            return $payment;
        });

        return $payment;
    }

    public function markOverdueSchedules(): int
    {
        $overdueSchedules = PaymentSchedule::where('due_date', '<', now()->toDateString())
            ->where('status', '!=', 'paid')
            ->get();

        $count = 0;

        foreach ($overdueSchedules as $schedule) {
            try {
                $schedule->update(['status' => 'overdue']);
                $count++;
            } catch (\Throwable $e) {
                Log::error('Failed to mark schedule as overdue', [
                    'schedule_id' => $schedule->id,
                    'error'       => $e->getMessage(),
                ]);
            }
        }

        return $count;
    }
}
