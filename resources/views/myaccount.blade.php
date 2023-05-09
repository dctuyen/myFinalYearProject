@extends('layouts.app')
@vite(['public/css/auth.css', 'public/js/auth.js'])

@section('content')
    <div class="card p-3">
        <form method="POST" action="{{ route('editaccount') }}" enctype="multipart/form-data">
            @csrf
            <div class="row input-group" style="margin-left: 0">
                <div class="col-md-4">
                    <label for="firstname" class="col-form-label fw-bolder"
                           style="padding-left: 0">{{ __('Họ') }}</label>
                    <input id="firstname" type="text" required autocomplete="firstname"
                           class="form-control @error('firstname') is-invalid @enderror" name="firstname"
                           value="{{ $userEdit->first_name }}" oninvalid="this.setCustomValidity('Vui lòng nhập Họ')"
                           oninput="this.setCustomValidity('')">
                </div>

                <div class="col-md-4">
                    <label for="lastname" class="col-form-label fw-bolder">{{ __('Tên') }}</label>
                    <input id="lastname" type="text" required autocomplete="lastname"
                           class="form-control @error('lastname') is-invalid @enderror" name="lastname"
                           value="{{  $userEdit->last_name }}" oninvalid="this.setCustomValidity('Vui lòng nhập Tên')"
                           oninput="this.setCustomValidity('')">
                </div>

                <div class="col-md-4">
                    <label for="sex" class="col-form-label fw-bolder">
                        {{ __('Giới tính') }}
                    </label>
                    <select class="form-select form-select-md" aria-label=".form-select-lg example"
                            id="sex" name="sex">
                        <option selected disabled>Chọn giới tính</option>
                        <option @if ($userEdit->sex === 'male') selected @endif value="male">Nam</option>
                        <option @if ($userEdit->sex === 'female') selected @endif value="female">Nữ</option>
                    </select>
                </div>
            </div>

            <div class="row input-group" style="margin-left: 0">
                <div class="col-md-4">
                    <label for="birthday" class="col-form-label fw-bolder" style="padding-left: 0">
                        {{ __('Ngày sinh') }}
                    </label>
                    <input id="birthday" type="date" required autocomplete="birthday"
                           value="{{ $userEdit->birthday }}" class="form-control" name="birthday">
                </div>


                <div class="col-md-8">
                    <label for="address" class="col-form-label fw-bolder" style="padding-left: 0">
                        {{ __('Địa chỉ') }}
                    </label>
                    <input id="address" type="text" required autocomplete="address"
                           value="{{ $userEdit->address }}" class="form-control" name="address">
                </div>
            </div>

            <table style="width: 100%" class="mt-4">
                <tr>
                    <td style="width:1px; white-space: nowrap;">Thông tin hệ thống</td>
                    <td>
                        <hr/>
                    </td>
                    <td>
                        <hr/>
                    </td>
                </tr>
            </table>
            <div class="row input-group" style="margin-left: 0">
                <div class="col-md-6">
                    <label for="email" class="col-form-label fw-bolder">
                        {{ __('Email') }}
                    </label>

                    <input id="email" name="email" type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ $userEdit->email }}" required autocomplete="email" autofocus
                           placeholder="Email đăng nhập hệ thống"
                           oninvalid="this.setCustomValidity('Vui lòng nhập email')"
                           oninput="this.setCustomValidity('')" readonly>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="phone" class="col-form-label fw-bolder">
                        {{ __('Số điện thoại') }}
                    </label>

                    <input id="phone" name="phone" type="text"
                           class="form-control @error('phone') is-invalid @enderror"
                           value="{{ $userEdit->phone }}" required autocomplete="phone" autofocus
                           oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại')"
                           oninput="this.setCustomValidity('')">

                    @error('phone')
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                </div>
            </div>

            <div class="row input-group" style="margin-left: 0">
                <div class="col-md-6">
                    <div class="col-md-12 mt-3">
                        <label for="password" class="col-form-label fw-bolder"
                               style="padding-left: 0">{{ __('Mật khẩu') }}</label>
                        <input id="password" type="password" autocomplete="password"
                               class="form-control @error('password') is-invalid @enderror" name="password"
                               oninvalid="this.setCustomValidity('Vui lòng nhập mật khẩu')"
                               oninput="this.setCustomValidity('')">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-12 mt-3">
                        <label for="confirmed"
                               class="col-form-label fw-bolder">{{ __('Xác nhận') }}</label>
                        <input id="confirmed" type="password"
                               class="form-control" name="confirmed"
                               autocomplete="current-password"
                               oninvalid="this.setCustomValidity('Vui lòng xác nhận mật khẩu')"
                               oninput="this.setCustomValidity('')">
                    </div>
                </div>
                <div class="col-md-3 pt-4 div-background-url">
                    <label for="backgroundUrl" class="col-form-label fw-bolder">
                        <p class="btn btn-danger vertical-text col-md-3">Chọn Avatar</p>
                        <div style="height: 160px;" class="col-md-9">
                            <img
                                src="{{ asset($userEdit->background_url) }}"
                                alt="" id="avatarView" style="width: 100%; height: 100%">
                        </div>
                    </label>
                </div>
            </div>
            <!--Hidden block-->
            <input type="file" accept="image/*" id="backgroundUrl" name="backgroundUrl"
                   value="{{ $userEdit->background_url }}"
                   hidden>
            <input type="text" name="userId" id="userId" value="{{ $userEdit->id }}" hidden>
            <!--End of hidden block-->

            <div class="row ms-0">
                <div class="mt-2 col-md-6">
                    <button type="submit" class="btn btn-primary me-2">Lưu</button>
                    <button type="reset" class="btn btn-danger">Hủy</button>
                </div>
            </div>
        </form>

    </div>
@endsection

