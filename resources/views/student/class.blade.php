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
            <table class="table">
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
                            <a href="{{ route('viewclass', $class->id) }}" title="Thông tin lớp học">
                                <strong>{{ $class->name }}</strong><br>
                                Khóa: <i>{{ $course->name }}</i>
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.viewaccount', $class->teacher_id) }}" title="Thông tin giảng viên">
                                <strong>{{ $class->teacher }}</strong><br>
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
                            Thời gian: {{ $class->start_at }} - {{ $class->end_at }}
                        </td>
                        <td>
                            Sĩ số: {{ $class->number_registered }} học viên <br>
                            <span
                                class="text-danger fw-bold"> Giới hạn: {{ $class->number_of_students }} học viên </span>
                        </td>
                        <td>
                            @if ($class->status === 1)
                                <span class="badge bg-label-success me-1 text-capitalize">Hoạt động</span>
                            @elseif ($class->status === 2)
                                <span class="badge bg-label-warning me-1 text-capitalize">Chờ khai giảng</span>
                            @elseif ($class->status === 0)
                                <span class="badge bg-label-danger me-1 text-capitalize">Không hoạt động</span>
                            @endif
                            <br>
                            <br>
                            @if ($class->number_registered == $class->number_of_students)
                                <p class="btn btn-success" style="cursor: not-allowed;">Lớp đã đầy</p>
                            @else
                                <button class="btn btn-warning" data-togle="tooltip" title="Đăng ký học"
                                        onclick="loadJoinModal({{ $class->id }}, '{{ $class->name }}')">
                                    Đăng ký học
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="joinToClassroom" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="joinToClassroom" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Đăng ký học</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    Xác nhận đăng ký học lớp <span id="modal-account_name"></span>?
                    <input type="hidden" id="class_id" name="class_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-white" data-bs-dismiss="modal"
                    >Hủy
                    </button>
                    <button type="button" class="btn btn-danger" id="modal-confirm_join">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    <script type="text/javascript">
        $(document).ready(function () {


        })

        function loadJoinModal(id, classname) {
            $('#modal-class_name').html(classname);
            $('#modal-confirm_join').attr('onclick', `confirmJoin(${id})`);
            $('#joinToClassroom').modal('show');
        }

        function confirmJoin(id) {
            $.ajax({
                url: `{{ route('joinclass', '') }}`,
                type: 'POST',
                data: {
                    '_method': 'post',
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'id': id
                },
                dataType: 'json',
                success: function (data) {
                    $('#joinToClassroom').modal('hide');
                    if (data.status === 1) {
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function (error) {
                    // Error logic goes here..!
                }
            });
        }
    </script>
@endsection
