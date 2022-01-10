@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                @include('partials.validation-errors')
                <div class="card border-0 bg-light px-4 py-2">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <input class="form-control border-0" type="name" name="name" id="name" placeholder="User Name">
                            </div>
                            <div class="form-group">
                                <input class="form-control border-0" type="first_name" name="first_name" id="first_name" placeholder="First Name">
                            </div>
                            <div class="form-group">
                                <input class="form-control border-0" type="last_name" name="last_name" id="last_name" placeholder="Last Name">
                            </div>
                            <div class="form-group">
                                <input class="form-control border-0" type="email" name="email" id="email" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <input class="form-control border-0" type="password" name="password" id="password" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <input class="form-control border-0" type="password" name="password_confirmation" id="password_confirmation" placeholder="Repeat Password">
                            </div>
                            <button class="btn btn-primary btn-block" dusk="register-btn">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
