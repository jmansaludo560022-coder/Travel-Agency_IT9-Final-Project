<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
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
        $employee = auth()->user()->employee;

        // Show bookings for packages created by this agent OR directly assigned to this agent
        $bookingQuery = Booking::where(function ($q) use ($employee) {
            $q->where('employee_id', $employee->id)
              ->orWhereHas('travelPackage', function ($pq) use ($employee) {
                  $pq->where('employee_id', $employee->id);
              });
        });

        $myBookingsCount = (clone $bookingQuery)->count();

        $commissionThisMonth = $this->commissionService->reportForEmployee(
            $employee,
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        );

        $pendingVerifications = Payment::whereHas('booking', function ($q) use ($employee) {
            $q->where(function ($bq) use ($employee) {
                $bq->where('employee_id', $employee->id)
                   ->orWhereHas('travelPackage', function ($pq) use ($employee) {
                       $pq->where('employee_id', $employee->id);
                   });
            });
        })->where('payment_status', 'pending')->count();

        $recentBookings = (clone $bookingQuery)
            ->with(['customer', 'travelPackage.destination'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('agent.dashboard', compact(
            'myBookingsCount',
            'commissionThisMonth',
            'pendingVerifications',
            'recentBookings'
        ));
    }
}
