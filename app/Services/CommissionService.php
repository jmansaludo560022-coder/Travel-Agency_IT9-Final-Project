<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Employee;
use Carbon\Carbon;

class CommissionService
{
    /**
     * Calculate commission for a single booking
     */
    public function calculateForBooking(Booking $booking): float
    {
        if (!$booking->employee) {
            return 0.0;
        }

        return (float) ($booking->total_amount * $booking->employee->commission_rate);
    }

    /**
     * Commission report for a single employee within a date range
     */
    public function reportForEmployee(Employee $employee, Carbon $from, Carbon $to): array
    {
        $bookings = Booking::where('employee_id', $employee->id)
            ->where('booking_status', 'confirmed')
            ->whereBetween('booking_date', [$from->startOfDay(), $to->endOfDay()])
            ->get();

        $totalCommission = $bookings->sum(function ($booking) use ($employee) {
            return $booking->total_amount * $employee->commission_rate;
        });

        return [
            'employee_id' => $employee->id,
            'employee_name' => $employee->emp_fn . ' ' . $employee->emp_ln,
            'commission_rate' => $employee->commission_rate,
            'bookings_count' => $bookings->count(),
            'total_sales' => $bookings->sum('total_amount'),
            'total_commission' => round($totalCommission, 2),
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ];
    }

    /**
     * Commission report for all employees within a date range
     */
    public function reportForAll(Carbon $from, Carbon $to): array
    {
        $employees = Employee::with(['bookings' => function ($q) use ($from, $to) {
            $q->where('booking_status', 'confirmed')
              ->whereBetween('booking_date', [$from->startOfDay(), $to->endOfDay()]);
        }])->get();

        $report = [];

        foreach ($employees as $employee) {
            $totalSales = $employee->bookings->sum('total_amount');
            $totalCommission = $totalSales * $employee->commission_rate;

            $report[] = [
                'employee_id' => $employee->id,
                'employee_name' => $employee->emp_fn . ' ' . $employee->emp_ln,
                'commission_rate' => $employee->commission_rate,
                'bookings_count' => $employee->bookings->count(),
                'total_sales' => round($totalSales, 2),
                'total_commission' => round($totalCommission, 2),
            ];
        }

        return $report;
    }
}
