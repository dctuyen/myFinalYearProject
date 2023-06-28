@extends('layouts.app')
@vite(['public/css/auth.css', 'public/js/auth.js'])
@section('title', 'Khóa học')

@section('content')
    <div class="row">
        @foreach($listCourse as $course)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card" style="height: 100%">
                    <div class="card-header text-warning fw-bold">{{ $course->course_type }} {{ $course->level }}</div>
                    <div class="card-body">
                        <h5 class="card-title text-danger">{{ $course->name }}</h5>
                        <p class="card-text">
                           {{ $course->description }}
                        </p>
                        <div style="display: flex; justify-content: space-between;">
                            <a href="{{ route('listclass') }}?course={{ $course->id }} " class="btn btn-primary col-md-5">Đăng ký học</a>
                            <span class="text-warning col-md-5 fw-bold mt-2" style="text-align: center;">Giá: {{ $course->price }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

