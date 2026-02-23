@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Enter Verification Code') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{route('webuser.post-verify-OTP')}}">
                        @csrf

                        <div class="row mb-3">
                            <label for="verify_code" class="col-md-4 col-form-label text-md-end">{{ __('OTP Code') }}</label>

                            <div class="col-md-6">
                                <input id="verify_code" type="number" class="form-control @error('verify_code') is-invalid @enderror"
                                    name="verify_code" value="{{ old('verify_code') }}" required autocomplete="Code" autofocus>

                                @error('verify_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        @php
                        $getUserEmail = Crypt::encrypt($userEmail);
                        @endphp

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Verify OTP') }}
                                </button>
                                <label class="mt-2 text-center d-block pb-2" for="login">
                                    <a href="{{route('webuser.resend-OTP',['user_email' => $getUserEmail])}}">Resend OTP</a>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection