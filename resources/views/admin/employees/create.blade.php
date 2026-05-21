@extends('layouts.admin')
@section('title', 'Create Employee')
@section('content')
<div class="max-w-3xl mx-auto">
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Create New Employee</h2>
        <p class="text-xs text-gray-500 mt-0.5">Fill in personal information first, then set up the account credentials</p>
    </div>

    <form action="{{ route('admin.employees.store') }}" method="POST" class="p-6 space-y-6">
        @csrf
        @php
            $input = 'w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm';
            $label = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5';
        @endphp

        {{-- ── SECTION 1: Personal Information ── --}}
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-7 h-7 rounded-full bg-sky-500 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">1</div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Personal Information</h3>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="{{ $label }}">First Name</label>
                    <input type="text" name="emp_fn" value="{{ old('emp_fn') }}" required class="{{ $input }}">
                    @error('emp_fn')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $label }}">Middle Name <span class="text-gray-400">(opt.)</span></label>
                    <input type="text" name="emp_mn" value="{{ old('emp_mn') }}" class="{{ $input }}">
                </div>
                <div>
                    <label class="{{ $label }}">Last Name</label>
                    <input type="text" name="emp_ln" value="{{ old('emp_ln') }}" required class="{{ $input }}">
                    @error('emp_ln')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="{{ $label }}">Gender</label>
                    <select name="emp_gender" required class="{{ $input }}">
                        <option value="">Select gender</option>
                        @foreach(['male'=>'Male','female'=>'Female','other'=>'Other'] as $val=>$lbl)
                            <option value="{{ $val }}" {{ old('emp_gender') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                    @error('emp_gender')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $label }}">Date of Birth</label>
                    <input type="date" name="emp_birthdate" value="{{ old('emp_birthdate') }}" required class="{{ $input }}">
                    @error('emp_birthdate')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="{{ $label }}">Home Address</label>
                <textarea name="emp_address" rows="2" required class="{{ $input }}">{{ old('emp_address') }}</textarea>
                @error('emp_address')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- ── SECTION 2: Employment Details ── --}}
        <div class="pt-5 border-t border-gray-200 dark:border-gray-800">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-7 h-7 rounded-full bg-sky-500 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">2</div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Employment Details</h3>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="{{ $label }}">Work Email</label>
                    <input type="email" name="emp_email" value="{{ old('emp_email') }}" required class="{{ $input }}">
                    @error('emp_email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $label }}">Contact Number</label>
                    <input type="text" name="emp_contact_num" value="{{ old('emp_contact_num') }}" required class="{{ $input }}">
                    @error('emp_contact_num')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="{{ $label }}">Hire Date</label>
                    <input type="date" name="emp_hiredate" value="{{ old('emp_hiredate') }}" required class="{{ $input }}">
                    @error('emp_hiredate')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $label }}">Commission Rate (%)</label>
                    <input type="number" name="commission_rate" step="0.01" min="0" max="100" value="{{ old('commission_rate', '0') }}" class="{{ $input }}" placeholder="e.g. 5 for 5%">
                    @error('commission_rate')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- ── SECTION 3: Account Credentials ── --}}
        <div class="pt-5 border-t border-gray-200 dark:border-gray-800">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-7 h-7 rounded-full bg-sky-500 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">3</div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Account Credentials</h3>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="{{ $label }}">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required autocomplete="off" class="{{ $input }}">
                    @error('username')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $label }}">Role</label>
                    <select name="role" required class="{{ $input }}">
                        <option value="">Select role</option>
                        <option value="agent" {{ old('role') === 'agent' ? 'selected' : '' }}>Agent</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="{{ $label }}">Password</label>
                    <input type="password" name="password" required autocomplete="new-password" class="{{ $input }}">
                    @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $label }}">Confirm Password</label>
                    <input type="password" name="password_confirmation" required autocomplete="new-password" class="{{ $input }}">
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
            <a href="{{ route('admin.employees.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-700 transition">Cancel</a>
            <button type="submit" class="px-5 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">Create Employee</button>
        </div>
    </form>
</div>
</div>
@endsection
