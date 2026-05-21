<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreReviewRequest;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function create()
    {
        $customerId = auth()->user()->customer->id;

        // Get completed bookings without a review
        $bookings = Booking::where('customer_id', $customerId)
            ->where('booking_status', 'completed')
            ->doesntHave('review')
            ->with('travelPackage')
            ->get();

        $bookingId = request('booking_id');

        return view('customer.reviews.create', compact('bookings', 'bookingId'));
    }

    public function store(StoreReviewRequest $request)
    {
        $customerId = auth()->user()->customer->id;
        $booking = Booking::findOrFail($request->booking_id);

        $this->authorize('create', [Review::class, $booking]);

        DB::transaction(function () use ($request, $customerId) {
            Review::create([
                'customer_id' => $customerId,
                'booking_id' => $request->booking_id,
                'rating' => $request->rating,
                'review_text' => $request->review_text,
            ]);
        });

        return redirect()->route('customer.bookings.show', $request->booking_id)
            ->with('success', 'Review submitted successfully. Thank you!');
    }

    public function show(Review $review)
    {
        $this->authorize('update', $review);
        return view('customer.reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
        $this->authorize('update', $review);
        return view('customer.reviews.edit', compact('review'));
    }

    public function update(StoreReviewRequest $request, Review $review)
    {
        $this->authorize('update', $review);

        DB::transaction(function () use ($request, $review) {
            $review->update([
                'rating' => $request->rating,
                'review_text' => $request->review_text,
            ]);
        });

        return redirect()->route('customer.bookings.show', $review->booking_id)
            ->with('success', 'Review updated successfully.');
    }
}
