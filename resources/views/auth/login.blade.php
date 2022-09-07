@extends('layouts.auth')
<title>Cashbook | Login</title>
@section('content')
{{-- <div class="header py-7 py-lg-8 pt-lg-9"> --}}
    {{-- <div class="container">
        <div class="header-body text-center mb-7">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-6 col-md-8 px-5">
                    <img src="{{ asset('assets/img/logo-2-1.png') }}">
                    <h1 class="text-white">Welcome!</h1>
                    <p class="text-lead text-white">Enter your email and password to login your account.</p>
                </div>
            </div>
        </div>
    </div> --}}
    {{-- <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
            <polygon class="fill-white" points="2560 0 2560 100 0 100"></polygon>
        </svg>
    </div> --}}
    {{-- </div> --}}
    {{-- <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8"> --}}
            <div class="login__container">
                <div id="login-form">
                    @include('flash::message')
                    <form method="POST" action="{{ route('login') }}" autocomplete="nope">
                        <h1>Cashbook</h1>
                        @csrf
                        <div class="input-group input-group-merge input-group-alternative input-group-login mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                            </div>
                            <input id="email" type="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror" name="email"  value="{{ old('email') }}" required autocomplete="false" autofocus>
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="input-group input-group-merge input-group-alternative input-group-login">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                            </div>
                            <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required   autocomplete="false">
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <button type="submit" class="btn btn-primary btn--login mt-0">Login</button>
                        {{-- <div class="form-group mt-4 mb-0">
                            <div class="alert alert-info">
                                Admin Email : admin@email.com , Password: secret
                            </div>
                            <div class="alert alert-info">
                                User Email : user@email.com , Password: secret
                                </div>
                        </div> --}}
                    </form>
                </div>
            </div>
            {{-- </div>
        </div>
    </div> --}}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('input[type="password"]').val('');
    $("form").attr('autocomplete', 'off'); // Switching form autocomplete attribute to off
    $("input").attr("autocomplete", "new-password");
});
</script>
@endsection

