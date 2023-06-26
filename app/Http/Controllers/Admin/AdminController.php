<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\Helper;
use App\Models\Classes;
use App\Models\Course;
use App\Models\Fee;
use App\Models\Shift;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function courseManagement(Request $request)
    {
        $searchData = $request->search;
        $allCourses = Course::query();
        if (!Helper::IsNullOrEmptyString($searchData)) {
            $allCourses->whereRaw("name LIKE '%$searchData%'")
                ->orWhereRaw("level LIKE '%$searchData%'")
                ->orWhereRaw("description LIKE '%$searchData%'");
        }

        $allCourses = $allCourses->orderBy('id', 'DESC')->paginate(20);
        $data = [
            'uinfo' => auth()->user(),
            'courses' => $allCourses,
            'activeSidebar' => 'coursemanagement'
        ];
        return view('admin.coursemanagement')->with($data);
    }


    /**
     * @throws Exception
     * @throws \JsonException
     */
    public function course(Request $request): View|Factory|RedirectResponse|Application
    {
        $data = [
            'uinfo' => auth()->user(),
            'activeSidebar' => 'coursemanagement'
        ];
        $data['action'] = 'new';
        $courseId = $request->id;
        if (!Helper::IsNullOrEmptyString($courseId)) {
            $courseEdit = Course::where('id', '=', $courseId)->first();
            if (!$courseEdit)
                return redirect()->route('admin.coursemanagement')->with('error', 'Khóa học không tồn tại!');
            $data['course'] = $courseEdit;
            $data['action'] = 'edit';
            if (!Helper::IsNullOrEmptyString($courseEdit->document)) {
                $data['listCertificate'] = json_decode($courseEdit->document, false, 512, JSON_THROW_ON_ERROR);
                foreach ($data['listCertificate'] as $key => $document) {
                    $data['listCertificate'][$key] = get_object_vars($document);
                }
            }
        }
        return view('admin.course')->with($data);
    }

    /**
     * @throws \JsonException
     */
    public function saveCourse(Request $request)
    {
        $courseId = $request->courseid;
        if (!Helper::IsNullOrEmptyString($courseId)) {
            $course = Course::where('id', '=', $courseId)->first();
            if (!$course) {
                return redirect()->route('admin.coursemanagement')->with('error', 'Khóa học không tồn tại!');
            }
            $course->updated_at = Helper::getCurrentTime();
        } else {
            $course = new Fee();
            $course->created_at = Helper::getCurrentTime();
        }
        $course->name = $request->name;
        $course->duration = $request->duration;
        $course->lesson_count = $request->lesson_count;
        $course->course_type = $request->course_type;
        $course->description = $request->description;
        $course->level = $request->level;
        $course->price = $request->price;
        $course->status = $request->status;
        $dataPost = $request->all();
        $listDocs = json_decode($dataPost['certificateJson'], false, 512, JSON_THROW_ON_ERROR);
        foreach ($listDocs as $key => $doc) {
            $doc = get_object_vars($doc);
            if ($request->hasFile($doc['fileKey'])) {
                $fileName = date('Ymd') . 'document' . random_int(1000, 9999) . '.' . $request->file($doc['fileKey'])->extension();
                $directoryPath = public_path() . '/files/documents';
                if (!File::exists($directoryPath)) {
                    File::makeDirectory($directoryPath, 0755, true); // Tham số thứ ba `true` để tạo cả các thư mục con nếu chúng chưa tồn tại
                }
                $fileLink = $request->file($doc['fileKey'])->storeAs('/files/documents', $fileName);
                $listDocs[$key]->fileUrl = $fileLink;
            }
            unset($listDocs[$key]->fileKey);
        }
        $course->document = json_encode($listDocs, JSON_THROW_ON_ERROR);

        if ($course->save()) {
            return redirect()->route('admin.coursemanagement')->with('success', 'Lưu thông tin khóa học thành công!');
        }
        return redirect()->route('admin.coursemanagement')->with('error', 'Khóa học không tồn tại!');

    }

    public function deleteCourse($courseId): JsonResponse
    {
        $course = Course::where('id', '=', $courseId);
        if (!$course) {
            return response()->json(['status' => 0, 'message' => 'Khóa học không tồn tại!']);
        }

        if ($course->delete()) {
            return response()->json(['status' => 1, 'message' => 'Xóa Khóa học thành công!']);
        }
        return response()->json(['status' => 0, 'message' => 'Xóa Khóa học không thành công!']);
    }
}
