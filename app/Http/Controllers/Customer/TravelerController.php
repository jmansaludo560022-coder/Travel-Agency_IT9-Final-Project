<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Traveler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TravelerController extends Controller
{
    public function index(Booking $booking)
    {
        $this->authorize('view', $booking);
        
        $travelers = $booking->travelers;
        return view('customer.travelers.index', compact('booking', 'travelers'));
    }

    public function create(Booking $booking)
    {
        $this->authorize('view', $booking);
        
        return view('customer.travelers.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        $this->authorize('view', $booking);
        
        $request->validate([
            'trav_fn' => ['required', 'string', 'max:100'],
            'trav_mn' => ['nullable', 'string', 'max:100'],
            'trav_ln' => ['required', 'string', 'max:100'],
            'trav_birthdate' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female,other'],
            'passport_no' => ['nullable', 'string', 'max:50'],
            'nationality' => ['required', 'string', 'max:100'],
        ]);

        DB::transaction(function () use ($request, $booking) {
            Traveler::create([
                'booking_id' => $booking->id,
                'trav_fn' => $request->trav_fn,
                'trav_mn' => $request->trav_mn,
                'trav_ln' => $request->trav_ln,
                'trav_birthdate' => $request->trav_birthdate,
                'gender' => $request->gender,
                'passport_no' => $request->passport_no,
                'nationality' => $request->nationality,
            ]);
            
            // Recalculate total amount
            $travelerCount = $booking->travelers()->count() + 1;
            $booking->update([
                'total_amount' => $booking->travelPackage->package_cost * $travelerCount
            ]);
        });

        return redirect()->route('customer.bookings.travelers.index', $booking->id)
            ->with('success', 'Traveler added successfully.');
    }

    public function edit(Booking $booking, Traveler $traveler)
    {
        $this->authorize('view', $booking);
        
        return view('customer.travelers.edit', compact('booking', 'traveler'));
    }

    public function update(Request $request, Booking $booking, Traveler $traveler)
    {
        $this->authorize('view', $booking);
        
        $request->validate([
            'trav_fn' => ['required', 'string', 'max:100'],
            'trav_mn' => ['nullable', 'string', 'max:100'],
            'trav_ln' => ['required', 'string', 'max:100'],
            'trav_birthdate' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female,other'],
            'passport_no' => ['nullable', 'string', 'max:50'],
            'nationality' => ['required', 'string', 'max:100'],
        ]);

        DB::transaction(function () use ($request, $traveler) {
            $traveler->update($request->all());
        });

        return redirect()->route('customer.bookings.travelers.index', $booking->id)
            ->with('success', 'Traveler updated successfully.');
    }

    public function destroy(Booking $booking, Traveler $traveler)
    {
        $this->authorize('view', $booking);
        
        DB::transaction(function () use ($booking, $traveler) {
            $traveler->delete();
            
            // Recalculate total amount
            $travelerCount = $booking->travelers()->count();
            if ($travelerCount > 0) {
                $booking->update([
                    'total_amount' => $booking->travelPackage->package_cost * $travelerCount
                ]);
            }
        });

        return redirect()->route('customer.bookings.travelers.index', $booking->id)
            ->with('success', 'Traveler removed successfully.');
    }
}
