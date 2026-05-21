@extends(
    auth()->check()
        ? (auth()->user()->role === 'admin' ? 'layouts.admin' : (auth()->user()->role === 'agent' ? 'layouts.agent' : 'layouts.customer'))
        : 'layouts.app'
)

@section('title', $package->package_name)

@section('content')
    @include('packages._show_content')
@endsection
