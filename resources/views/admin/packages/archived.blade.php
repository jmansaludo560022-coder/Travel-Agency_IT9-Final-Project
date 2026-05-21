@extends('layouts.admin')
@section('title', 'Archived Packages')
@section('content')

{{-- Tabs --}}
<div class="flex items-center gap-1 mb-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-1 w-fit">
    <a href="{{ route('admin.packages.index') }}"
        class="px-4 py-2 text-sm font-medium rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition">
        Active
    </a>
    <a href="{{ route('admin.packages.archived') }}"
        class="px-4 py-2 text-sm font-medium rounded-lg bg-sky-500 text-white transition">
        Archived
    </a>
</div>

<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Archived Packages</h2>
        <p class="text-xs text-gray-500 mt-0.5">These packages are hidden. Restore to make them active again.</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Package</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destination</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bookings</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Archived On</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($packages as $package)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition opacity-75">
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-500 dark:text-gray-400 line-through">{{ $package->package_name }}</p>
                        @if($package->package_type)<p class="text-xs text-gray-400 mt-0.5">{{ $package->package_type }}</p>@endif
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $package->destination->city_name }}, {{ $package->destination->country }}</td>
                    <td class="px-6 py-4 text-gray-500">${{ number_format($package->package_cost, 2) }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $package->bookings_count }}</td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $package->deleted_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.packages.restore', $package->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('Restore this package?');">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs text-green-600 dark:text-green-400 hover:underline font-medium">Restore</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">No archived packages.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($packages->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">{{ $packages->links() }}</div>
    @endif
</div>
@endsection
