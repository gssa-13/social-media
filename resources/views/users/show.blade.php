@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card border-0 bg-light shadow-sm">
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}">
                    <div class="card-body">
                        <div class="card-title">{{ $user->name  }}</div>
                        <request-friendship-btn
                            dusk="request-friendship"
                            friendship-status="{{ $friendshipStatus }}"
                            :recipient="{{ $user }}"/>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card border-0 bg-light shadow-sm">
                    <div class="card-body">
                        <status-list url="{{ route('users.statuses.index', $user) }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
