@extends('layouts.agent')
@section('title', 'Archived Payments')
@section('content')

{{-- Tabs --}}
<div class="flex items-center gap-1 mb-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-1 w-fit">
    <a href="{{ route('agent.payments.index') }}"
        class="px-4 py-2 text-sm font-medium rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition">
        Active
    </a>
    <a href="{{ route('agent.payments.archived') }}"
        class="px-4 py-2 text-sm font-medium rounded-lg bg-sky-500 text-white transition">
        Archived
        <span class="ml-1 px-1.5 py-0.5 text-xs bg-white/20 rounded-full">{{ $payments->total() }}</span>
    </a>
</div>

<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Archived Payments</h2>
        <p class="text-xs text-gray-500 mt-0.5">These records are hidden from the active list. Restore to make them visible again.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Package</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Archived On</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition opacity-75">
                    <td class="px-6 py-4 text-gray-400 text-xs">#{{ $payment->id }}</td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $payment->booking->customer->cus_fn }} {{ $payment->booking->customer->cus_ln }}</td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ Str::limit($payment->booking->travelPackage->package_name, 25) }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-500 dark:text-gray-400">${{ number_format($payment->amount_paid, 2) }}</td>
                    <td class="px-6 py-4 text-gray-500 capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $payment->payment_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        @php
                            $sc = [
                                'verified' => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-500/15 dark:text-green-400 dark:border-green-500/20',
                                'pending'  => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-500/15 dark:text-amber-400 dark:border-amber-500/20',
                                'rejected' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-500/15 dark:text-red-400 dark:border-red-500/20',
                            ][$payment->payment_status] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                        @endphp
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full border {{ $sc }}">{{ ucfirst($payment->payment_status) }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $payment->deleted_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <form action="{{ route('agent.payments.restore', $payment->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('Restore this payment?')">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs text-green-600 dark:text-green-400 hover:underline font-medium">Restore</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-10 text-center text-gray-400 dark:text-gray-600">No archived payments.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($payments->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
        {{ $payments->links() }}
    </div>
    @endif
</div>
@endsection
