@extends('layouts.app')
@vite(['public/css/auth.css', 'public/js/auth.js'])
@section('title', 'Khóa học')

@section('content')

    <div class="mb-4">
        <div class="card">
            <div class="card-header text-warning fw-bold">{{ $course->course_type }} {{ $course->level }}</div>
            <div class="card-body">
                <h5 class="card-title text-danger">{{ $course->name }}</h5>
                <p class="card-text">Số buổi học: {{ $course->lesson_count }} || <span><i class="fas fa-heart"></i> {{ $course->like_count }} </span>
                </p>
                <p class="card-text">
                    Thời gian: {{ $course->duration }} tháng
                </p>
                <p class="card-text">
                    {{ $course->description }}
                </p>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="table-responsive text-nowrap m-4">
            <table class="table" id="listAccount">
                <thead>
                <tr class="text-nowrap">
                    <th>STT</th>
                    <th>Tên Lớp</th>
                    <th>Giảng viên</th>
                    <th>Thời Gian</th>
                    <th>Ca học</th>
                    <th>Số lượng học viên</th>
                    <th>Thao Tác</th>
                </tr>
                </thead>
                <tbody>
                @if($classes->total() == 0)
                    <tr>
                        <td colspan="8">
                            <div class="divider divider-success">
                                <div class="divider-text text-lg">Không có dữ liệu</div>
                            </div>
                        </td>
                    </tr>
                @endif
                @foreach($classes as $key => $class)
                    <tr data-key="{{ $key }}">
                        <td>{{ $key + 1 }}</td>
                        <td>
                            {{ $class->name }} <br>
                            Thuộc khóa: <i>{{ $course->name }}</i>
                        </td>
                        <td>{{ $class->teacher }} <br>
                            {{ $class->teacher_email }}
                        </td>
                        <td>
                            Từ {{ $class->start_date }} <br>
                            Đến {{ $class->end_date }}
                        </td>
                        <td>
                            Phòng: {{ $class->classroom }} <br>
                            {{ $class->dates }} <br>
                            Từ {{ $class->start_at }} đến {{ $class->end_at }}
                        </td>
                        <td>
                            Sĩ số: {{ $class->number_registered }} học viên <br>
                            <span
                                class="text-danger fw-bold"> Giới hạn: {{ $class->number_of_students }} học viên </span>
                        </td>
                        <td>
                            <button class="btn btn-danger" data-togle="tooltip" title="Đăng ký học"
                                    onclick="loadDeleteModal({{ $key }}, {{ $class->id }})">
                                Đăng ký học
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
