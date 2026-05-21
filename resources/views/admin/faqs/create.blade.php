@extends('layouts.admin')
@section('title', 'Create FAQ')
@section('content')
<div class="max-w-2xl mx-auto">
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Create FAQ</h2>
    </div>
    <form action="{{ route('admin.faqs.store') }}" method="POST" class="p-6 space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Question</label>
            <textarea name="question" rows="3" required
                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm">{{ old('question') }}</textarea>
            @error('question')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Answer</label>
            <textarea name="answer" rows="5" required
                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm">{{ old('answer') }}</textarea>
            @error('answer')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Category</label>
                <input type="text" name="category" value="{{ old('category') }}" placeholder="e.g., Booking, Payment"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Display Order</label>
                <input type="number" name="display_order" value="{{ old('display_order', 0) }}" min="0"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm">
            </div>
        </div>
        <div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-sky-500 focus:ring-sky-500">
                <span class="text-sm text-gray-700 dark:text-gray-300">Publish this FAQ</span>
            </label>
        </div>
        <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-200 dark:border-gray-800">
            <a href="{{ route('admin.faqs.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-700 transition">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">Create FAQ</button>
        </div>
    </form>
</div>
</div>
@endsection
