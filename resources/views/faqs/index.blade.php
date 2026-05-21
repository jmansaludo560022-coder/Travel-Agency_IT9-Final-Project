@php
    $role = auth()->check() ? auth()->user()->role : null;
    $layout = match($role) {
        'admin'    => 'layouts.admin',
        'agent'    => 'layouts.agent',
        'customer' => 'layouts.customer',
        default    => null,
    };
@endphp

@if($layout)
    @extends($layout)
    @section('title', 'FAQs')
    @section('content')
        @include('faqs._content')
    @endsection
@else
    {{-- Public / guest view --}}
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>FAQs — {{ config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 font-sans antialiased">
        <div class="max-w-4xl mx-auto py-12 px-4">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Frequently Asked Questions</h1>
                <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:underline">Login</a>
            </div>
            @include('faqs._content')
        </div>
    </body>
    </html>
@endif
