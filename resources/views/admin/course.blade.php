@extends('layouts.app')
@section('title', 'Khóa học')

@section('content')
    <div class="card container px-5 py-4">
        <h4 class="text-danger" style="white-space: nowrap;">Thông tin khóa học</h4>
        <form method="POST" action="{{ route('admin.savecourse') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 row">
                <label for="name" class="col-md-2 col-form-label">Tên Khóa Học</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" id="name" name="name"
                           @if($action === 'edit') value="{{ $course['name'] }}" @endif>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="duration" class="col-md-2 col-form-label">Thời Gian (tháng)</label>
                <div class="col-md-10">
                    <input class="form-control" type="number" id="duration" name="duration"
                           @if($action === 'edit') value="{{ $course['duration'] }}" @endif>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="lesson_count" class="col-md-2 col-form-label">Số Buổi Học</label>
                <div class="col-md-10">
                    <input class="form-control" type="number" id="lesson_count" name="lesson_count"
                           @if($action === 'edit') value="{{ $course['lesson_count'] }}" @endif>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="course_type" class="col-md-2 col-form-label">Loại Khóa</label>
                <div class="col-md-10">
                    <select class="form-select form-select-md" aria-label=".form-select-lg example"
                            id="course_type" name="course_type">
                        <option selected disabled>Chọn loại khóa</option>
                        <option value="TOEIC"
                                @if($action === 'edit' && $course['course_type'] === 'TOEIC')
                                    selected
                            @endif>
                            TOEIC
                        </option>
                        <option value="IELTS"
                                @if($action === 'edit' && $course['course_type'] === 'IELTS')
                                    selected
                            @endif>
                            IELTS
                        </option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="level" class="col-md-2 col-form-label">Level</label>
                <div class="col-md-10">
                    <select class="form-select form-select-md" aria-label=".form-select-lg example"
                            id="level" name="level">
                        <option selected disabled>Chọn level</option>
                        <option value="sơ cấp"
                                @if($action === 'edit' && $course['level'] === 'sơ cấp')
                                    selected
                            @endif>
                            sơ cấp
                        </option>
                        <option value="trung cấp"
                                @if($action === 'edit' && $course['level'] === 'trung cấp')
                                    selected
                            @endif>
                            trung cấp
                        </option>
                        <option value="cao cấp"
                                @if($action === 'edit' && $course['level'] === 'cao cấp')
                                    selected
                            @endif>
                            cao cấp
                        </option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="price" class="col-md-2 col-form-label">Giá (VNĐ)</label>
                <div class="col-md-10">
                    <input class="form-control format-money" type="text" id="price" name="price"
                           @if($action === 'edit') value="{{ $course['price'] }}" @endif>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="status" class="col-md-2 col-form-label">Trạng Thái</label>
                <div class="col-md-10">
                    <select class="form-select form-select-md" aria-label=".form-select-lg example"
                            id="status" name="status">
                        <option selected disabled>Chọn Trạng Thái</option>
                        <option value="0" class="text-danger"
                                @if($action === 'edit' && $course['status'] === '0')
                                    selected
                            @endif>
                            Ngưng hoạt động
                        </option>
                        <option value="1" class="text-success"
                                @if($action === 'edit' && $course['status'] === '1')
                                    selected
                            @endif>
                            Hoạt động
                        </option>
                    </select>
                </div>
            </div>
            <!--Hidden block-->
            @if($action === 'edit')
                <input type="text" id="courseid" name="courseid" value="{{ $course['id'] }}" hidden readonly>
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
