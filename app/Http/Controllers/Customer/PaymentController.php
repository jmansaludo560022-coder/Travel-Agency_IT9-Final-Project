<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StorePaymentRequest;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $customerId = auth()->user()->customer->id;

        $payments = Payment::whereHas('booking', function ($q) use ($customerId) {
            $q->where('customer_id', $customerId);
        })->with(['booking.travelPackage'])->orderBy('created_at', 'desc')->paginate(10);

        return view('customer.payments.index', compact('payments'));
    }

    public function create()
    {
        $customerId = auth()->user()->customer->id;
        $bookingId = request('booking_id');

        $booking = Booking::where('customer_id', $customerId)
            ->whereNotIn('booking_status', ['cancelled', 'fully_paid'])
            ->findOrFail($bookingId);

        $amountPaid = $booking->payments()->sum('amount_paid');
        $balance = $booking->total_amount - $amountPaid;

        return view('customer.payments.create', compact('booking', 'balance'));
    }

    public function store(StorePaymentRequest $request)
    {
        try {
            $payment = $this->paymentService->recordPayment($request->validated());
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        }

        return redirect()->route('customer.payments.show', $payment->id)
            ->with('success', 'Payment submitted successfully. Awaiting verification.');
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);

        $payment->load(['booking.travelPackage', 'paymentSchedules']);
        return view('customer.payments.show', compact('payment'));
    }
}
