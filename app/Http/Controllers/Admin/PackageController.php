<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePackageRequest;
use App\Models\TravelPackage;
use App\Models\Destination;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    public function index()
    {
        $packages = TravelPackage::with(['destination', 'employee'])
            ->withCount('bookings')
            ->paginate(15);

        $archivedCount   = TravelPackage::onlyTrashed()->count();
        $pendingCount    = TravelPackage::whereIn('approval_status', ['pending_approval'])->count();

        return view('admin.packages.index', compact('packages', 'archivedCount', 'pendingCount'));
    }

    public function archived()
    {
        $packages = TravelPackage::onlyTrashed()
            ->with(['destination', 'employee'])
            ->withCount('bookings')
            ->paginate(15);

        return view('admin.packages.archived', compact('packages'));
    }

    public function create()
    {
        $destinations = Destination::orderBy('city_name')->get();
        return view('admin.packages.create', compact('destinations'));
    }

    public function store(StorePackageRequest $request)
    {
        DB::transaction(function () use ($request) {
            TravelPackage::create($request->validated());
        });

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package created successfully.');
    }

    public function show($id)
    {
        $package = TravelPackage::with(['destination', 'employee'])
            ->withCount('bookings')
            ->findOrFail($id);
        return view('admin.packages.show', compact('package'));
    }

    public function edit($id)
    {
        $package = TravelPackage::findOrFail($id);
        $destinations = Destination::orderBy('city_name')->get();
        return view('admin.packages.edit', compact('package', 'destinations'));
    }

    public function update(StorePackageRequest $request, $id)
    {
        $package = TravelPackage::findOrFail($id);

        DB::transaction(function () use ($request, $package) {
            $package->update($request->validated());
        });

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package updated successfully.');
    }

    /**
     * Archive (soft delete) instead of permanent delete.
     */
    public function destroy($id)
    {
        $package = TravelPackage::findOrFail($id);

        // Block archive if active bookings exist
        if ($package->bookings()->whereNotIn('booking_status', ['cancelled'])->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot archive package. There are active bookings associated with it.');
        }

        DB::transaction(function () use ($package) {
            $package->delete(); // soft delete — sets deleted_at
        });

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package archived successfully.');
    }

    /**
     * Restore an archived package.
     */
    public function restore($id)
    {
        $package = TravelPackage::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($package) {
            $package->restore();
        });

        return redirect()->route('admin.packages.archived')
            ->with('success', 'Package restored successfully.');
    }

    public function toggleVisibility($id)
    {
        $package = TravelPackage::findOrFail($id);

        DB::transaction(function () use ($package) {
            $package->update(['is_visible' => !$package->is_visible]);
        });

        $status = $package->fresh()->is_visible ? 'visible' : 'hidden';
        return redirect()->back()
            ->with('success', "Package is now {$status}.");
    }

    /**
     * Approve a package submitted by an agent (new or edit).
     */
    public function approve($id)
    {
        $package = TravelPackage::findOrFail($id);

        DB::transaction(function () use ($package) {
            $updates = ['approval_status' => 'approved'];

            // If there are pending edit changes, apply them now
            if ($package->pending_changes) {
                $updates = array_merge($updates, $package->pending_changes, [
                    'pending_changes' => null,
                ]);
            }

            $package->update($updates);
        });

        return redirect()->back()
            ->with('success', 'Package approved. Admin can now make it visible to customers.');
    }

    /**
     * Reject a package or pending edit from an agent.
     */
    public function reject($id)
    {
        $package = TravelPackage::findOrFail($id);

        DB::transaction(function () use ($package) {
            $package->update([
                'approval_status' => 'rejected',
                'pending_changes' => null,
            ]);
        });

        return redirect()->back()
            ->with('success', 'Package rejected. The agent will need to resubmit.');
    }
}
