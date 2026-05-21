<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\TravelPackage;
use App\Services\CommissionService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    public function index()
    {
        $totalBookings = Booking::count();

        $totalRevenue = Booking::whereIn('booking_status', ['confirmed', 'fully_paid'])
            ->sum('total_amount');

        $activePackages = TravelPackage::where('is_visible', true)
            ->where('slots_available', '>', 0)
            ->count();

        $pendingPayments = Payment::where('payment_status', 'pending')->count();

        $recentBookings = Booking::with(['customer', 'travelPackage.destination'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $commissionSummary = $this->commissionService->reportForAll(
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        );

        $totalCommission = collect($commissionSummary)->sum('total_commission');

        return view('admin.dashboard', compact(
            'totalBookings',
            'totalRevenue',
            'activePackages',
            'pendingPayments',
            'recentBookings',
            'commissionSummary',
            'totalCommission'
        ));
    }
}
