@extends('layouts.admin')

@section('title', 'Users')

@section('content')

{{-- Tabs --}}
<div class="flex items-center gap-1 mb-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-1 w-fit">
    <a href="{{ route('admin.users.index') }}"
        class="px-4 py-2 text-sm font-medium rounded-lg bg-sky-500 text-white transition">
        Active
    </a>
    <a href="{{ route('admin.users.archived') }}"
        class="px-4 py-2 text-sm font-medium rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition">
        Archived
    </a>
</div>

<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">All Users</h2>
            <p class="text-xs text-gray-500 mt-0.5">Manage admin, agent, and customer accounts</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
            class="flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New User
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-sky-100 dark:bg-sky-500/20 border border-sky-200 dark:border-sky-500/30 flex items-center justify-center flex-shrink-0">
                                <span class="text-sky-600 dark:text-sky-400 text-xs font-bold">{{ strtoupper(substr($user->username, 0, 2)) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $user->username }}</p>
                                <p class="text-xs text-gray-500">
                                    @if($user->employee) {{ $user->employee->emp_fn }} {{ $user->employee->emp_ln }}
                                    @elseif($user->customer) {{ $user->customer->cus_fn }} {{ $user->customer->cus_ln }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $roleColors = [
                                'admin'    => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-500/15 dark:text-red-400 dark:border-red-500/20',
                                'agent'    => 'bg-sky-100 text-sky-700 border-sky-200 dark:bg-sky-500/15 dark:text-sky-400 dark:border-sky-500/20',
                                'customer' => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-500/15 dark:text-green-400 dark:border-green-500/20',
                            ];
                            $rc = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                        @endphp
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full border {{ $rc }}">{{ ucfirst($user->role) }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                        @if($user->employee) {{ $user->employee->emp_email }}
                        @elseif($user->customer) {{ $user->customer->cus_email }}
                        @else — @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">
                        {{ $user->last_login ? $user->last_login->format('M d, Y H:i') : 'Never' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.users.show', $user->id) }}"
                                class="text-xs text-sky-600 dark:text-sky-400 hover:text-sky-500 dark:hover:text-sky-300 transition">View</a>
                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                class="text-xs text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">Edit</a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.archive', $user->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('Archive this user? They will no longer be able to log in. You can restore them later.')">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs text-amber-600 dark:text-amber-400 hover:text-amber-500 transition">Archive</button>
                            </form>
                            @else
                            <span class="text-xs text-gray-300 dark:text-gray-600 cursor-not-allowed" title="Cannot archive your own account">Archive</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 dark:text-gray-600">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
        {{ $users->links() }}
    </div>
</div>
@endsection
