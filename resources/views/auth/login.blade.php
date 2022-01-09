@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                @include('partials.validation-errors')
                <div class="card border-0 bg-light px-4 py-2">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <input class="form-control border-0" type="email" name="email" id="email" placeholder="email">
                            </div>
                            <div class="form-group">
                                <input class="form-control border-0" type="password" name="password" id="password" placeholder="Password">
                            </div>
                            <button class="btn btn-primary btn-block" dusk="login-btn">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
