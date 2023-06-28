@extends('layouts.app')
@vite(['public/css/admin/home.css'])
@section('title', 'Học viên')


@section('content')
    <div class="card">
        <h4 class="card-header">Danh Sách Lớp</h4>
        <div class="row mx-4 mb-4">
            <a href="{{ route('newclass') }}" class="btn btn-info rounded-pill col-md-2">
                <i class="fas fa-plus-circle"></i> Thêm mới</a>
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
                    <th>Người Chăm Sóc</th>
                    <th>Thời Gian</th>
                    <th>Ca học</th>
                    <th>Số lượng học viên</th>
                    <th>Người Tạo</th>
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
                            Thuộc khóa: <i>{{ $class->course_name }}</i>
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
                            @if ($class->status === 1)
                                <span class="badge bg-label-success me-1 text-capitalize">Hoạt động</span>
                            @elseif ($class->status === 2)
                                <span class="badge bg-label-warning me-1 text-capitalize">Chờ kích hoạt</span>
                            @elseif ($class->status === 0)
                                <span class="badge bg-label-danger me-1 text-capitalize">Không hoạt động</span>
                            @endif
                            <br>{{ $class->created_at }}
                        </td>
                        <td>
                            <a class="btn btn-warning rounded-pill btn-icon me-2" data-togle="tooltip" title="Chỉnh sửa"
                               href="{{ route('editclass', $class->id) }}">
                                <i class="fal fa-edit"></i></a>
                            @if ($class->number_registered == 0)
                                <button class="btn btn-danger rounded-pill btn-icon" data-togle="tooltip" title="Xóa"
                                        onclick="loadDeleteModal({{ $key }}, {{ $class->id }})"
                                ><i class="fas fa-trash-alt"></i></button>
                            @endif
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

    <div class="modal fade" id="deleteAccount" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="deleteAccount" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xóa lớp học</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa lớp học <span id="modal-account_name"></span>?
                    <input type="hidden" id="account" name="account_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-white" data-bs-dismiss="modal"
                    >Hủy
                    </button>
                    <button type="button" class="btn btn-danger" id="modal-confirm_delete">Xóa</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function () {


        })

        function loadDeleteModal(key, id, email, token) {
            $('#modal-account_name').html(email);
            $('#modal-confirm_delete').attr('onclick', `confirmDelete(${key}, ${id}, ${token})`);
            $('#deleteAccount').modal('show');
        }

        function confirmDelete(key, id, token) {
            $.ajax({
                url: `{{ route('deleteclass', '') }}` + "/" + id,
                type: 'DELETE',
                data: {
                    '_method': 'delete',
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                dataType: 'json',
                success: function (data) {
                    $('#delete').modal('hide');
                    if (data.status === 1) {
                        toastr.success(data.message);
                        $('#listAccount').find('tr[data-key="' + key + '"]').remove();
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
