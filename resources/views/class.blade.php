@extends('layouts.app')
@section('title', 'Khóa học')

@section('content')
    <div class="card container px-5 py-4">
        <h4 class="text-danger" style="white-space: nowrap;">Thông tin lớp học</h4>
        <form method="POST" action="{{ route('saveclass') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 row">
                <label for="name" class="col-md-2 col-form-label">Tên Lớp</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" id="name" name="name" readonly
                           @if($action === 'edit') value="{{ $class['name'] }}" @endif>
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
                                    @if($action === 'edit' && $class['carer_id'] === $carer['id'])
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
                                    @if($action === 'edit' && $class['teacher_id'] === $teacher['id'])
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
                                    @if($action === 'edit' && $class['course_id'] === $course['id'])
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
                           value="@if($action === 'edit'){{ $class->start_date }}@endif" class="form-control"
                           name="start_date">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="end_date" class="col-md-2 col-form-label">
                    {{ __('Ngày kết thúc') }}
                </label>
                <div class="col-md-10">
                    <input id="end_date" type="date" required autocomplete="end_date" readonly
                           value="@if($action === 'edit'){{ $class->end_date }}@endif" class="form-control"
                           name="end_date">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="shift_id" class="col-md-2 col-form-label">Ca học</label>
                <div class="col-md-10">
                    <select id="shift_id" class="form-select form-select-md" aria-label=".form-select-lg example"
                            data-dropdown="true" data-tags="true" name="shift_id">
                      @foreach ($listShift as $shift)
                            <option value="{{ $shift->id }}">Phòng {{ $shift->classroom }} ({{ $shift->start_at }} tới {{ $shift->end_at }} {{ $shift->dates }} )</option>
                      @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="lesson_count" class="col-md-2 col-form-label">Số Lượng Học Viên</label>
                <div class="col-md-10">
                    <input class="form-control" type="number" id="number_of_students" name="number_of_students"
                           required @if($action === 'edit') value="{{ $class['number_of_students'] }}"
                           @else value="0" @endif>
                </div>
            </div>
            <!--Hidden block-->
            @if($action === 'edit')
                <input type="text" id="classid" name="classid" value="{{ $class['id'] }}" hidden readonly>
            @endif

            <div class="row mt-3" style="float: right">
                <div class="mt-2 col-md-3 d-flex justify-content-between">
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
