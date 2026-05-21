@extends('layouts.customer')
@section('title', 'Booking Details')
@section('content')

<div class="max-w-3xl mx-auto bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Booking #{{ $booking->id }}</h2>
            @php
                $sc = [
                    'confirmed'  => 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400',
                    'pending'    => 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400',
                    'cancelled'  => 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400',
                    'completed'  => 'bg-sky-100 text-sky-700 dark:bg-sky-500/15 dark:text-sky-400',
                    'fully_paid' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-400',
                ][$booking->booking_status] ?? 'bg-gray-100 text-gray-600';
            @endphp
            <span class="mt-1 inline-block px-2.5 py-0.5 text-xs font-medium rounded-full {{ $sc }}">
                {{ ucfirst(str_replace('_', ' ', $booking->booking_status)) }}
            </span>
        </div>
        <a href="{{ route('customer.bookings.index') }}"
            class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
            Back to Bookings
        </a>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Package Details -->
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Package Details</h3>
                <div class="space-y-3 text-sm">
                    <div><p class="text-xs text-gray-500">Package Name</p><p class="text-gray-900 dark:text-white mt-0.5 font-medium">{{ $booking->travelPackage->package_name }}</p></div>
                    <div><p class="text-xs text-gray-500">Destination</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $booking->travelPackage->destination->city_name }}, {{ $booking->travelPackage->destination->country }}</p></div>
                    <div><p class="text-xs text-gray-500">Travel Date</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ \Carbon\Carbon::parse($booking->travel_date)->format('F d, Y') }}</p></div>
                    <div><p class="text-xs text-gray-500">Booking Date</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $booking->booking_date->format('F d, Y') }}</p></div>
                </div>
            </div>

            <!-- Payment Summary -->
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Payment Summary</h3>
                @php $paid = $booking->payments->sum('amount_paid'); $balance = $booking->total_amount - $paid; @endphp
                <div class="grid grid-cols-3 gap-3 text-center mb-4">
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-0.5">Total</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">${{ number_format($booking->total_amount, 2) }}</p>
                    </div>
                    <div class="bg-green-50 dark:bg-green-500/10 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-0.5">Paid</p>
                        <p class="text-sm font-bold text-green-600 dark:text-green-400">${{ number_format($paid, 2) }}</p>
                    </div>
                    <div class="bg-amber-50 dark:bg-amber-500/10 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-0.5">Balance</p>
                        <p class="text-sm font-bold text-amber-600 dark:text-amber-400">${{ number_format($balance, 2) }}</p>
                    </div>
                </div>

                @if($booking->booking_status !== 'cancelled' && $balance > 0)
                <a href="{{ route('customer.payments.create', ['booking_id' => $booking->id]) }}"
                    class="block w-full text-center px-4 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">
                    Make Payment
                </a>
                @endif
            </div>
        </div>

        <!-- Travelers -->
        @if($booking->travelers->count())
        <div class="mt-8">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Travelers ({{ $booking->travelers->count() }})</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($booking->travelers as $traveler)
                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                    <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $traveler->trav_fn }} {{ $traveler->trav_mn }} {{ $traveler->trav_ln }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ ucfirst($traveler->gender) }} · {{ $traveler->nationality }}</p>
                    <p class="text-xs text-gray-500">Born: {{ \Carbon\Carbon::parse($traveler->trav_birthdate)->format('M d, Y') }}</p>
                    @if($traveler->passport_no)
                    <p class="text-xs text-gray-500 font-mono">Passport: {{ $traveler->passport_no }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Payment History -->
        @if($booking->payments->count())
        <div class="mt-8">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Payment History</h3>
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($booking->payments as $payment)
                        <tr>
                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $payment->payment_date->format('M d, Y') }}</td>
                            <td class="px-4 py-2 font-semibold text-gray-900 dark:text-white">${{ number_format($payment->amount_paid, 2) }}</td>
                            <td class="px-4 py-2 text-gray-600 dark:text-gray-400 capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                            <td class="px-4 py-2">
                                @php
                                    $ps = [
                                        'verified' => 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400',
                                        'pending'  => 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400',
                                        'rejected' => 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400',
                                    ][$payment->payment_status] ?? '';
                                @endphp
                                <span class="px-2 py-0.5 text-xs rounded-full {{ $ps }}">{{ ucfirst($payment->payment_status) }}</span>
                            </td>
                            <td class="px-4 py-2 text-gray-500 text-xs font-mono">{{ $payment->payment_ref_no }}</td>
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
