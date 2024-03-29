@extends('layouts.app')
@vite(['public/css/auth.css', 'public/js/auth.js'])

@section('content')
<!-- Error -->
<div class="container-xxl container-p-y card">
    <div class="misc-wrapper" style="text-align: center">
        <h2 class="mb-2 mx-2">Page Not Found :(</h2>
        <p class="mb-4 mx-2">Oops! 😖 The requested URL was not found on this server.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Back to home</a>
        <div class="mt-3">
            <img
                src="{{ asset('theme/assets/img/illustrations/page-misc-error-light.png') }}"
                alt="page-misc-error-light"
                width="500"
                class="img-fluid"
                data-app-dark-img="{{ asset('theme/illustrations/page-misc-error-dark.png') }}"
                data-app-light-img="{{ asset('theme/illustrations/page-misc-error-light.png') }}"
            />
        </div>
    </div>
</div>
<!-- /Error -->
@endsection
