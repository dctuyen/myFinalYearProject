@extends('layouts.nonemenu')
@vite(['public/css/auth.css', 'public/js/auth.js'])

@section('content')
    <div class="container">
        <div class="row big-div form-reset-password" style="">
            <div class="col-md-6 col-xs-12" style="padding: 0">
                <div class="div-background"></div>
            </div>
            <div class="col-md-6 col-sm-12" style="background-color: white; padding: 4% 4% 5% 4%">
                <h2 class="mb-3" style="text-align: center; color: #3c509e; font-weight: bold;">RESET MẬT KHẨU</h2>
                <form method="POST" action="{{ route('submitpassword') }}">
                    @csrf
                    <div class="row input-group" style="margin-left: 0">
                        <label for="email" class="col-form-label fw-bolder">
                            {{ __('Email') }}
                        </label>

                        <input id="email" name="email" type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ $user->email }}" required autocomplete="email" autofocus
                               disabled
                        >

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row input-group" style="margin-left: 0">
                        <div class="col-md-12 mt-3 ps-0">
                            <label for="password" class="col-form-label fw-bolder"
                                   style="padding-left: 0">{{ __('Mật khẩu') }}</label>
                            <input id="password" type="password" required autocomplete="password"
                                   class="form-control @error('password') is-invalid @enderror" name="password"
                                   oninvalid="this.setCustomValidity('Vui lòng nhập mật khẩu')"
                                   oninput="this.setCustomValidity('')">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-12 mt-3 ps-0">
                            <label for="confirmed"
                                   class="col-form-label fw-bolder">{{ __('Xác nhận') }}</label>
                            <input id="confirmed" type="password"
                                   class="form-control" name="confirmed"
                                   required autocomplete="current-password"
                                   oninvalid="this.setCustomValidity('Vui lòng xác nhận mật khẩu')"
                                   oninput="this.setCustomValidity('')">
                        </div>
                    </div>
                    <input id="userId" name="userId" value="{{ $user->id }}" hidden readonly>
                    <div class="row my-5">
                        <div class="col-md-4 offset-md-3">
                            <button id="submitButton" type="submit" class="btn btn-lg btn-danger">
                                {{ __('Xác nhận') }}
                            </button>
                        </div>

                        <div class="col-md-4">
                            <a class="ps-4 btn btn-lg btn-warning" href="{{ route('login') }}">
                                Hủy <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
