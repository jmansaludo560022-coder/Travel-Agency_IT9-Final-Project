<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StorePaymentRequest;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $payments = Payment::with(['booking.customer', 'booking.travelPackage'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    public function create()
    {
        $bookingId = request('booking_id');

        // If a booking_id is pre-selected, load it directly
        $booking = null;
        $balance = null;
        if ($bookingId) {
            $booking = Booking::with(['customer', 'travelPackage'])
                ->whereNotIn('booking_status', ['cancelled', 'fully_paid'])
                ->findOrFail($bookingId);
            $balance = $booking->total_amount - $booking->payments()->sum('amount_paid');
        }

        // All bookings that still have an outstanding balance (for the dropdown)
        $bookings = Booking::with(['customer', 'travelPackage'])
            ->whereNotIn('booking_status', ['cancelled', 'fully_paid'])
            ->get()
            ->filter(fn($b) => $b->total_amount > $b->payments()->sum('amount_paid'))
            ->values();

        return view('admin.payments.create', compact('bookings', 'booking', 'balance'));
    }

    public function store(StorePaymentRequest $request)
    {
        try {
            $payment = $this->paymentService->recordPayment($request->validated());
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        }

        return redirect()->route('admin.payments.show', $payment->id)
            ->with('success', 'Payment recorded successfully.');
    }

    public function show($id)
    {
        $payment = Payment::with(['booking.customer', 'booking.travelPackage', 'paymentSchedules'])
            ->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }

    public function verify($id)
    {
        $payment = Payment::findOrFail($id);
        $this->authorize('verify', $payment);

        DB::transaction(function () use ($payment) {
            $payment->update(['payment_status' => 'verified']);
        });

        return redirect()->back()->with('success', 'Payment verified successfully.');
    }

    public function reject($id)
    {
        $payment = Payment::findOrFail($id);
        $this->authorize('reject', $payment);

        DB::transaction(function () use ($payment) {
            $payment->update(['payment_status' => 'rejected']);
        });

        return redirect()->back()->with('success', 'Payment rejected.');
    }

    public function archive($id)
    {
        $payment = Payment::findOrFail($id);

        DB::transaction(function () use ($payment) {
            $payment->delete(); // soft delete
        });

        return redirect()->back()->with('success', 'Payment archived.');
    }

    public function restore($id)
    {
        $payment = Payment::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($payment) {
            $payment->restore();
        });

        return redirect()->route('admin.payments.archived')->with('success', 'Payment restored.');
    }

    public function archived()
    {
        $payments = Payment::onlyTrashed()
            ->with(['booking.customer', 'booking.travelPackage'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return view('admin.payments.archived', compact('payments'));
    }
}
