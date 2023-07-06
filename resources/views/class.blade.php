@extends('layouts.app')
@section('title', 'Khóa học')

@section('content')
    <div class="card container px-5 py-4">
        <h4 class="text-danger" style="white-space: nowrap;">Thông tin lớp học</h4>
        <form method="POST" action="{{ route('saveclass') }}" enctype="multipart/form-data" id="formInfo">
            @csrf
            <div class="mb-3 row">
                <label for="name" class="col-md-2 col-form-label">Tên Lớp</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" id="name" name="name" readonly
                           @if($action === 'edit' || $action === 'view') value="{{ $class['name'] }}" @endif>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="carer_id" class="col-md-2 col-form-label">Người chăm sóc</label>
                <div class="col-md-10">
                    <select class="form-select form-select-md" aria-label=".form-select-lg example"
                            id="carer_id" name="carer_id" required>
                        <option selected disabled>Chọn người chăm sóc</option>
                        @foreach($listCarers as $carer)
                            <option value="{{ $carer['id'] }}"
                                    @if(($action === 'edit' || $action === 'view') && $class['carer_id'] === $carer['id'])
                                        selected
                                @endif>
                                {{ $carer['first_name'] . ' ' . $carer['last_name'] }} ({{ $carer['email'] }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="teacher_id" class="col-md-2 col-form-label">Giảng viên</label>
                <div class="col-md-10">
                    <select class="form-select form-select-md" aria-label=".form-select-lg example"
                            id="teacher_id" name="teacher_id" required>
                        <option selected disabled>Chọn giảng viên</option>
                        @foreach($listTeachers as $teacher)
                            <option value="{{ $teacher['id'] }}"
                                    @if(($action === 'edit' || $action === 'view') && $class['teacher_id'] === $teacher['id'])
                                        selected
                                @endif>
                                {{ $teacher['first_name'] . ' ' . $teacher['last_name'] }} ({{ $teacher['email'] }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="course_id" class="col-md-2 col-form-label">Khóa Học</label>
                <div class="col-md-10">
                    <select class="form-select form-select-md" aria-label=".form-select-lg example"
                            id="course_id" name="course_id" required>
                        <option selected disabled>Chọn khóa</option>
                        @foreach($listCourses as $course)
                            <option value="{{ $course['id'] }}" id="exp-{{ $course['duration'] }}"
                                    @if(($action === 'edit' || $action === 'view') && $class['course_id'] === $course['id'])
                                        selected
                                @endif>
                                {{ $course['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="start_date" class="col-md-2 col-form-label">
                    {{ __('Ngày bắt đầu') }}
                </label>
                <div class="col-md-10">
                    <input id="start_date" type="date" required autocomplete="start_date"
                           value="@if($action === 'edit' || $action === 'view'){{ $class->start_date }}@endif"
                           class="form-control"
                           name="start_date">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="end_date" class="col-md-2 col-form-label">
                    {{ __('Ngày kết thúc') }}
                </label>
                <div class="col-md-10">
                    <input id="end_date" type="date" required autocomplete="end_date" readonly
                           value="@if($action === 'edit' || $action === 'view'){{ $class->end_date }}@endif"
                           class="form-control"
                           name="end_date">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="shift_id" class="col-md-2 col-form-label">Ca học</label>
                <div class="col-md-10">
                    <select id="shift_id" class="form-select form-select-md" aria-label=".form-select-lg example"
                            data-dropdown="true" data-tags="true" name="shift_id">
                        @foreach ($listShift as $shift)
                            <option value="{{ $shift->id }}">Phòng {{ $shift->classroom }} ({{ $shift->start_at }}
                                tới {{ $shift->end_at }} {{ $shift->dates }} )
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="lesson_count" class="col-md-2 col-form-label">Số Lượng Học Viên</label>
                <div class="col-md-10">
                    <input class="form-control" type="number" id="number_of_students" name="number_of_students"
                           required
                           @if($action === 'edit' || $action === 'view') value="{{ $class['number_of_students'] }}"
                           @else value="0" @endif>
                </div>
            </div>
            <!--Hidden block-->
            @if($action === 'edit' || $action === 'view')
                <input type="text" id="classid" name="classid" value="{{ $class['id'] }}" hidden readonly>
            @endif
            <input type="text" id="action" name="action" value="{{ $action }}" hidden readonly>

            @if ($action === 'view')
                <div class="m-3 row">
                    <div class="table-responsive text-nowrap mt-3">
                        <table class="table" id="listCertificate">
                            <thead>
                            <tr class="text-nowrap">
                                <th>STT</th>
                                <th>Tên</th>
                                <th>Mô tả</th>
                                <th>Tệp tin</th>
                            </tr>
                            </thead>

                            <tbody>
                            @isset($listCertificate)
                                @foreach($listCertificate as $key => $certificate)
                                    <tr id="certificate{{ $key }}">
                                        <td id="cerId{{ $key }}">{{ $key + 1 }}</td>
                                        <td><input type="text" class="form-control" value="{{ $certificate['tên'] }}">
                                        </td>
                                        <td><input type="text" class="form-control" value="{{ $certificate['mô tả'] }}">
                                        </td>
                                        <td class="input-group">
                                            <input type="file" class="form-control" id="fileCertificate{{ $key }}"
                                                   name="fileCertificate{{ $key }}"
                                                   value="{{ $certificate['fileUrl'] }}">
                                            <a href="javascript:void(0)"
                                               onclick="window.open('{{ asset($certificate['fileUrl']) }}', '_blank', 'width=1000,height=800')"
                                               class="btn btn-outline-success"
                                               target="_blank" data-toggle="tooltip" title="Xem tài liệu">
                                                Xem tài liệu <i class="fas fa-eye"></i>
                                            </a>
                                            <input type="text" id="fileUrl{{ $key }}"
                                                   value="{{ $certificate['fileUrl'] }}" hidden>
                                        </td>
                                    </tr>
                                @endforeach
                            @endisset
                            </tbody>
                        </table>
                    </div>

                </div>
            @endif

            <div class="row mt-3">
                <div class="mt-2 col-md-3 d-flex ">
                    @if ($action === 'view')
                        <a href="{{ $backurl }}" class="btn btn-primary me-2" id="backButton"
                           data-toggle="tooltip" title="Quay lại">Quay lại
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary me-2" id="btnSubmit" data-toggle="tooltip"
                            title="Lưu thông tin">Lưu
                    </button>
                    <button type="reset" class="btn btn-danger">Hủy</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            if ($('#action').val() === 'view') {
                let inputs = document.getElementsByTagName("input");
                for (let i = 0; i < inputs.length; i++) {
                    inputs[i].readOnly = true;
                }
                let buttons = document.getElementsByTagName("button");

                for (let i = 0; i < buttons.length; i++) {
                    buttons[i].style.display = "none";
                }
                let selectElements = document.getElementsByTagName("select");
                for (let i = 0; i < selectElements.length; i++) {
                    selectElements[i].disabled = true;
                }
            }
        })

        $('#course_id').on('change', function () {
            let id = $('#classid').val();
            if (id !== '' && id !== undefined) {
                return;
            }
            let selectElement = document.getElementById("course_id");
            let option = selectElement.options[selectElement.selectedIndex];
            let courseName = option.text;
            let randomString = Math.random().toString(36).slice(2, 5).toUpperCase() +
                Math.random().toString(10).slice(2, 5)
            let textname = courseName.split(' ')[0].toUpperCase() + randomString;
            let jsonData = {name: textname};
            $('#name').val(textname);
            axios.post('{{ route('ajax.checkname') }}', jsonData, {
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(function (response) {
                    // Xử lý kết quả nhận được
                    let result = response.data.result;
                    if (result) {
                        $('#course_id').change();
                    }
                })
                .catch(function (error) {
                    // Xử lý lỗi (nếu có)
                    console.error(error);
                });
            $('#start_date').change();
        })

        $('#start_date').on('change', function () {
            let startDate = $('#start_date').val();
            let selectCourse = document.getElementById('course_id');
            let courseSelected = selectCourse.selectedOptions[0].id;
            let duration = parseInt(courseSelected.split('-')[1]);
            endDateCalculation(startDate, duration);
        })

        function endDateCalculation(startDate, duration) {
            let startD = new Date(startDate);
            s = startD.getMonth();
            let endDate = new Date(startD.getFullYear(), startD.getMonth() + duration, startD.getDate());
            let formattedEndDate = endDate.toISOString().slice(0, 10);
            $('#end_date').val(formattedEndDate);
        }

    </script>
@endsection
