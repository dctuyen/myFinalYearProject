@extends('layouts.app')
@vite(['public/css/admin/home.css'])
@section('title', 'Giảng viên')


@section('content')
    <div class="card">
        <h4 class="card-header">Danh Sách Giảng Viên</h4>
        <div class="row mx-4 mb-4">
            <a href="{{ route('admin.newteacher') }}" class="btn btn-info rounded-pill col-md-2">
                <i class="fas fa-plus-circle"></i>
                Thêm mới</a>
            <p class="col-md-8"></p>
            <p class="col-md-1 text-nowrap fw-bold info" style="padding-left: 3rem !important;">
                @if ($teachers->total() > 0)
                    Tổng
                    {{ count($teachers->items()) }} / {{ $teachers->total() }}
                    giảng viên
                @endif
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
                @if($teachers->total() == 0)
                    <tr>
                        <td colspan="8">
                            <div class="divider divider-success">
                                <div class="divider-text text-lg">Không có dữ liệu</div>
                            </div>
                        </td>
                    </tr>
                @endif
                @foreach($teachers as $key => $teacher)
                    <tr data-key="{{ $key }}">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $teacher['first_name'] }}</td>
                        <td>{{ $teacher['last_name'] }}</td>
                        <td>
                            Giới tính:
                            @if ($teacher['sex'] === 'male')
                                Nam
                            @else
                                Nữ
                            @endif
                            <br>
                            Ngày sinh:
                            {{ $teacher['birthday'] }}
                            <br>
                            Địa chỉ:
                            {{ $teacher['address'] }}</td>
                        <td>
                            Email: {{ $teacher['email'] }}
                            <br>
                            SĐT: <strong>{{ $teacher['phone'] }}</strong>
                        </td>
                        <td><img src="{{ asset($teacher['background_url']) }}" style="height: 50px" alt=""></td>
                        <td>
                            @if ($teacher['status'] === 1)
                                <span class="badge bg-label-success me-1 text-capitalize">Hoạt động</span>
                            @elseif ($teacher['status'] === 2)
                                <span class="badge bg-label-warning me-1 text-capitalize">Chờ kích hoạt</span>
                            @elseif ($teacher['status'] === 0)
                                <span class="badge bg-label-danger me-1 text-capitalize">Không hoạt động</span>
                            @endif
                            {{ $teacher['creator'] }}
                            <br>{{ $teacher['created_at'] }}
                        </td>
                        <td>
                            <a class="btn btn-warning rounded-pill btn-icon me-2"
                               data-togle="tooltip" title="Chỉnh sửa"
                               href="{{ route('admin.editaccount', $teacher['id']) }}"><i
                                    class="fal fa-edit"></i></a>
                            <button class="btn btn-danger rounded-pill btn-icon"
                                    data-togle="tooltip" title="Xóa"
                                    onclick="loadDeleteModal({{ $key }}, {{ $teacher['id'] }}, '{{ $teacher['email'] }}')"
                            ><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="demo-inline-spacing row mt-3">
            {{ $teachers->links('layouts.pagination') }}
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
