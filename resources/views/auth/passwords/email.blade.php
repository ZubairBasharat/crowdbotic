@extends('layouts.auth')

@section('content')
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8"> --}}
            <div class="card bg-secondary border-soft login-container">
                <div class="card-header bg-signin">
                    <h3 class="text-muted text-center my-2">{{ __('Reset Password') }}</h3>
                </div>

                <div class="card-body px-5 pt-5 pb-4 pt-2">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative input-group-login mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                </div>
                                <input id="email" type="email"  class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="E-mail Address">


                            </div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>




                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn--login">{{ __('Send Password Reset Link') }}</button>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-12 back-to-login">
                                <a  href="{{route('login')}}" class="text-back-to-login">
                                    Back to Login

                                </a>
                            </div>
                        </div>

                    </form>

                </div>

            </div>
        {{-- </div>
    </div>
</div> --}}
@endsection
