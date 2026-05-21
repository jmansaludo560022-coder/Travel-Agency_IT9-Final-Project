@extends('layouts.agent')
@section('title', 'Booking Details')
@section('content')

@php
    $sc = [
        'confirmed'  => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-500/15 dark:text-green-400 dark:border-green-500/20',
        'pending'    => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-500/15 dark:text-amber-400 dark:border-amber-500/20',
        'cancelled'  => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-500/15 dark:text-red-400 dark:border-red-500/20',
        'completed'  => 'bg-sky-100 text-sky-700 border-sky-200 dark:bg-sky-500/15 dark:text-sky-400 dark:border-sky-500/20',
        'fully_paid' => 'bg-indigo-100 text-indigo-700 border-indigo-200 dark:bg-indigo-500/15 dark:text-indigo-400 dark:border-indigo-500/20',
    ][$booking->booking_status] ?? 'bg-gray-100 text-gray-600 border-gray-200';
    $input = 'w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm';
@endphp

<div class="max-w-3xl mx-auto space-y-4">

    {{-- Header --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Booking #{{ $booking->id }}</h2>
                <span class="mt-1 inline-block px-2.5 py-0.5 text-xs font-medium rounded-full border {{ $sc }}">
                    {{ ucfirst(str_replace('_', ' ', $booking->booking_status)) }}
                </span>
            </div>
            <a href="{{ route('agent.bookings.index') }}"
                class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                Back
            </a>
        </div>
        <div class="p-6 grid grid-cols-2 gap-4 text-sm">
            <div><p class="text-xs text-gray-500">Customer</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $booking->customer->cus_fn }} {{ $booking->customer->cus_ln }}</p></div>
            <div><p class="text-xs text-gray-500">Package</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $booking->travelPackage->package_name }}</p></div>
            <div><p class="text-xs text-gray-500">Travel Date</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ \Carbon\Carbon::parse($booking->travel_date)->format('F d, Y') }}</p></div>
            <div><p class="text-xs text-gray-500">Booking Date</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $booking->booking_date->format('F d, Y') }}</p></div>
            <div><p class="text-xs text-gray-500">Total Amount</p><p class="text-xl font-bold text-gray-900 dark:text-white mt-0.5">${{ number_format($booking->total_amount, 2) }}</p></div>
        </div>
    </div>

    {{-- Update Status --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-5">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Update Status</h3>
        <form action="{{ route('agent.bookings.update-status', $booking->id) }}" method="POST" class="flex items-center gap-3">
            @csrf @method('PATCH')
            <select name="status" class="{{ $input }} flex-1">
                @foreach(['pending','confirmed','cancelled','completed','fully_paid'] as $s)
                    <option value="{{ $s }}" {{ $booking->booking_status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">Update</button>
        </form>
    </div>

    {{-- Travelers --}}
    @if($booking->travelers->count())
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Travelers ({{ $booking->travelers->count() }})</h3>
        </div>
        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($booking->travelers as $traveler)
            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $traveler->trav_fn }} {{ $traveler->trav_ln }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ ucfirst($traveler->gender) }} · {{ $traveler->nationality }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Payments --}}
    @if($booking->payments->count())
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Payments</h3>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    <th class="px-6 py-2 text-left text-xs text-gray-500">Date</th>
                    <th class="px-6 py-2 text-left text-xs text-gray-500">Amount</th>
                    <th class="px-6 py-2 text-left text-xs text-gray-500">Method</th>
                    <th class="px-6 py-2 text-left text-xs text-gray-500">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($booking->payments as $payment)
                <tr>
                    <td class="px-6 py-3 text-gray-600 dark:text-gray-400 text-xs">{{ $payment->payment_date->format('M d, Y') }}</td>
                    <td class="px-6 py-3 font-semibold text-gray-900 dark:text-white">${{ number_format($payment->amount_paid, 2) }}</td>
                    <td class="px-6 py-3 text-gray-600 dark:text-gray-400 capitalize">{{ str_replace('_',' ',$payment->payment_method) }}</td>
                    <td class="px-6 py-3">
                        @php $ps = ['verified'=>'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400','pending'=>'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400','rejected'=>'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400'][$payment->payment_status] ?? ''; @endphp
                        <span class="px-2 py-0.5 text-xs rounded-full {{ $ps }}">{{ ucfirst($payment->payment_status) }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
