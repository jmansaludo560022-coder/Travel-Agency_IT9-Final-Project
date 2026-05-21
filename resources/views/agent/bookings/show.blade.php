@extends('layouts.agent')

@section('title', 'Booking Details')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Booking #{{ $booking->id }}</h2>
        <a href="{{ route('agent.bookings.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
            Back
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Booking Information</h3>
            <div class="space-y-2">
                <p><span class="font-medium">Customer:</span> {{ $booking->customer->cus_fn }} {{ $booking->customer->cus_ln }}</p>
                <p><span class="font-medium">Package:</span> {{ $booking->travelPackage->package_name }}</p>
                <p><span class="font-medium">Travel Date:</span> {{ \Carbon\Carbon::parse($booking->travel_date)->format('F d, Y') }}</p>
                <p><span class="font-medium">Booking Date:</span> {{ $booking->booking_date->format('F d, Y') }}</p>
                <p><span class="font-medium">Total Amount:</span> ${{ number_format($booking->total_amount, 2) }}</p>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Update Status</h3>
            <form action="{{ route('agent.bookings.update-status', $booking->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded mb-3">
                    <option value="pending" {{ $booking->booking_status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $booking->booking_status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ $booking->booking_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="completed" {{ $booking->booking_status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="fully_paid" {{ $booking->booking_status === 'fully_paid' ? 'selected' : '' }}>Fully Paid</option>
                </select>
                <button type="submit" class="w-full px-4 py-2 bg-sky-500 text-white rounded hover:bg-sky-600">
                    Update Status
                </button>
            </form>
        </div>
    </div>

    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">Travelers ({{ $booking->travelers->count() }})</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($booking->travelers as $traveler)
                <div class="p-4 border border-gray-200 rounded">
                    <p class="font-medium">{{ $traveler->trav_fn }} {{ $traveler->trav_ln }}</p>
                    <p class="text-sm text-gray-600">{{ ucfirst($traveler->gender) }} • {{ $traveler->nationality }}</p>
                </div>
            @endforeach
        </div>
    </div>

    @if($booking->payments->count() > 0)
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Payments</h3>
            <table class="w-full border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Amount</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Method</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($booking->payments as $payment)
                        <tr>
                            <td class="px-4 py-2 text-sm">{{ $payment->payment_date->format('M d, Y') }}</td>
                            <td class="px-4 py-2 text-sm font-semibold">${{ number_format($payment->amount_paid, 2) }}</td>
                            <td class="px-4 py-2 text-sm">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                            <td class="px-4 py-2 text-sm">{{ ucfirst($payment->payment_status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
