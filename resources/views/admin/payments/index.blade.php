@extends('layouts.admin')

@section('title', 'Payments')

@section('content')

{{-- Tabs --}}
<div class="flex items-center gap-1 mb-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-1 w-fit">
    <a href="{{ route('admin.payments.index') }}"
        class="px-4 py-2 text-sm font-medium rounded-lg bg-sky-500 text-white transition">
        Active
    </a>
    <a href="{{ route('admin.payments.archived') }}"
        class="px-4 py-2 text-sm font-medium rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition">
        Archived
    </a>
</div>

<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">All Payments</h2>
            <p class="text-xs text-gray-500 mt-0.5">{{ $payments->total() }} total payments</p>
        </div>
        <a href="{{ route('admin.payments.create') }}"
            class="flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Record Payment
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                    <td class="px-6 py-4 text-gray-500 text-xs">#{{ $payment->id }}</td>
                    <td class="px-6 py-4 text-gray-900 dark:text-gray-300">{{ $payment->booking->customer->cus_fn }} {{ $payment->booking->customer->cus_ln }}</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ Str::limit($payment->booking->travelPackage->package_name, 25) }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">${{ number_format($payment->amount_paid, 2) }}</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400 capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                    <td class="px-6 py-4 text-gray-500 text-xs">{{ $payment->payment_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        @php
                            $sc = ['verified'=>'bg-green-100 text-green-700 border-green-200 dark:bg-green-500/15 dark:text-green-400 dark:border-green-500/20','pending'=>'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-500/15 dark:text-amber-400 dark:border-amber-500/20','rejected'=>'bg-red-100 text-red-700 border-red-200 dark:bg-red-500/15 dark:text-red-400 dark:border-red-500/20'][$payment->payment_status] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                        @endphp
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full border {{ $sc }}">{{ ucfirst($payment->payment_status) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.payments.show', $payment->id) }}" class="text-xs text-sky-600 dark:text-sky-400 hover:text-sky-500 dark:hover:text-sky-300 transition">View</a>
                            @if($payment->payment_status === 'pending')
                                <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs text-green-600 dark:text-green-400 hover:text-green-500 transition">Verify</button>
                                </form>
                                <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-400 transition">Reject</button>
                                </form>
                            @endif
                            <form action="{{ route('admin.payments.archive', $payment->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('Archive this payment? It can be restored later.')">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">Archive</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-10 text-center text-gray-400 dark:text-gray-600">No payments found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
        {{ $payments->links() }}
    </div>
</div>
@endsection
