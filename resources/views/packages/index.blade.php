@extends(
    auth()->check()
        ? (auth()->user()->role === 'admin' ? 'layouts.admin' : (auth()->user()->role === 'agent' ? 'layouts.agent' : 'layouts.customer'))
        : 'layouts.app'
)

@section('title', 'Browse Packages')

@section('content')
    @include('packages._index_content')
@endsection
