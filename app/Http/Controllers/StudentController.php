<?php

namespace App\Http\Controllers;

use App\Library\Helper;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $data['listCourse'] = Course::all();

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
            $allClasses->where("Classes.name LIKE '%$searchData%'");
        }
        $allClasses = $allClasses->where('course_id', '=', $request->course)
            ->groupBy('Classes.id')
            ->orderBy('Classes.id', 'DESC')->paginate(20);
        $data['classes'] = $allClasses;
        return view('student.class')->with($data);

    }
}
