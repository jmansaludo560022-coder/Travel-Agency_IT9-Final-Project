@extends('layouts.agent')

@section('title', 'Edit Package')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b">
        <h2 class="text-xl font-semibold text-gray-800">Edit Travel Package</h2>
    </div>

    <form action="{{ route('agent.packages.update', $package->id) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="package_name" class="block text-sm font-medium text-gray-700 mb-2">Package Name</label>
                <input type="text" name="package_name" id="package_name" value="{{ old('package_name', $package->package_name) }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('package_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="destination_id" class="block text-sm font-medium text-gray-700 mb-2">Destination</label>
                <select name="destination_id" id="destination_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach($destinations as $destination)
                        <option value="{{ $destination->id }}" {{ old('destination_id', $package->destination_id) == $destination->id ? 'selected' : '' }}>
                            {{ $destination->city_name }}, {{ $destination->country }}
                        </option>
                    @endforeach
                </select>
                @error('destination_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label for="package_type" class="block text-sm font-medium text-gray-700 mb-2">Package Type</label>
                <select name="package_type" id="package_type"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('package_type') border-red-500 @enderror">
                    <option value="">Select Type</option>
                    @foreach(['Beach', 'Adventure', 'Cultural', 'Nature', 'City', 'Luxury', 'Family', 'Honeymoon', 'Wildlife', 'Cruise'] as $type)
                        <option value="{{ $type }}" {{ old('package_type', $package->package_type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
                @error('package_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="package_cost" class="block text-sm font-medium text-gray-700 mb-2">Package Cost ($)</label>
                <input type="number" name="package_cost" id="package_cost" step="0.01" min="0" value="{{ old('package_cost', $package->package_cost) }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('package_cost')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slots_available" class="block text-sm font-medium text-gray-700 mb-2">Slots Available</label>
                <input type="number" name="slots_available" id="slots_available" min="0" value="{{ old('slots_available', $package->slots_available) }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('slots_available')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $package->start_date) }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('start_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $package->end_date) }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('end_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-sky-500">{{ old('description', $package->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <x-package-form-fields :package="$package" />
        </div>

        <div class="mb-6">
            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Image URL</label>
            <input type="text" name="image" id="image" value="{{ old('image', $package->image) }}"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
            @error('image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_visible" value="1" {{ old('is_visible', $package->is_visible) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-sky-600 focus:ring-indigo-500">
                <span class="ml-2 text-sm text-gray-700">Make package visible to customers</span>
            </label>
            @error('is_visible')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end space-x-4 pt-6 border-t">
            <a href="{{ route('agent.packages.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded hover:bg-gray-200">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-sky-500 text-white rounded hover:bg-sky-600">
                Update Package
            </button>
        </div>
    </form>
</div>
@endsection
