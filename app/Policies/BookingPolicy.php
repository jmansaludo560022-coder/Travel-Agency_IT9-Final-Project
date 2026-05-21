<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\UserAccount;

class BookingPolicy
{
    /**
     * Determine if the user can view any bookings.
     */
    public function viewAny(UserAccount $user): bool
    {
        return in_array($user->role, ['admin', 'agent', 'customer']);
    }

    /**
     * Determine if the user can view the booking.
     */
    public function view(UserAccount $user, Booking $booking): bool
    {
        if (in_array($user->role, ['admin', 'agent'])) {
            return true;
        }

        if ($user->role === 'customer') {
            return $booking->customer_id === $user->customer->id;
        }

        return false;
    }

    /**
     * Determine if the user can create bookings.
     */
    public function create(UserAccount $user): bool
    {
        return in_array($user->role, ['admin', 'agent', 'customer']);
    }

    /**
     * Determine if the user can update the booking.
     */
    public function update(UserAccount $user, Booking $booking): bool
    {
        if (in_array($user->role, ['admin', 'agent'])) {
            return true;
        }

        if ($user->role === 'customer') {
            return $booking->customer_id === $user->customer->id;
        }

        return false;
    }

    /**
     * Determine if the user can cancel the booking.
     */
    public function cancel(UserAccount $user, Booking $booking): bool
    {
        if (in_array($user->role, ['admin', 'agent'])) {
            return true;
        }

        if ($user->role === 'customer') {
            return $booking->customer_id === $user->customer->id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the booking.
     */
    public function delete(UserAccount $user, Booking $booking): bool
    {
        return $user->role === 'admin';
    }
}
