@extends('layouts.agent')

@section('title', 'Create Booking')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Create New Booking</h2>
        <a href="{{ route('agent.bookings.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-sm">Back</a>
    </div>

    <form action="{{ route('agent.bookings.store') }}" method="POST" id="bookingForm">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                <select name="customer_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('customer_id') border-red-500 @enderror">
                    <option value="">Select Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->cus_fn }} {{ $customer->cus_ln }} ({{ $customer->userAccount->username }})
                        </option>
                    @endforeach
                </select>
                @error('customer_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Package</label>
                <select name="package_id" id="package_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('package_id') border-red-500 @enderror">
                    <option value="">Select Package</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}"
                            data-cost="{{ $package->package_cost }}"
                            {{ old('package_id') == $package->id ? 'selected' : '' }}>
                            {{ $package->package_name }} — {{ $package->destination->city_name }}, {{ $package->destination->country }}
                            (${{ number_format($package->package_cost, 2) }}/person)
                        </option>
                    @endforeach
                </select>
                @error('package_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Travel Date</label>
                <input type="date" name="travel_date" value="{{ old('travel_date') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('travel_date') border-red-500 @enderror">
                @error('travel_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Travelers --}}
        <div id="travelers-container">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-gray-800">Travelers</h3>
                <span class="text-sm text-gray-500">Total: <span id="total-amount">$0.00</span></span>
            </div>

            <div class="traveler-entry mb-4 p-4 border border-gray-200 rounded bg-gray-50">
                <h4 class="font-medium text-gray-700 mb-3">Traveler 1</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" name="travelers[0][trav_fn]" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                        <input type="text" name="travelers[0][trav_mn]" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" name="travelers[0][trav_ln]" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Birthdate</label>
                        <input type="date" name="travelers[0][trav_birthdate]" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <select name="travelers[0][gender]" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nationality</label>
                        <input type="text" name="travelers[0][nationality]" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Passport No <span class="text-gray-400">(optional)</span></label>
                        <input type="text" name="travelers[0][passport_no]" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    </div>
                </div>
            </div>
        </div>

        <button type="button" onclick="addTraveler()"
            class="mb-6 px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm">
            + Add Another Traveler
        </button>

        <div class="flex items-center gap-3 pt-6 border-t">
            <button type="submit" class="px-6 py-2 bg-sky-500 text-white rounded hover:bg-sky-600 text-sm">
                Create Booking
            </button>
            <a href="{{ route('agent.bookings.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-sm">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
let travelerCount = 1;

function updateTotal() {
    const select = document.getElementById('package_id');
    const selected = select.options[select.selectedIndex];
    const cost = parseFloat(selected?.dataset?.cost || 0);
    const count = document.querySelectorAll('.traveler-entry').length;
    document.getElementById('total-amount').textContent = '$' + (cost * count).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

document.getElementById('package_id').addEventListener('change', updateTotal);

function addTraveler() {
    const container = document.getElementById('travelers-container');
    const div = document.createElement('div');
    div.className = 'traveler-entry mb-4 p-4 border border-gray-200 rounded bg-gray-50 relative';
    div.innerHTML = `
        <div class="flex items-center justify-between mb-3">
            <h4 class="font-medium text-gray-700">Traveler ${travelerCount + 1}</h4>
            <button type="button" onclick="removeTraveler(this)" class="text-red-500 hover:text-red-700 text-sm">Remove</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">First Name</label><input type="text" name="travelers[${travelerCount}][trav_fn]" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label><input type="text" name="travelers[${travelerCount}][trav_mn]" class="w-full px-3 py-2 border border-gray-300 rounded text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label><input type="text" name="travelers[${travelerCount}][trav_ln]" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Birthdate</label><input type="date" name="travelers[${travelerCount}][trav_birthdate]" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Gender</label><select name="travelers[${travelerCount}][gender]" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm"><option value="male">Male</option><option value="female">Female</option><option value="other">Other</option></select></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Nationality</label><input type="text" name="travelers[${travelerCount}][nationality]" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm"></div>
            <div class="md:col-span-3"><label class="block text-sm font-medium text-gray-700 mb-1">Passport No <span class="text-gray-400">(optional)</span></label><input type="text" name="travelers[${travelerCount}][passport_no]" class="w-full px-3 py-2 border border-gray-300 rounded text-sm"></div>
        </div>
    `;
    container.appendChild(div);
    travelerCount++;
    updateTotal();
}

function removeTraveler(btn) {
    btn.closest('.traveler-entry').remove();
    updateTotal();
}
</script>
@endsection
