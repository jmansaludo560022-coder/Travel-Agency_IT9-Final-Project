@extends('layouts.admin')

@section('title', 'Edit Employee')

@section('content')
<div class="max-w-3xl mx-auto">
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Edit Employee</h2>
    </div>

    <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" class="p-6 space-y-6">
        @csrf
        @method('PUT')
        @php
            $input = 'w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm';
            $label = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5';
        @endphp

        {{-- Account Info --}}
        <div>
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Account</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="{{ $label }}">Username</label>
                    <input type="text" name="username" value="{{ old('username', $employee->userAccount->username) }}"
                           class="{{ $input }} @error('username') border-red-500 @enderror">
                    @error('username')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $label }}">Role</label>
                    <select name="role" class="{{ $input }} @error('role') border-red-500 @enderror">
                        <option value="admin" {{ old('role', $employee->userAccount->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="agent" {{ old('role', $employee->userAccount->role) === 'agent' ? 'selected' : '' }}>Agent</option>
                    </select>
                    @error('role')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $label }}">New Password <span class="text-gray-400">(leave blank to keep)</span></label>
                    <input type="password" name="password"
                           class="{{ $input }} @error('password') border-red-500 @enderror">
                    @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $label }}">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="{{ $input }}">
                </div>
            </div>
        </div>

        {{-- Personal Info --}}
        <div class="pt-5 border-t border-gray-200 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Personal Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="{{ $label }}">First Name</label>
                    <input type="text" name="emp_fn" value="{{ old('emp_fn', $employee->emp_fn) }}"
                           class="{{ $input }} @error('emp_fn') border-red-500 @enderror">
                    @error('emp_fn')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $label }}">Middle Name <span class="text-gray-400">(optional)</span></label>
                    <input type="text" name="emp_mn" value="{{ old('emp_mn', $employee->emp_mn) }}" class="{{ $input }}">
                </div>
                <div>
                    <label class="{{ $label }}">Last Name</label>
                    <input type="text" name="emp_ln" value="{{ old('emp_ln', $employee->emp_ln) }}"
                           class="{{ $input }} @error('emp_ln') border-red-500 @enderror">
                    @error('emp_ln')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $label }}">Gender</label>
                    <select name="emp_gender" class="{{ $input }} @error('emp_gender') border-red-500 @enderror">
                        <option value="male" {{ old('emp_gender', $employee->emp_gender) === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('emp_gender', $employee->emp_gender) === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('emp_gender', $employee->emp_gender) === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('emp_gender')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $label }}">Birthdate</label>
                    <input type="date" name="emp_birthdate" value="{{ old('emp_birthdate', $employee->emp_birthdate->format('Y-m-d')) }}"
                           class="{{ $input }} @error('emp_birthdate') border-red-500 @enderror">
                    @error('emp_birthdate')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $label }}">Hire Date</label>
                    <input type="date" name="emp_hiredate" value="{{ old('emp_hiredate', $employee->emp_hiredate->format('Y-m-d')) }}"
                           class="{{ $input }} @error('emp_hiredate') border-red-500 @enderror">
                    @error('emp_hiredate')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="pt-5 border-t border-gray-200 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Contact</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="{{ $label }}">Email</label>
                    <input type="email" name="emp_email" value="{{ old('emp_email', $employee->emp_email) }}"
                           class="{{ $input }} @error('emp_email') border-red-500 @enderror">
                    @error('emp_email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $label }}">Contact Number</label>
                    <input type="text" name="emp_contact_num" value="{{ old('emp_contact_num', $employee->emp_contact_num) }}"
                           class="{{ $input }} @error('emp_contact_num') border-red-500 @enderror">
                    @error('emp_contact_num')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="{{ $label }}">Address</label>
                    <textarea name="emp_address" rows="2"
                              class="{{ $input }} @error('emp_address') border-red-500 @enderror">{{ old('emp_address', $employee->emp_address) }}</textarea>
                    @error('emp_address')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $label }}">Commission Rate (%)</label>
                    <input type="number" name="commission_rate"
                           value="{{ old('commission_rate', number_format($employee->commission_rate * 100, 2)) }}"
                           step="0.01" min="0" max="100"
                           class="{{ $input }} @error('commission_rate') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Enter as percentage (0–100).</p>
                    @error('commission_rate')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
            <a href="{{ route('admin.employees.show', $employee->id) }}" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-700 transition">Cancel</a>
            <button type="submit" class="px-5 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">Save Changes</button>
        </div>
    </form>
</div>
</div>
@endsection
