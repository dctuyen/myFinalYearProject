@extends('layouts.app')
@vite(['public/css/admin/home.css'])
@section('title', 'Học viên')


@section('content')
    <div class="card">
        <h4 class="card-header">Danh Sách Học Viên</h4>
        <div class="row mx-4">
            <a href="{{ route('admin.newstudent') }}" class="btn btn-info col-md-2"><i class="fas fa-plus-circle"></i> Thêm mới</a>
            <p class="col-md-8"></p>
            <p class="col-md-1 text-nowrap fw-bold info" style="padding-left: 5rem !important;">
                Tổng
                @if ($students->total() > 0)
                    {{ count($students->items()) }} / {{ $students->total() }}
                @endif
                học viên
            </p>
        </div>
        <div class="table-responsive text-nowrap mx-4">
            <table class="table" id="listAccount">
                <thead>
                <tr class="text-nowrap">
                    <th>STT</th>
                    <th>Họ</th>
                    <th>Tên</th>
                    <th>Thông Tin</th>
                    <th>Liên Hệ</th>
                    <th>Avatar</th>
                    <th>Người Tạo</th>
                    <th>Thao Tác</th>
                </tr>
                </thead>
                <tbody>
                @foreach($students as $key => $student)
                    <tr data-key="{{ $key }}">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $student['first_name'] }}</td>
                        <td>{{ $student['last_name'] }}</td>
                        <td>
                            Giới tính:
                            @if ($student['sex'] === 'male')
                                Nam
                            @else
                                Nữ
                            @endif
                            <br>
                            Ngày sinh:
                            {{ $student['birthday'] }}
                            <br>
                            Địa chỉ:
                            {{ $student['address'] }}</td>
                        <td>
                            Email: {{ $student['email'] }}
                            <br>
                            SĐT: <strong>{{ $student['phone'] }}</strong>
                        </td>
                        <td><img src="{{ asset($student['background_url']) }}" style="height: 50px" alt=""></td>
                        <td>
                            @if ($student['status'] === 1)
                                <span class="badge bg-label-success me-1 text-capitalize">Hoạt động</span>
                            @elseif ($student['status'] === 2)
                                <span class="badge bg-label-warning me-1 text-capitalize">Chờ kích hoạt</span>
                            @elseif ($student['status'] === 3)
                                <span class="badge bg-label-danger me-1 text-capitalize">Không hoạt động</span>
                            @endif
                            {{ $student['creator'] }}
                            <br>{{ $student['created_at'] }}
                        </td>
                        <td>
                            <a class="btn btn-warning" href="{{ route('admin.newstudent') }}"><i class="fal fa-edit"></i></a>
                            <button class="btn btn-danger"
                                    onclick="loadDeleteModal({{ $key }}, {{ $student['id'] }}, '{{ $student['email'] }}')"
                            ><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="demo-inline-spacing row mt-3">
            {{ $students->links('layouts.pagination') }}
        </div>
    </div>

    <div class="modal fade" id="deleteAccount" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="deleteAccount" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xóa tài khoản</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa tài khoản <span id="modal-account_name"></span>?
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
                url: `{{ route('admin.deleteaccount', '') }}` + "/" + id,
                type: 'DELETE',
                data: {
                    '_method': 'delete',
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                dataType: 'json',
                success: function (data) {
                    $('#deleteAccount').modal('hide');
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
