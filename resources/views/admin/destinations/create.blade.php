@extends('layouts.admin')
@section('title', 'Add Destination')
@section('content')
<div class="max-w-2xl mx-auto">
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Add New Destination</h2>
    </div>
    <form action="{{ route('admin.destinations.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
        @csrf
        @php $input = 'w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent transition text-sm'; $label = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5'; @endphp

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="{{ $label }}">City Name</label>
                <input type="text" name="city_name" value="{{ old('city_name') }}" required class="{{ $input }}">
                @error('city_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="{{ $label }}">Country</label>
                <input type="text" name="country" value="{{ old('country') }}" required class="{{ $input }}">
                @error('country')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="{{ $label }}">Description</label>
            <textarea name="description" rows="3" class="{{ $input }}">{{ old('description') }}</textarea>
            @error('description')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="{{ $label }}">Destination Image</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl hover:border-sky-400 dark:hover:border-sky-500 transition cursor-pointer"
                onclick="document.getElementById('image-input').click()">
                <div class="space-y-2 text-center" id="upload-placeholder">
                    <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <span class="text-sky-600 dark:text-sky-400 font-medium">Click to upload</span> or drag and drop
                    </p>
                    <p class="text-xs text-gray-400">JPG, JPEG, PNG, WEBP — max 2MB</p>
                </div>
                <img id="image-preview" src="" alt="Preview" class="hidden max-h-40 rounded-lg mx-auto">
            </div>
            <input type="file" id="image-input" name="image" accept=".jpg,.jpeg,.png,.webp" class="hidden"
                onchange="previewImage(this)">
            @error('image')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-200 dark:border-gray-800">
            <a href="{{ route('admin.destinations.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-700 transition">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold rounded-lg transition">Create Destination</button>
        </div>
    </form>
</div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const placeholder = document.getElementById('upload-placeholder');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
