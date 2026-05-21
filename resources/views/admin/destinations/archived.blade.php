@extends('layouts.admin')
@section('title', 'Archived Destinations')
@section('content')

{{-- Tabs --}}
<div class="flex items-center gap-1 mb-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-1 w-fit">
    <a href="{{ route('admin.destinations.index') }}"
        class="px-4 py-2 text-sm font-medium rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition">
        Active
    </a>
    <a href="{{ route('admin.destinations.archived') }}"
        class="px-4 py-2 text-sm font-medium rounded-lg bg-sky-500 text-white transition">
        Archived
        <span class="ml-1 px-1.5 py-0.5 text-xs bg-white/20 rounded-full">{{ $destinations->total() }}</span>
    </a>
</div>

<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Archived Destinations</h2>
        <p class="text-xs text-gray-500 mt-0.5">Restore a destination to make it available for packages again.</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Country</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Packages</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Archived On</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($destinations as $destination)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition opacity-70">
                    <td class="px-6 py-4 font-medium text-gray-500 dark:text-gray-400 line-through">{{ $destination->city_name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $destination->country }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $destination->travel_packages_count }}</td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $destination->deleted_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.destinations.restore', $destination->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('Restore this destination?');">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs text-green-600 dark:text-green-400 hover:underline font-medium">Restore</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400">No archived destinations.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($destinations->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">{{ $destinations->links() }}</div>
    @endif
</div>
@endsection
