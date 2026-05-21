<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\UserAccount;

class ReviewPolicy
{
    /**
     * Customer can create a review only if:
     * - booking is completed
     * - booking belongs to the customer
     * - no existing review for that booking
     */
    public function create(UserAccount $user, $booking): bool
    {
        if ($user->role !== 'customer') {
            return false;
        }

        if ($booking->customer_id !== $user->customer->id) {
            return false;
        }

        if ($booking->booking_status !== 'completed') {
            return false;
        }

        if ($booking->review()->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Customer can update their own review
     */
    public function update(UserAccount $user, Review $review): bool
    {
        if ($user->role !== 'customer') {
            return false;
        }

        return $review->customer_id === $user->customer->id;
    }

    /**
     * Only agents can reply to reviews
     */
    public function reply(UserAccount $user, Review $review): bool
    {
        return $user->role === 'agent';
    }

    /**
     * Admin can delete reviews
     */
    public function delete(UserAccount $user, Review $review): bool
    {
        return $user->role === 'admin';
    }
}
