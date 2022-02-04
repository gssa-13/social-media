@extends('layouts.app')

@section('content')
    <div class="container">
        @foreach($friendshipRequests as $friendshipRequest)
            <accept-friendship-btn
               :sender="{{ $friendshipRequest->sender }}"
               friendship-status="{{ $friendshipRequest->status }}"
               :key="{{ $friendshipRequest->id  }}"
            ></accept-friendship-btn>
        @endforeach
    </div>
@endsection
