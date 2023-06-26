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
                    <input class="form-control" type="text" id="name" name="name" required
                           @if($action === 'edit') value="{{ $course['name'] }}" @endif>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="duration" class="col-md-2 col-form-label">Thời Gian (tháng)</label>
                <div class="col-md-10">
                    <input class="form-control" type="number" id="duration" name="duration" required
                           @if($action === 'edit') value="{{ $course['duration'] }}" @endif>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="lesson_count" class="col-md-2 col-form-label">Số Buổi Học</label>
                <div class="col-md-10">
                    <input class="form-control" type="number" id="lesson_count" name="lesson_count" required
                           @if($action === 'edit') value="{{ $course['lesson_count'] }}" @endif>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="course_type" class="col-md-2 col-form-label">Loại Khóa</label>
                <div class="col-md-10">
                    <select class="form-select form-select-md" aria-label=".form-select-lg example" required
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
                <label for="description" class="col-md-2 col-form-label">Mô tả</label>
                <div class="col-md-10">
                    <textarea id="description" name="description" class="form-control" cols="20" rows="5" style="text-align: justify;"
                    >@if($action === 'edit') {{ $course['description'] }} @endif
                    </textarea>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="price" class="col-md-2 col-form-label">Giá (VNĐ)</label>
                <div class="col-md-10">
                    <input class="form-control format-money" type="text" id="price" name="price" required
                           @if($action === 'edit') value="{{ $course['price'] }}" @else value="0" @endif>
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
            <input type="text" name="certificateJson" id="certificateJson" hidden>
            <table style="width: 100%" class="mt-4">
                <tr>
                    <td style="width:1px; white-space: nowrap;">Tài liệu</td>
                    <td>
                        <hr/>
                    </td>
                    <td>
                        <hr/>
                    </td>
                </tr>
            </table>

            <div class="m-2">
                <button type="button" class="btn btn-outline-success" onclick="addRow()" data-toggle="tooltip"
                        title="Thêm tài liệu">
                    <i class="fas fa-plus-circle"></i>
                    Thêm mới
                </button>
                <div class="table-responsive text-nowrap mt-3">
                    <table class="table" id="listCertificate">
                        <thead>
                        <tr class="text-nowrap">
                            <th>STT</th>
                            <th>Tên</th>
                            <th>Mô tả</th>
                            <th>Tệp tin</th>
                            <th>Thao tác</th>
                        </tr>
                        </thead>

                        <tbody>
                        @isset($listCertificate)
                            @foreach($listCertificate as $key => $certificate)
                                <tr id="certificate{{ $key }}">
                                    <td id="cerId{{ $key }}">{{ $key + 1 }}</td>
                                    <td><input type="text" class="form-control" value="{{ $certificate['tên'] }}"></td>
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
                                        <input type="text" id="fileUrl{{ $key }}" value="{{ $certificate['fileUrl'] }}" hidden>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-danger" id="deleteCert{{ $key }}"
                                                onclick="deleteRow(this.id)"
                                                data-toggle="tooltip" title="Xóa tài liệu">
                                            <i class="fas fa-minus-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endisset
                        </tbody>
                    </table>
                </div>

            </div>
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
        function addRow() {
            let getRow = document.querySelectorAll("#listCertificate tr");
            let key = getRow.length;
            if (key > 10) {
                toastr.error('Không vượt quá 10 tài liệu!');
                return;
            }
            let newRow =
                `<tr id="certificate${key - 1}">` +
                `   <td id="certId${key - 1}">${key}</td> ` +
                '   <td><input type="text" class="form-control"></td>' +
                '   <td><input type="text" class="form-control"></td>' +
                `   <td><input type="file" class="form-control" id="fileCertificate${key - 1}" name="fileCertificate${key - 1}"></td>` +
                '   <td class="text-center">' +
                `       <button type="button" class="btn btn-outline-danger" id="deleteCert${key - 1}" onclick="deleteRow(this.id)"` +
                '            data-toggle="tooltip" title="Xóa tài liệu">' +
                '            <i class="fas fa-minus-circle"></i>' +
                '       </button>' +
                '   </td>' +
                '</tr>';
            $("#listCertificate tbody").append(newRow);
        }

        let deleteArr = [];

        function deleteRow(id) {
            let index = id.slice(-1);
            $('#certificate' + index).remove();
            deleteArr.push(index);
            let getRow = document.querySelectorAll("#listCertificate tr");
            let maxRow = getRow.length - 1;
            let key = 1;
            for (let i = 0; i <= 10; i++) {
                if (deleteArr.includes(`${i}`)) {
                    continue;
                }
                $('#certId' + i).text(key);
                key++;
                if (key === maxRow + 1) break;
            }
        }

        $("#btnSubmit").on("click", function () {
            let table = document.getElementById('listCertificate');
            let rows = table.rows;
            let data = [];

            for (let i = 1; i < rows.length; i++) {
                let row = rows[i];
                let rowData = {};

                for (let j = 1; j < row.cells.length - 1; j++) {
                    let cell = row.cells[j];
                    let columnHeader = table.rows[0].cells[j].innerText;
                    let input = cell.querySelector('input');
                    if (j === row.cells.length - 2)
                        rowData['fileKey'] = input.id;
                    else
                        rowData[columnHeader.toLowerCase()] = input.value;
                }
                rowData['fileUrl'] = $(`#fileUrl${i - 1}`).val();
                data.push(rowData);
            }
            let jsonData = JSON.stringify(data);
            $('#certificateJson').val(jsonData);
        });
    </script>
@endsection
