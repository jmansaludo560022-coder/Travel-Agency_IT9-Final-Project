<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['customer', 'booking.travelPackage'])
            ->latest();

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('replied')) {
            $request->replied === 'yes'
                ? $query->whereNotNull('review_reply')
                : $query->whereNull('review_reply');
        }

        $reviews = $query->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function show($id)
    {
        $review = Review::with(['customer', 'booking.travelPackage'])->findOrFail($id);
        return view('admin.reviews.show', compact('review'));
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }
}
