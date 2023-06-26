@extends('layouts.app')
@vite(['public/css/admin/home.css'])
@section('title', 'Khóa học')


@section('content')
    <div class="card">
        <h4 class="card-header">Danh Sách Khóa Học</h4>
        <div class="row mx-4 mb-4">
            <a href="{{ route('admin.newcourse') }}" class="btn btn-info rounded-pill col-md-2">
                <i class="fas fa-plus-circle"></i>
                Thêm mới</a>
            <p class="col-md-8"></p>
            <p class="col-md-1 text-nowrap fw-bold info" style="padding-left: 3rem !important;">
                @if ($courses->total() > 0)
                    Tổng
                    {{ count($courses->items()) }} / {{ $courses->total() }}
                    giảng viên
                @endif
            </p>
        </div>
        <div class="table-responsive text-nowrap mx-4">
            <table class="table" id="listCourse">
                <thead>
                <tr class="text-nowrap">
                    <th>STT</th>
                    <th>Tên</th>
                    <th>Thời Gian</th>
                    <th>Loại Khóa</th>
                    <th>Mô tả</th>
                    <th>Giá (VNĐ)</th>
                    <th>Yêu Thích</th>
                    <th>Người Tạo</th>
                    <th>Thao Tác</th>
                </tr>
                </thead>
                <tbody>
                @if($courses->total() === 0)
                    <tr>
                        <td colspan="8">
                            <div class="divider divider-success">
                                <div class="divider-text text-lg">Không có dữ liệu</div>
                            </div>
                        </td>
                    </tr>
                @endif
                @foreach($courses as $key => $course)
                    <tr data-key="{{ $key }}">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $course['name'] }}</td>
                        <td>
                            @if ($course['duration'] > 0)
                                {{ $course['duration'] }} tháng <br>
                            @endif
                            {{ $course['lesson_count'] }} buổi
                        </td>
                        <td>
                            {{ $course['course_type'] }}
                            <br>
                            <strong>{{ $course['level'] }} </strong>
                        </td>
                        <td>
                            <textarea class="form-control" readonly cols="20"
                                      rows="5">{{ $course['description'] }}</textarea>
                        </td>
                        <td class="format-money">
                            {{ $course['price'] }}
                        </td>
                        <td>
                            {{ $course['like_count'] }}
                        </td>
                        <td>
                            @if ($course['status'] === '1')
                                <span class="badge bg-label-success me-1 text-capitalize">Hoạt động</span>
                            @elseif ($course['status'] === 2)
                                <span class="badge bg-label-warning me-1 text-capitalize">Chờ kích hoạt</span>
                            @elseif ($course['status'] === 0)
                                <span class="badge bg-label-danger me-1 text-capitalize">Không hoạt động</span>
                            @endif
                            {{ $course['creator'] }}
                            <br>{{ $course['created_at'] }}
                        </td>
                        <td>
                            <a class="btn btn-warning rounded-pill btn-icon me-2"
                               data-togle="tooltip" title="Chỉnh sửa"
                               href="{{ route('admin.editcourse', $course['id']) }}"><i
                                    class="fal fa-edit"></i></a>
                            <button class="btn btn-danger rounded-pill btn-icon"
                                    data-togle="tooltip" title="Xóa"
                                    onclick="loadDeleteModal({{ $key }}, {{ $course['id'] }}, '{{ $course['name'] }}')"
                            ><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="demo-inline-spacing row mt-3">
            {{ $courses->links('layouts.pagination') }}
        </div>
    </div>

    <div class="modal fade" id="deleteCourse" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="deleteCourse" aria-hidden="true">
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
                    Bạn có chắc chắn muốn xóa khóa học <strong><span id="modal-account_name"></span></strong>?
                    <input type="hidden" id="course_id" name="course_id">
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

        function loadDeleteModal(key, id, name) {
            $('#modal-account_name').html(name);
            $('#modal-confirm_delete').attr('onclick', `confirmDelete(${key}, ${id})`);
            $('#deleteCourse').modal('show');
        }

        function confirmDelete(key, id) {
            debugger;
            $.ajax({
                url: `{{ route('admin.deletecourse', '') }}` + "/" + id,
                type: 'DELETE',
                data: {
                    '_method': 'delete',
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                dataType: 'json',
                success: function (data) {
                    $('#deleteCourse').modal('hide');
                    if (data.status === 1) {
                        toastr.success(data.message);
                        $('#listCourse').find('tr[data-key="' + key + '"]').remove();
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
