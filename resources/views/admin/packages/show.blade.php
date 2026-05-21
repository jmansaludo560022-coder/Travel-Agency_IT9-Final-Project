@extends('layouts.admin')
@section('title', 'Package Details')
@section('content')
<div class="max-w-3xl space-y-4">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Package Details</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.packages.edit', $package->id) }}" class="px-3 py-1.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-semibold rounded-lg transition">Edit</a>
                <a href="{{ route('admin.packages.index') }}" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">Back</a>
            </div>
        </div>
        @if($package->image)
            <img src="{{ $package->image }}" alt="{{ $package->package_name }}" class="w-full h-52 object-cover">
        @endif
        <div class="p-6 grid grid-cols-2 gap-4 text-sm">
            <div class="col-span-2"><p class="text-xs text-gray-500">Package Name</p><p class="text-xl font-bold text-gray-900 dark:text-white mt-0.5">{{ $package->package_name }}</p></div>
            <div><p class="text-xs text-gray-500">Destination</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $package->destination->city_name }}, {{ $package->destination->country }}</p></div>
            <div><p class="text-xs text-gray-500">Type</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $package->package_type ?? '—' }}</p></div>
            <div><p class="text-xs text-gray-500">Cost</p><p class="text-2xl font-bold text-sky-600 dark:text-sky-400 mt-0.5">${{ number_format($package->package_cost, 2) }}</p></div>
            <div><p class="text-xs text-gray-500">Slots Available</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $package->slots_available }}</p></div>
            <div><p class="text-xs text-gray-500">Start Date</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ \Carbon\Carbon::parse($package->start_date)->format('F d, Y') }}</p></div>
            <div><p class="text-xs text-gray-500">End Date</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ \Carbon\Carbon::parse($package->end_date)->format('F d, Y') }}</p></div>
            <div><p class="text-xs text-gray-500">Visibility</p>
                <span class="mt-1 inline-block px-2.5 py-0.5 text-xs font-medium rounded-full
                    {{ $package->is_visible ? 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                    {{ $package->is_visible ? 'Visible' : 'Hidden' }}
                </span>
            </div>
            <div><p class="text-xs text-gray-500">Total Bookings</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $package->bookings_count }}</p></div>
        </div>
        @if($package->description)
        <div class="px-6 pb-4 border-t border-gray-100 dark:border-gray-800 pt-4">
            <p class="text-xs text-gray-500 mb-1">Description</p>
            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $package->description }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
