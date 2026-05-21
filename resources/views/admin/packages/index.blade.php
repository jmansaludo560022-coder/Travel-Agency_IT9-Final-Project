@extends('layouts.admin')

@section('title', 'Travel Packages')

@section('content')

{{-- Tabs --}}
<div class="flex items-center gap-1 mb-4 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-1 w-fit">
    <a href="{{ route('admin.packages.index') }}"
        class="px-4 py-2 text-sm font-medium rounded-lg bg-sky-500 text-white transition flex items-center gap-2">
        Active
        @if($pendingCount > 0)
            <span class="px-1.5 py-0.5 text-xs bg-amber-400 text-white rounded-full">{{ $pendingCount }}</span>
        @endif
    </a>
    <a href="{{ route('admin.packages.archived') }}"
        class="px-4 py-2 text-sm font-medium rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-800 transition flex items-center gap-2">
        Archived
        @if($archivedCount > 0)
            <span class="px-1.5 py-0.5 text-xs bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20 rounded-full">{{ $archivedCount }}</span>
        @endif
    </a>
</div>

<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Active Travel Packages</h2>
            <p class="text-xs text-gray-500 mt-0.5">{{ $packages->total() }} packages total</p>
        </div>
        <a href="{{ route('admin.packages.create') }}"
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slots</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approval</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visibility</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bookings</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($packages as $package)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-900 dark:text-white">{{ Str::limit($package->package_name, 30) }}</p>
                        @if($package->package_type)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $package->package_type }}</p>
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
                        @php
                            $as = [
                                'pending_approval' => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-500/15 dark:text-amber-400 dark:border-amber-500/20',
                                'approved'         => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-500/15 dark:text-green-400 dark:border-green-500/20',
                                'rejected'         => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-500/15 dark:text-red-400 dark:border-red-500/20',
                            ][$package->approval_status] ?? 'bg-gray-100 text-gray-500 border-gray-200 dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600';
                            $asLabel = [
                                'pending_approval' => '⏳ Pending',
                                'approved'         => '✓ Approved',
                                'rejected'         => '✗ Rejected',
                            ][$package->approval_status] ?? '— Admin';
                        @endphp
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full border {{ $as }}">{{ $asLabel }}</span>
                        @if($package->pending_changes)
                            <span class="ml-1 px-2 py-0.5 text-xs bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400 rounded-full border border-amber-200 dark:border-amber-500/20">Edit pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.packages.toggle-visibility', $package->id) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="px-2.5 py-1 text-xs font-medium rounded-full border transition
                                    {{ $package->is_visible
                                        ? 'bg-green-100 text-green-700 border-green-200 dark:bg-green-500/15 dark:text-green-400 dark:border-green-500/20 hover:bg-green-200 dark:hover:bg-green-500/25'
                                        : 'bg-gray-100 text-gray-600 border-gray-200 dark:bg-gray-500/15 dark:text-gray-400 dark:border-gray-500/20 hover:bg-gray-200 dark:hover:bg-gray-500/25' }}">
                                {{ $package->is_visible ? '● Visible' : '○ Hidden' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $package->bookings_count }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.packages.show', $package->id) }}" class="text-xs text-sky-600 dark:text-sky-400 hover:text-sky-500 dark:hover:text-sky-300 transition">View</a>
                            <a href="{{ route('admin.packages.edit', $package->id) }}" class="text-xs text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">Edit</a>
                            @if($package->approval_status === 'pending_approval')
                                <form action="{{ route('admin.packages.approve', $package->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs text-green-600 dark:text-green-400 hover:underline font-medium">Approve</button>
                                </form>
                                <form action="{{ route('admin.packages.reject', $package->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs text-red-500 hover:underline">Reject</button>
                                </form>
                            @endif
                            <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('Archive this package?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-amber-600 dark:text-amber-500 hover:text-amber-500 dark:hover:text-amber-400 transition">Archive</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-10 text-center text-gray-400 dark:text-gray-600">No active packages found.</td>
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
