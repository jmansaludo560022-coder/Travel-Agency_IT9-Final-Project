<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\StorePackageRequest;
use App\Models\TravelPackage;
use App\Models\Destination;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    public function index()
    {
        $employeeId = auth()->user()->employee->id;

        $packages = TravelPackage::where('employee_id', $employeeId)
            ->with(['destination'])
            ->withCount('bookings')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('agent.packages.index', compact('packages'));
    }

    public function create()
    {
        $destinations = Destination::orderBy('city_name')->get();
        return view('agent.packages.create', compact('destinations'));
    }

    public function store(StorePackageRequest $request)
    {
        $employeeId = auth()->user()->employee->id;

        DB::transaction(function () use ($request, $employeeId) {
            TravelPackage::create(array_merge(
                $request->validated(),
                [
                    'employee_id'     => $employeeId,
                    'is_visible'      => false,          // hidden until admin approves
                    'approval_status' => 'pending_approval',
                ]
            ));
        });

        return redirect()->route('agent.packages.index')
            ->with('success', 'Package submitted for admin approval. It will be visible to customers once approved.');
    }

    public function show(TravelPackage $package)
    {
        $this->authorizePackageAccess($package);
        $package->load(['destination', 'employee'])->loadCount('bookings');
        return view('agent.packages.show', compact('package'));
    }

    public function edit(TravelPackage $package)
    {
        $this->authorizePackageAccess($package);

        // Block editing if there are already pending changes awaiting approval
        if ($package->pending_changes) {
            return redirect()->route('agent.packages.show', $package->id)
                ->with('error', 'This package already has pending changes awaiting admin approval. Please wait for the admin to review before submitting new changes.');
        }

        $destinations = Destination::orderBy('city_name')->get();
        return view('agent.packages.edit', compact('package', 'destinations'));
    }

    public function update(StorePackageRequest $request, TravelPackage $package)
    {
        $this->authorizePackageAccess($package);

        DB::transaction(function () use ($request, $package) {
            // Store changes as pending — do NOT apply them yet
            $package->update([
                'pending_changes' => $request->validated(),
                'approval_status' => 'pending_approval',
            ]);
        });

        return redirect()->route('agent.packages.show', $package->id)
            ->with('success', 'Changes submitted for admin approval. They will be applied once approved.');
    }

    // ── No destroy — agents can only request deletion via support ──

    /**
     * Ensure the agent only accesses their own packages.
     */
    private function authorizePackageAccess(TravelPackage $package): void
    {
        $employeeId = auth()->user()->employee->id;
        if ($package->employee_id !== $employeeId) {
            abort(403, 'You do not have permission to access this package.');
        }
    }
}
