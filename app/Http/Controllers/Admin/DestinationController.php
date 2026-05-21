<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDestinationRequest;
use App\Models\Destination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::withCount('travelPackages')->paginate(15);
        $archivedCount = Destination::onlyTrashed()->count();
        return view('admin.destinations.index', compact('destinations', 'archivedCount'));
    }

    public function archived()
    {
        $destinations = Destination::onlyTrashed()->withCount('travelPackages')->paginate(15);
        return view('admin.destinations.archived', compact('destinations'));
    }

    public function create()
    {
        return view('admin.destinations.create');
    }

    public function store(StoreDestinationRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();

            // Handle file upload
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('destinations', 'public');
                $data['image'] = $path;
            }

            Destination::create($data);
        });

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination created successfully.');
    }

    public function show($id)
    {
        $destination = Destination::withCount('travelPackages')->findOrFail($id);
        return view('admin.destinations.show', compact('destination'));
    }

    public function edit($id)
    {
        $destination = Destination::findOrFail($id);
        return view('admin.destinations.edit', compact('destination'));
    }

    public function update(StoreDestinationRequest $request, $id)
    {
        $destination = Destination::findOrFail($id);

        DB::transaction(function () use ($request, $destination) {
            $data = $request->validated();

            // Handle file upload — delete old image if new one uploaded
            if ($request->hasFile('image')) {
                if ($destination->image) {
                    Storage::disk('public')->delete($destination->image);
                }
                $path = $request->file('image')->store('destinations', 'public');
                $data['image'] = $path;
            } else {
                // Keep existing image
                unset($data['image']);
            }

            $destination->update($data);
        });

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination updated successfully.');
    }

    public function destroy($id)
    {
        $destination = Destination::findOrFail($id);
        DB::transaction(fn() => $destination->delete());
        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination archived successfully.');
    }

    public function restore($id)
    {
        $destination = Destination::onlyTrashed()->findOrFail($id);
        DB::transaction(fn() => $destination->restore());
        return redirect()->route('admin.destinations.archived')
            ->with('success', 'Destination restored successfully.');
    }
}
