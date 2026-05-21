<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreBookingRequest;
use App\Models\Booking;
use App\Models\TravelPackage;
use App\Models\Customer;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index()
    {
        $bookings = Booking::with(['customer.userAccount', 'travelPackage.destination'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('agent.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['customer.userAccount', 'travelPackage.destination', 'travelers', 'payments'])
            ->findOrFail($id);
            
        return view('agent.bookings.show', compact('booking'));
    }

    public function create()
    {
        $packages = TravelPackage::with('destination')
            ->where('is_visible', true)
            ->where('slots_available', '>', 0)
            ->get();
            
        $customers = Customer::with('userAccount')->get();
        
        return view('agent.bookings.create', compact('packages', 'customers'));
    }

    public function store(StoreBookingRequest $request)
    {
        $employeeId = auth()->user()->employee->id;
        
        $bookingData = [
            'customer_id' => $request->customer_id,
            'package_id' => $request->package_id,
            'travel_date' => $request->travel_date,
            'employee_id' => $employeeId,
        ];
        
        $booking = $this->bookingService->createBooking($bookingData, $request->travelers);
        
        return redirect()->route('agent.bookings.show', $booking->id)
            ->with('success', 'Booking created successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,cancelled,completed,fully_paid']
        ]);

        try {
            $this->bookingService->updateStatus($booking, $request->status);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->with('error', collect($e->errors())->flatten()->first());
        }
        
        return redirect()->back()
            ->with('success', 'Booking status updated successfully.');
    }
}
