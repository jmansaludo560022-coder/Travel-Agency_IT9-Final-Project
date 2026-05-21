<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\TravelPackage;
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
        $query = Booking::with(['customer.userAccount', 'travelPackage.destination', 'employee'])
            ->orderBy('created_at', 'desc');

        if (request('status') && request('status') !== 'all') {
            $query->where('booking_status', request('status'));
        }

        $bookings = $query->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $packages = TravelPackage::with('destination')
            ->where('is_visible', true)
            ->where('slots_available', '>', 0)
            ->orderBy('package_name')
            ->get();

        $customers = Customer::with('userAccount')
            ->orderBy('cus_ln')
            ->get();

        return view('admin.bookings.create', compact('packages', 'customers'));
    }

    public function store(StoreBookingRequest $request)
    {
        $bookingData = [
            'customer_id' => $request->customer_id,
            'package_id'  => $request->package_id,
            'travel_date' => $request->travel_date,
            'employee_id' => null, // admin has no employee profile
        ];

        $booking = $this->bookingService->createBooking($bookingData, $request->travelers);

        return redirect()->route('admin.bookings.show', $booking->id)
            ->with('success', 'Booking created successfully.');
    }

    public function show($id)
    {
        $booking = Booking::with(['customer.userAccount', 'travelPackage.destination', 'travelers', 'payments', 'employee'])
            ->findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
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
