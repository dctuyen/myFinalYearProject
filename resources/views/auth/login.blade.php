@extends('layouts.nonemenu')
@vite(['public/css/auth.css', 'public/js/auth.js'])

@section('content')
    <div class="container">
        <div class="row big-div form-login">
            <div class="col-md-6 col-xs-12" style="padding: 0">
                <div class="div-background"></div>
            </div>
            <div class="col-md-6 col-xs-12" style="background-color: white; padding: 10% 5%">
                <form method="POST" action="{{ route('login') }}">
                    <h3 class="mb-5" style="text-align: center; color: #3c509e; font-weight: bold;">
                        ĐĂNG NHẬP HỆ THỐNG</h3>
                    @csrf
                    <div class="row">
                        <label for="email" class="col-md-3 col-form-label text-md-end fw-bold">
                            {{ __('Tài khoản') }}
                        </label>

                        <div class="col-md-8">
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror" name="email"
                                   value="{{ old('email') }}" required autocomplete="email" autofocus
                                   oninvalid="this.setCustomValidity('Vui lòng nhập tài khoản')"
                                   oninput="this.setCustomValidity('')">

                            @error('email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-4 mb-2">
                        <label for="password"
                               class="col-md-3 col-form-label text-md-end fw-bold">{{ __('Mật khẩu') }}</label>

                        <div class="col-md-8">
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror" name="password"
                                   required autocomplete="current-password"
                                   oninvalid="this.setCustomValidity('Vui lòng nhập mật khẩu')"
                                   oninput="this.setCustomValidity('')">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 offset-md-3">
                            <div class="form-check">
                                <input class="form-check-input mt-2" type="checkbox" name="remember"
                                       id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Nhớ mật khẩu') }}
                                </label>
                                @if (Route::has('forgotpassword'))
                                    <a class="btn btn-link" href="{{ route('forgotpassword') }}"
                                       style="text-decoration: none; font-weight: bold">
                                        {{ __('Bạn quên mật khẩu ?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-4 offset-md-3">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Đăng nhập') }}
                            </button>
                        </div>
                        <div class="col-5">
                            <a href="{{ route('register') }}" class="btn btn-danger">Đăng ký</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
