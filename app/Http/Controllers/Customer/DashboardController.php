<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $customer = auth()->user()->customer;

        $myBookingsCount = Booking::where('customer_id', $customer->id)->count();

        $upcomingTrips = Booking::where('customer_id', $customer->id)
            ->whereIn('booking_status', ['confirmed', 'fully_paid'])
            ->where('travel_date', '>=', now())
            ->count();

        $pendingPayments = Payment::whereHas('booking', function ($q) use ($customer) {
            $q->where('customer_id', $customer->id);
        })->where('payment_status', 'pending')->count();

        $recentBookings = Booking::where('customer_id', $customer->id)
            ->with(['travelPackage.destination'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('customer.dashboard', compact(
            'myBookingsCount',
            'upcomingTrips',
            'pendingPayments',
            'recentBookings'
        ));
    }
}
