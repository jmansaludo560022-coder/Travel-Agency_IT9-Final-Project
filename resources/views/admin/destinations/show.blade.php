@extends('layouts.admin')
@section('title', 'Destination Details')
@section('content')
<div class="max-w-2xl">
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Destination Details</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.destinations.edit', $destination->id) }}" class="px-3 py-1.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-semibold rounded-lg transition">Edit</a>
            <a href="{{ route('admin.destinations.index') }}" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">Back</a>
        </div>
    </div>
    <div class="p-6 space-y-4">
        @if($destination->image)
            <img src="{{ Storage::url($destination->image) }}" alt="{{ $destination->city_name }}" class="w-full h-48 object-cover rounded-lg">
        @endif
        <div class="grid grid-cols-2 gap-4">
            <div><p class="text-xs text-gray-500">City</p><p class="font-semibold text-gray-900 dark:text-white mt-0.5">{{ $destination->city_name }}</p></div>
            <div><p class="text-xs text-gray-500">Country</p><p class="font-semibold text-gray-900 dark:text-white mt-0.5">{{ $destination->country }}</p></div>
            <div><p class="text-xs text-gray-500">Packages</p><p class="font-semibold text-gray-900 dark:text-white mt-0.5">{{ $destination->travel_packages_count }}</p></div>
            <div><p class="text-xs text-gray-500">Created</p><p class="text-gray-600 dark:text-gray-400 text-sm mt-0.5">{{ $destination->created_at->format('M d, Y') }}</p></div>
        </div>
        @if($destination->description)
            <div><p class="text-xs text-gray-500 mb-1">Description</p><p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">{{ $destination->description }}</p></div>
        @endif
    </div>
</div>
</div>
@endsection
