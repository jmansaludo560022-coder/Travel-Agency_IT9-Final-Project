@extends('layouts.agent')
@section('title', 'Package Details')
@section('content')

@php
    $approvalColor = [
        'pending_approval' => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-500/15 dark:text-amber-400 dark:border-amber-500/20',
        'approved'         => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-500/15 dark:text-green-400 dark:border-green-500/20',
        'rejected'         => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-500/15 dark:text-red-400 dark:border-red-500/20',
    ][$package->approval_status] ?? 'bg-gray-100 text-gray-500 border-gray-200';

    $approvalLabel = [
        'pending_approval' => 'Pending Admin Approval',
        'approved'         => 'Approved',
        'rejected'         => 'Rejected — Please resubmit',
    ][$package->approval_status] ?? 'No approval required';
@endphp

<div class="max-w-3xl mx-auto space-y-4">

    {{-- Header --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">{{ $package->package_name }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-2.5 py-0.5 text-xs font-medium rounded-full border {{ $approvalColor }}">
                        {{ $approvalLabel }}
                    </span>
                    @if($package->pending_changes)
                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full border bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-500/15 dark:text-amber-400 dark:border-amber-500/20">
                            Edit changes pending approval
                        </span>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if(!$package->pending_changes)
                    <a href="{{ route('agent.packages.edit', $package->id) }}"
                        class="px-3 py-1.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-semibold rounded-lg transition">
                        Edit &amp; Resubmit
                    </a>
                @else
                    <span class="px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-400 text-xs font-semibold rounded-lg cursor-not-allowed"
                        title="Edit pending approval — wait for admin to review">
                        Edit &amp; Resubmit
                    </span>
                @endif
                <a href="{{ route('agent.packages.index') }}"
                    class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    Back
                </a>
            </div>
        </div>

        {{-- Approval notice banners --}}
        @if($package->approval_status === 'pending_approval' && !$package->pending_changes)
        <div class="mx-6 mt-4 flex items-start gap-3 p-3.5 rounded-lg bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-sm text-amber-700 dark:text-amber-400">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            This package is awaiting admin approval. It will not be visible to customers until approved.
        </div>
        @endif

        @if($package->approval_status === 'rejected')
        <div class="mx-6 mt-4 flex items-start gap-3 p-3.5 rounded-lg bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-sm text-red-700 dark:text-red-400">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            This package was rejected by admin. Click "Edit &amp; Resubmit" to make changes and resubmit for approval.
        </div>
        @endif

        @if($package->pending_changes)
        <div class="mx-6 mt-4 flex items-start gap-3 p-3.5 rounded-lg bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-sm text-amber-700 dark:text-amber-400">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            You have submitted edits that are awaiting admin approval. The current details below reflect the live version. Changes will be applied once approved.
        </div>
        @endif

        {{-- Package details --}}
        <div class="p-6 grid grid-cols-2 gap-4 text-sm">
            <div><p class="text-xs text-gray-500">Destination</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $package->destination->city_name }}, {{ $package->destination->country }}</p></div>
            <div><p class="text-xs text-gray-500">Package Type</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $package->package_type ?? '—' }}</p></div>
            <div><p class="text-xs text-gray-500">Cost per Person</p><p class="text-xl font-bold text-sky-600 dark:text-sky-400 mt-0.5">${{ number_format($package->package_cost, 2) }}</p></div>
            <div><p class="text-xs text-gray-500">Slots Available</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $package->slots_available }}</p></div>
            <div><p class="text-xs text-gray-500">Start Date</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ \Carbon\Carbon::parse($package->start_date)->format('F d, Y') }}</p></div>
            <div><p class="text-xs text-gray-500">End Date</p><p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ \Carbon\Carbon::parse($package->end_date)->format('F d, Y') }}</p></div>
            <div><p class="text-xs text-gray-500">Visibility</p>
                <span class="mt-1 inline-block px-2.5 py-0.5 text-xs font-medium rounded-full border
                    {{ $package->is_visible
                        ? 'bg-green-100 text-green-700 border-green-200 dark:bg-green-500/15 dark:text-green-400 dark:border-green-500/20'
                        : 'bg-gray-100 text-gray-500 border-gray-200 dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600' }}">
                    {{ $package->is_visible ? '● Visible to customers' : '○ Hidden from customers' }}
                </span>
            </div>
            <div><p class="text-xs text-gray-500">Total Bookings</p><p class="font-medium text-gray-900 dark:text-white mt-0.5">{{ $package->bookings_count }}</p></div>
        </div>
    </div>

    {{-- Description --}}
    @if($package->description)
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-5">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Description</h3>
        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $package->description }}</p>
    </div>
    @endif

    {{-- Inclusions / Exclusions --}}
    @if($package->inclusions || $package->exclusions)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @if($package->inclusions)
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Inclusions</h3>
            <ul class="space-y-1">
                @foreach(array_filter(explode("\n", $package->inclusions)) as $item)
                <li class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <svg class="w-3.5 h-3.5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    {{ trim($item) }}
                </li>
                @endforeach
            </ul>
        </div>
        @endif
        @if($package->exclusions)
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Exclusions</h3>
            <ul class="space-y-1">
                @foreach(array_filter(explode("\n", $package->exclusions)) as $item)
                <li class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <svg class="w-3.5 h-3.5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    {{ trim($item) }}
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    @endif

    {{-- Itinerary --}}
    @if($package->itinerary)
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-5">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Itinerary</h3>
        <div class="space-y-2">
            @foreach(array_filter(explode("\n", $package->itinerary)) as $line)
            <div class="flex items-start gap-3 text-sm">
                <span class="flex-shrink-0 w-16 text-xs font-semibold text-sky-600 dark:text-sky-400 pt-0.5">
                    {{ Str::before(trim($line), ':') }}:
                </span>
                <span class="text-gray-700 dark:text-gray-300">{{ trim(Str::after(trim($line), ': ')) }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
