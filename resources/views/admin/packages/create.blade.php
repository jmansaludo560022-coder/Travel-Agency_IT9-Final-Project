@extends('layouts.admin')
@section('title', 'Create Package')
@section('content')
<div class="max-w-3xl mx-auto">
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Create New Travel Package</h2>
    </div>
    <form action="{{ route('admin.packages.store') }}" method="POST" class="p-6 space-y-5">
        @csrf
        @php $input = 'w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm'; $label = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5'; @endphp

        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="{{ $label }}">Package Name</label>
                <input type="text" name="package_name" value="{{ old('package_name') }}" required class="{{ $input }}">
                @error('package_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="{{ $label }}">Destination</label>
                <select name="destination_id" required class="{{ $input }}">
                    <option value="">Select Destination</option>
                    @foreach($destinations as $d)
                        <option value="{{ $d->id }}" {{ old('destination_id') == $d->id ? 'selected' : '' }}>{{ $d->city_name }}, {{ $d->country }}</option>
                    @endforeach
                </select>
                @error('destination_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="{{ $label }}">Package Type</label>
                <input type="text" name="package_type" value="{{ old('package_type') }}" placeholder="e.g., Adventure, Luxury" class="{{ $input }}">
            </div>
            <div>
                <label class="{{ $label }}">Package Cost ($)</label>
                <input type="number" name="package_cost" step="0.01" min="0" value="{{ old('package_cost') }}" required class="{{ $input }}">
                @error('package_cost')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="{{ $label }}">Slots Available</label>
                <input type="number" name="slots_available" min="0" value="{{ old('slots_available', 0) }}" required class="{{ $input }}">
                @error('slots_available')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="{{ $label }}">Start Date</label>
                <input type="date" name="start_date" value="{{ old('start_date') }}" required class="{{ $input }}">
                @error('start_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="{{ $label }}">End Date</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" required class="{{ $input }}">
                @error('end_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="{{ $label }}">Description</label>
            <textarea name="description" rows="3" class="{{ $input }}">{{ old('description') }}</textarea>
        </div>

        <x-package-form-fields :inputClass="$input" :labelClass="$label" />
        <div>
            <label class="{{ $label }}">Image URL</label>
            <input type="text" name="image" value="{{ old('image') }}" placeholder="https://..." class="{{ $input }}">
        </div>
        <div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_visible" value="1" {{ old('is_visible') ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-sky-500 focus:ring-sky-500">
                <span class="text-sm text-gray-700 dark:text-gray-300">Make package visible to customers</span>
            </label>
        </div>
        <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-200 dark:border-gray-800">
            <a href="{{ route('admin.packages.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-700 transition">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">Create Package</button>
        </div>
    </form>
</div>
</div>
@endsection
