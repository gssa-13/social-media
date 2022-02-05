@extends('layouts.app')

@section('content')
    <div class="container">
        @forelse($friends as $friend)
            <p>{{ $friend->name }}</p>
        @empty
            You don't have friends yet
        @endforelse
    </div>
@endsection
