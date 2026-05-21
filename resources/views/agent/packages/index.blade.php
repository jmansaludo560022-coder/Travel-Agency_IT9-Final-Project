@extends('layouts.agent')
@section('title', 'My Packages')
@section('content')

<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">My Travel Packages</h2>
            <p class="text-xs text-gray-500 mt-0.5">Packages you submit are reviewed by admin before becoming visible to customers</p>
        </div>
        <a href="{{ route('agent.packages.create') }}"
            class="flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New Package
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Package</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destination</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dates</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slots</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approval</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Visible</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bookings</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($packages as $package)
                @php
                    $approvalColor = [
                        'pending_approval' => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-500/15 dark:text-amber-400 dark:border-amber-500/20',
                        'approved'         => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-500/15 dark:text-green-400 dark:border-green-500/20',
                        'rejected'         => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-500/15 dark:text-red-400 dark:border-red-500/20',
                    ][$package->approval_status] ?? 'bg-gray-100 text-gray-500 border-gray-200 dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600';

                    $approvalLabel = [
                        'pending_approval' => '⏳ Pending',
                        'approved'         => '✓ Approved',
                        'rejected'         => '✗ Rejected',
                    ][$package->approval_status] ?? '— N/A';
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-900 dark:text-white">{{ Str::limit($package->package_name, 28) }}</p>
                        @if($package->package_type)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $package->package_type }}</p>
                        @endif
                        @if($package->pending_changes)
                            <span class="inline-block mt-1 px-2 py-0.5 text-xs bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400 rounded-full border border-amber-200 dark:border-amber-500/20">
                                Edit awaiting approval
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                        {{ $package->destination->city_name }}, {{ $package->destination->country }}
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">${{ number_format($package->package_cost, 2) }}</td>
                    <td class="px-6 py-4 text-gray-500 text-xs">
                        {{ \Carbon\Carbon::parse($package->start_date)->format('M d') }} – {{ \Carbon\Carbon::parse($package->end_date)->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $package->slots_available }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full border {{ $approvalColor }}">
                            {{ $approvalLabel }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        {{-- Visibility is read-only for agents — controlled by admin --}}
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full border
                            {{ $package->is_visible
                                ? 'bg-green-100 text-green-700 border-green-200 dark:bg-green-500/15 dark:text-green-400 dark:border-green-500/20'
                                : 'bg-gray-100 text-gray-500 border-gray-200 dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600' }}">
                            {{ $package->is_visible ? '● Visible' : '○ Hidden' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $package->bookings_count }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('agent.packages.show', $package->id) }}"
                                class="text-xs text-sky-600 dark:text-sky-400 hover:underline">View</a>
                            {{-- Edit only allowed if no pending changes already waiting --}}
                            @if(!$package->pending_changes)
                                <a href="{{ route('agent.packages.edit', $package->id) }}"
                                    class="text-xs text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">Edit</a>
                            @else
                                <span class="text-xs text-gray-300 dark:text-gray-600 cursor-not-allowed" title="Edit pending approval">Edit</span>
                            @endif
                            {{-- No delete — agents cannot delete packages --}}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-10 text-center text-gray-400 dark:text-gray-600">
                        No packages yet. Create your first package and submit it for admin approval.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
        {{ $packages->links() }}
    </div>
</div>
@endsection
