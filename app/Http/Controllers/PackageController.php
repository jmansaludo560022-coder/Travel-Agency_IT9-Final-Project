<?php

namespace App\Http\Controllers;

use App\Models\TravelPackage;
use App\Models\Destination;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = TravelPackage::with('destination')
            ->where('is_visible', true)
            ->where('slots_available', '>', 0);

        if ($request->filled('search')) {
            $query->where('package_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('destination_id')) {
            $query->where('destination_id', $request->destination_id);
        }

        if ($request->filled('package_type')) {
            $query->where('package_type', 'like', '%' . $request->package_type . '%');
        }

        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        match($request->input('sort', 'newest')) {
            'price_asc'  => $query->orderBy('package_cost', 'asc'),
            'price_desc' => $query->orderBy('package_cost', 'desc'),
            'date_asc'   => $query->orderBy('start_date', 'asc'),
            default      => $query->orderBy('created_at', 'desc'),
        };

        $packages     = $query->paginate(12)->withQueryString();
        $destinations = Destination::orderBy('city_name')->get();

        return view('packages.index', compact('packages', 'destinations'));
    }

    public function show($id)
    {
        $package = TravelPackage::with(['destination', 'employee'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->findOrFail($id);

        return view('packages.show', compact('package'));
    }
}
