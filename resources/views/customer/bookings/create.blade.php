@extends('layouts.customer')

@section('title', 'Create Booking')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Package info card --}}
    @if($package)
    <div class="mb-6 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-5 flex items-center justify-between">
        <div>
            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $package->package_name }}</h3>
            <p class="text-sm text-gray-500 mt-0.5">{{ $package->destination->city_name }}, {{ $package->destination->country }}</p>
        </div>
        <div class="text-right">
            <p class="text-2xl font-bold text-sky-500">${{ number_format($package->package_cost, 2) }}</p>
            <p class="text-xs text-gray-400">per person</p>
        </div>
    </div>
    @endif

    {{-- Validation errors summary --}}
    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-xl">
        <p class="text-sm font-semibold text-red-700 dark:text-red-400 mb-2">Please fix the following errors:</p>
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li class="text-sm text-red-600 dark:text-red-400">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('customer.bookings.store') }}" method="POST" id="bookingForm">
        @csrf
        <input type="hidden" name="package_id" value="{{ $package->id ?? old('package_id') }}">

        {{-- Travel Date --}}
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-5 mb-4">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-sky-500 text-white text-xs font-bold flex items-center justify-center">1</span>
                Travel Date
            </h3>
            <div class="max-w-xs">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Select your travel date</label>
                <input type="date" name="travel_date" value="{{ old('travel_date') }}" required
                    min="{{ now()->toDateString() }}"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm">
                @error('travel_date')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Travelers --}}
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-5 mb-4">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-sky-500 text-white text-xs font-bold flex items-center justify-center">2</span>
                Traveler Information
            </h3>

            <div id="travelers-container" class="space-y-4">
                {{-- Traveler 1 (static) --}}
                <div class="traveler-entry border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Traveler 1</h4>
                    </div>
                    @include('customer.bookings._traveler_fields', ['index' => 0])
                </div>
            </div>

            <button type="button" onclick="addTraveler()"
                class="mt-4 flex items-center gap-2 px-4 py-2 text-sm font-medium text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-500/10 hover:bg-sky-100 dark:hover:bg-sky-500/20 border border-sky-200 dark:border-sky-500/20 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Add Another Traveler
            </button>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('customer.bookings.index') }}"
                class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-700 transition">
                Cancel
            </a>
            <button type="submit"
                class="px-6 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                Confirm Booking
            </button>
        </div>
    </form>
</div>

<script>
let travelerCount = 1;

function addTraveler() {
    const container = document.getElementById('travelers-container');
    const idx = travelerCount;
    const div = document.createElement('div');
    div.className = 'traveler-entry border border-gray-200 dark:border-gray-700 rounded-lg p-4';
    div.innerHTML = `
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Traveler ${idx + 1}</h4>
            <button type="button" onclick="this.closest('.traveler-entry').remove(); renumberTravelers();"
                class="text-xs text-red-500 hover:text-red-700 dark:hover:text-red-400">Remove</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">First Name</label>
                <input type="text" name="travelers[${idx}][trav_fn]" required
                    class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Middle Name <span class="text-gray-400">(opt.)</span></label>
                <input type="text" name="travelers[${idx}][trav_mn]"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Last Name</label>
                <input type="text" name="travelers[${idx}][trav_ln]" required
                    class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Date of Birth</label>
                <input type="date" name="travelers[${idx}][trav_birthdate]" required max="{{ now()->subDay()->toDateString() }}"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Gender</label>
                <select name="travelers[${idx}][gender]" required
                    class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Nationality</label>
                <input type="text" name="travelers[${idx}][nationality]" required
                    class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
            </div>
            <div class="md:col-span-3">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Passport No <span class="text-gray-400">(opt.)</span></label>
                <input type="text" name="travelers[${idx}][passport_no]"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
            </div>
        </div>
    `;
    container.appendChild(div);
    travelerCount++;
}

function renumberTravelers() {
    document.querySelectorAll('.traveler-entry').forEach((el, i) => {
        const heading = el.querySelector('h4');
        if (heading) heading.textContent = `Traveler ${i + 1}`;
    });
}
</script>
@endsection
