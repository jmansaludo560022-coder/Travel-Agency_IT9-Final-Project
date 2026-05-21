<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\TravelPackage;
use App\Models\Traveler;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingService
{
    /**
     * Create a new booking with travelers
     */
    public function createBooking(array $data, array $travelers): Booking
    {
        $package = TravelPackage::findOrFail($data['package_id']);

        // Validate package is visible
        if (!$package->is_visible) {
            throw ValidationException::withMessages([
                'package_id' => ['This package is not available for booking.']
            ])->status(422);
        }

        // Validate slots are available
        if ($package->slots_available <= 0) {
            throw ValidationException::withMessages([
                'package_id' => ['No slots available for this package.']
            ])->status(422);
        }

        // Validate enough slots for travelers
        if ($package->slots_available < count($travelers)) {
            throw ValidationException::withMessages([
                'travelers' => ['Not enough slots available. Only ' . $package->slots_available . ' slots remaining.']
            ])->status(422);
        }

        // Calculate total amount
        $totalAmount = $package->package_cost * count($travelers);

        $booking = DB::transaction(function () use ($data, $travelers, $package, $totalAmount) {
            // Create booking
            $booking = Booking::create([
                'customer_id' => $data['customer_id'],
                'package_id' => $data['package_id'],
                'employee_id' => $data['employee_id'] ?? null,
                'booking_date' => now(),
                'travel_date' => $data['travel_date'],
                'total_amount' => $totalAmount,
                'booking_status' => 'pending',
            ]);

            // Create travelers
            foreach ($travelers as $travelerData) {
                Traveler::create([
                    'booking_id' => $booking->id,
                    'trav_fn' => $travelerData['trav_fn'],
                    'trav_mn' => $travelerData['trav_mn'] ?? null,
                    'trav_ln' => $travelerData['trav_ln'],
                    'trav_birthdate' => $travelerData['trav_birthdate'],
                    'gender' => $travelerData['gender'],
                    'passport_no' => $travelerData['passport_no'] ?? null,
                    'nationality' => $travelerData['nationality'],
                ]);
            }

            return $booking;
        });

        return $booking;
    }

    /**
     * Update booking status
     */
    public function updateStatus(Booking $booking, string $status): void
    {
        // Validate status=confirmed requires at least one payment
        if ($status === 'confirmed') {
            if ($booking->payments()->count() === 0) {
                throw ValidationException::withMessages([
                    'status' => ['Cannot confirm booking without at least one payment.']
                ])->status(422);
            }
        }

        DB::transaction(function () use ($booking, $status) {
            $oldStatus = $booking->booking_status;
            
            // Update booking status
            $booking->update(['booking_status' => $status]);

            // If confirming, decrement slots_available
            if ($status === 'confirmed' && $oldStatus !== 'confirmed') {
                $package = $booking->travelPackage;
                $package->decrement('slots_available', 1);
            }
        });
    }

    /**
     * Cancel a booking
     */
    public function cancelBooking(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {
            $wasConfirmed = $booking->booking_status === 'confirmed';
            
            // Update booking status to cancelled
            $booking->update(['booking_status' => 'cancelled']);

            // If was confirmed, increment slots_available back
            if ($wasConfirmed) {
                $package = $booking->travelPackage;
                $package->increment('slots_available', 1);
            }
        });
    }
}
