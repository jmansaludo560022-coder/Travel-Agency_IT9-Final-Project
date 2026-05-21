@extends('layouts.customer')

@section('title', 'Booking Details')

@section('content')
<div class="max-w-3xl mx-auto bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="p-6 border-b">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Booking #{{ $booking->id }}</h2>
                <span class="mt-2 inline-block px-3 py-1 text-sm rounded-full 
                    {{ $booking->booking_status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $booking->booking_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $booking->booking_status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                    {{ $booking->booking_status === 'completed' ? 'bg-sky-100 text-sky-800' : '' }}
                    {{ $booking->booking_status === 'fully_paid' ? 'bg-sky-100 text-sky-800' : '' }}">
                    {{ ucfirst($booking->booking_status) }}
                </span>
            </div>
            <a href="{{ route('customer.bookings.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                Back to Bookings
            </a>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Package Details</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Package Name</label>
                        <p class="text-gray-900">{{ $booking->travelPackage->package_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Destination</label>
                        <p class="text-gray-900">{{ $booking->travelPackage->destination->city_name }}, {{ $booking->travelPackage->destination->country }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Travel Date</label>
                        <p class="text-gray-900">{{ \Carbon\Carbon::parse($booking->travel_date)->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Booking Date</label>
                        <p class="text-gray-900">{{ $booking->booking_date->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Summary</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Total Amount</label>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($booking->total_amount, 2) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Amount Paid</label>
                        <p class="text-lg font-semibold text-green-600">${{ number_format($booking->payments->sum('amount_paid'), 2) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Balance</label>
                        <p class="text-lg font-semibold text-red-600">${{ number_format($booking->total_amount - $booking->payments->sum('amount_paid'), 2) }}</p>
                    </div>
                </div>

                @if($booking->booking_status !== 'cancelled' && $booking->total_amount > $booking->payments->sum('amount_paid'))
                    <a href="{{ route('customer.payments.create', ['booking_id' => $booking->id]) }}" 
                        class="mt-4 block w-full text-center px-4 py-2 bg-sky-500 text-white rounded hover:bg-sky-600">
                        Make Payment
                    </a>
                @endif
            </div>
        </div>

        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Travelers ({{ $booking->travelers->count() }})</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($booking->travelers as $traveler)
                    <div class="p-4 border border-gray-200 rounded">
                        <p class="font-medium text-gray-900">{{ $traveler->trav_fn }} {{ $traveler->trav_mn }} {{ $traveler->trav_ln }}</p>
                        <p class="text-sm text-gray-600">{{ ucfirst($traveler->gender) }} • {{ $traveler->nationality }}</p>
                        <p class="text-sm text-gray-600">Born: {{ \Carbon\Carbon::parse($traveler->trav_birthdate)->format('M d, Y') }}</p>
                        @if($traveler->passport_no)
                            <p class="text-sm text-gray-600">Passport: {{ $traveler->passport_no }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        @if($booking->payments->count() > 0)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment History</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($booking->payments as $payment)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-2 text-sm font-semibold text-gray-900">${{ number_format($payment->amount_paid, 2) }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $payment->payment_status === 'verified' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $payment->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $payment->payment_status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($payment->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $payment->payment_ref_no }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
