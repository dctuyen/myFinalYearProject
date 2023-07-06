@extends('layouts.app')
@vite(['public/css/admin/home.css'])
@section('title', 'Học viên')


@section('content')
    <div class="card">
        <h4 class="card-header">Danh Sách Lớp Học Của Tôi</h4>
        <div class="row mx-4 mb-4">
            <div class="col-md-2"></div>
            <p class="col-md-8"></p>
            <p class="col-md-1 text-nowrap fw-bold info" style="padding-left: 5rem !important;">
                @if ($classes->total() > 0)
                    Tổng
                    {{ count($classes->items()) }} / {{ $classes->total() }}
                    lớp học
                @endif
            </p>
        </div>
        <div class="table-responsive text-nowrap mx-4">
            <table class="table" id="listAccount">
                <thead>
                <tr class="text-nowrap">
                    <th>STT</th>
                    <th>Tên Lớp</th>
                    <th>Giảng viên</th>
                    <th>Ngày bắt đầu</th>
                    <th>Thời gian</th>
                    <th>Ca học</th>
                    <th>Thao tác</th>
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
                            <a href="{{ route('viewclass', $class->id) }}" title="Thông tin lớp học">
                                {{ $class->name }} <br>
                                Thuộc khóa: <i>{{ $class->course_name }}</i>
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.viewaccount', $class->teacher_id) }}" title="Thông tin giảng viên">
                            {{ $class->teacher }} <br>
                            {{ $class->teacher_email }}
                            </a>
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
                            <a href="{{ $class->classroom_online }}" class="btn btn-warning" target="_blank">
                                Vào lớp
                                <i class="fas fa-sign-in"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="demo-inline-spacing row mt-3">
            {{ $classes->links('layouts.pagination') }}
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function () {


        })
    </script>
@endsection
