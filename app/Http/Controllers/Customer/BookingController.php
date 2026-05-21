<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreBookingRequest;
use App\Models\Booking;
use App\Models\TravelPackage;
use App\Services\BookingService;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
        $this->authorizeResource(Booking::class, 'booking');
    }

    public function index()
    {
        $customerId = auth()->user()->customer->id;
        
        $bookings = Booking::where('customer_id', $customerId)
            ->with(['travelPackage.destination', 'travelers'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('customer.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $packageId = request('package_id');
        $package = null;
        
        if ($packageId) {
            $package = TravelPackage::with('destination')
                ->where('id', $packageId)
                ->where('is_visible', true)
                ->where('slots_available', '>', 0)
                ->first();
        }
        
        return view('customer.bookings.create', compact('package'));
    }

    public function store(StoreBookingRequest $request)
    {
        $customerId = auth()->user()->customer->id;
        
        $bookingData = [
            'customer_id' => $customerId,
            'package_id' => $request->package_id,
            'travel_date' => $request->travel_date,
        ];
        
        $booking = $this->bookingService->createBooking($bookingData, $request->travelers);
        
        return redirect()->route('customer.bookings.show', $booking->id)
            ->with('success', 'Booking created successfully! Please proceed with payment.');
    }

    public function show(Booking $booking)
    {
        $booking->load(['travelPackage.destination', 'travelers', 'payments']);
        return view('customer.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        $this->authorize('cancel', $booking);
        
        if ($booking->booking_status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending bookings can be cancelled.');
        }
        
        $this->bookingService->cancelBooking($booking);
        
        return redirect()->route('customer.bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }
}
