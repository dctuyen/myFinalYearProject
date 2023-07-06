<?php

namespace App\Http\Controllers;

use App\Library\Constants;
use App\Library\Helper;
use App\Models\Classes;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Fee;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class StudentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'uinfo' => auth()->user(),
            'activeSidebar' => 'student.index'
        ];

        $listCourses = Course::all();
        foreach ($listCourses as $key => $course) {
            $course->price = Helper::formatMoney((int)$course->price);
        }

        $data['listCourse'] = $listCourses;

        return view('student.index')->with($data);
    }

    public function listClass(request $request)
    {
        $data = [
            'uinfo' => auth()->user(),
            'activeSidebar' => 'student.index'
        ];

        $data['course'] = Course::where('id', $request->course)->first();

        $searchData = $request->search;

        $allClasses = DB::table('Classes')
            ->leftJoin('Users as teacher', 'Classes.teacher_id', '=', 'teacher.id')
            ->leftJoin('Users as carer', 'Classes.carer_id', '=', 'carer.id')
            ->leftJoin('Shifts', 'Shifts.id', '=', 'Classes.shift_id')
            ->leftJoin('Enrollments', 'Enrollments.class_id', '=', 'Classes.id')
            ->select(
                'teacher.email as teacher_email', 'carer.email as carer_email', 'Classes.*',
                'Shifts.classroom', 'Shifts.dates', 'Shifts.start_at', 'Shifts.end_at',
                DB::raw('CONCAT(teacher.first_name, " ", teacher.last_name) as teacher'),
                DB::raw('CONCAT(carer.first_name, " ", carer.last_name) as carer'),
                DB::raw('CONCAT("Phòng ", Shifts.classroom, " ", Shifts.start_at, " đến ", Shifts.end_at) as shift'),
                DB::raw('COUNT(Enrollments.id) as number_registered')
            );
        if (!Helper::IsNullOrEmptyString($searchData)) {
            $allClasses->whereRaw("name LIKE '%$searchData%'");
        }
        $allClasses = $allClasses->where('course_id', '=', $request->course)
            ->groupBy('Classes.id')
            ->orderBy('Classes.id', 'DESC')->paginate(20);
        $data['classes'] = $allClasses;
        return view('student.class')->with($data);

    }

    public function joinclass(request $request)
    {
        $classroom = Classes::where('id', '=', $request->id)->first();
        $studentId = auth()->user()->id;
        $checkExist = DB::table('Enrollments')
            ->leftJoin('Classes', 'Classes.id', '=', 'Enrollments.class_id')
            ->where('Classes.course_id', '=', $classroom->course_id)
            ->where('Enrollments.student_id', '=', $studentId)
            ->count();
        if ($checkExist > 0) {
            return response()->json(['status' => 0, 'message' => 'Đăng ký không thành công do bạn đã đăng ký lớp học cùng khóa học trước đó!']);
        }

        $course = Course::where('id', '=', $classroom->course_id)->first();

        $enrollment = new Enrollment();
        $enrollment->class_id = $classroom->id;
        $enrollment->student_id = $studentId;
        $enrollment->registration_date = Helper::getCurrentTime();
        $enrollment->status = Constants::ACTIVATED_STATUS;
        $enrollment->save();

        $fee = new Fee();
        $fee->class_id = $classroom->id;
        $fee->student_id = $studentId;
        $fee->course_id = $course->id;
        $fee->amount = $course->price;
        $fee->amount_paid = 0;
        $fee->save();

        return response()->json(['status' => 1, 'message' => 'Đăng ký học thành công!']);
    }

    public function myclass(Request $request)
    {
        $uinfo = auth()->user();
        $searchData = $request->search;

        $allClasses = DB::table('Classes')
            ->leftJoin('Users as teacher', 'Classes.teacher_id', '=', 'teacher.id')
            ->leftJoin('Users as carer', 'Classes.carer_id', '=', 'carer.id')
            ->leftJoin('Courses', 'Classes.course_id', '=', 'Courses.id')
            ->leftJoin('Shifts', 'Shifts.id', '=', 'Classes.shift_id')
            ->leftJoin('Enrollments', 'Enrollments.class_id', '=', 'Classes.id')
            ->select(
                'teacher.email as teacher_email', 'carer.email as carer_email', 'Classes.*', 'Shifts.end_at',
                'Courses.name as course_name', 'Shifts.classroom', 'Shifts.dates', 'Shifts.start_at', 'Shifts.classroom_online',
                DB::raw('CONCAT(teacher.first_name, " ", teacher.last_name) as teacher'),
                DB::raw('CONCAT(carer.first_name, " ", carer.last_name) as carer'),
                DB::raw('COUNT(Enrollments.id) as number_registered')
            )
            ->where('Enrollments.student_id', '=', $uinfo->id);
        if (!Helper::IsNullOrEmptyString($searchData)) {
            $allClasses->where("Classes.name LIKE '%$searchData%'");
        }

        $routeName = Route::current()->uri;
        $now = date('Y-m-d');

        $allClasses = $allClasses->groupBy('Classes.id')
            ->orderBy('Classes.id', 'DESC')->paginate(20);


        $data = [
            'uinfo' => auth()->user(),
            'classes' => $allClasses,
            'activeSidebar' => 'myclass'
        ];
        return view('student.myclass')->with($data);
    }

    /**
     * @throws \Exception
     */
    public function schedule(request $request)
    {
        $uinfo = auth()->user();
        $searchData = $request->search;

        $classroomJoined = DB::table('Enrollments')
            ->leftJoin('Classes', 'Classes.id', '=', 'Enrollments.class_id')
            ->leftJoin('Users as teacher', 'Classes.teacher_id', '=', 'teacher.id')
            ->leftJoin('Courses', 'Classes.course_id', '=', 'Courses.id')
            ->leftJoin('Shifts', 'Shifts.id', '=', 'Classes.shift_id')
            ->select(
                'teacher.email as teacher_email', 'Classes.*', 'Shifts.end_at', 'Courses.lesson_count',
                'Courses.name as course_name', 'Shifts.classroom', 'Shifts.dates', 'Shifts.start_at', 'Shifts.classroom_online',
                DB::raw('CONCAT(teacher.first_name, " ", teacher.last_name) as teacher'),
                DB::raw('COUNT(Enrollments.id) as number_registered')
            );
        if ($uinfo->role_id == Constants::STUDENT_ROLE_ID) {
            $classroomJoined->where('Enrollments.student_id', '=', $uinfo->id);
        } else if ($uinfo->role_id == Constants::TEACHER_ROLE_ID) {
            $classroomJoined->where('Classes.teacher_id', '=', $uinfo->id);
        }
        $classroomJoined = $classroomJoined
            ->groupBy('Classes.id')
            ->orderBy('Classes.id', 'DESC')->get();
        $dayMapping = [
            'Monday' => 'Thứ Hai',
            'Tuesday' => 'Thứ Ba',
            'Wednesday' => 'Thứ Tư',
            'Thursday' => 'Thứ Năm',
            'Friday' => 'Thứ Sáu',
            'Saturday' => 'Thứ Bảy',
            'Sunday' => 'Chủ Nhật',
        ];
        $calendars = [];
        foreach ($classroomJoined as $item) {
            $startDate = new DateTime($item->start_date);
            $endDate = new DateTime($item->end_date);
            $dates = explode(", ", $item->dates);
            $count = 0;
            while ($startDate <= $endDate) {
                $dayOfWeek = $startDate->format('l');
                $dayVietnamese = $dayMapping[$dayOfWeek];

                if (in_array($dayVietnamese, $dates)) {
                    $end = $startDate->format('Y-m-d');
                    $end = new DateTime($end);
                    $calendars[] = [
                        'title' => $item->course_name . " Thời gian: " . $item->start_at . " - " . $item->end_at . "  Tại: " . $item->classroom,
                        'start' => date("Y-m-d H:i:s", strtotime($startDate->format('Y-m-d') . " " . $item->start_at . ':00')),
                        'end' => date("Y-m-d H:i:s", strtotime($end->format('Y-m-d') . " " . $item->end_at . ':00')),
                    ];
                    $count++;
                }
                if ($count >= $item->lesson_count)
                    break;
                $startDate->modify('+1 day');
            }
        }

        $data = [
            'uinfo' => auth()->user(),
            'activeSidebar' => 'schedule',
            'calendars' => $calendars
        ];

        return view('student.schedule')->with($data);
    }
}
