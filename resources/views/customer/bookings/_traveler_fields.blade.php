@php $i = $index; @endphp
<div class="grid grid-cols-1 md:grid-cols-3 gap-3">
    <div>
        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">First Name</label>
        <input type="text" name="travelers[{{ $i }}][trav_fn]" value="{{ old("travelers.$i.trav_fn") }}" required
            class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
        @error("travelers.$i.trav_fn")<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Middle Name <span class="text-gray-400">(opt.)</span></label>
        <input type="text" name="travelers[{{ $i }}][trav_mn]" value="{{ old("travelers.$i.trav_mn") }}"
            class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Last Name</label>
        <input type="text" name="travelers[{{ $i }}][trav_ln]" value="{{ old("travelers.$i.trav_ln") }}" required
            class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
        @error("travelers.$i.trav_ln")<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Date of Birth</label>
        <input type="date" name="travelers[{{ $i }}][trav_birthdate]" value="{{ old("travelers.$i.trav_birthdate") }}"
            required max="{{ now()->subDay()->toDateString() }}"
            class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
        @error("travelers.$i.trav_birthdate")<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Gender</label>
        <select name="travelers[{{ $i }}][gender]" required
            class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
            <option value="male" {{ old("travelers.$i.gender") === 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ old("travelers.$i.gender") === 'female' ? 'selected' : '' }}>Female</option>
            <option value="other" {{ old("travelers.$i.gender") === 'other' ? 'selected' : '' }}>Other</option>
        </select>
        @error("travelers.$i.gender")<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Nationality</label>
        <input type="text" name="travelers[{{ $i }}][nationality]" value="{{ old("travelers.$i.nationality") }}" required
            class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
        @error("travelers.$i.nationality")<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-3">
        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Passport No <span class="text-gray-400">(opt.)</span></label>
        <input type="text" name="travelers[{{ $i }}][passport_no]" value="{{ old("travelers.$i.passport_no") }}"
            class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition">
    </div>
</div>
