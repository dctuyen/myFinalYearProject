<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\Constants;
use App\Library\Helper;
use App\Models\Course;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $data = [];

        $data['uinfo'] = auth()->user();
        $data['activeSidebar'] = 'home';
        return view('admin.home')->with($data);
    }

    /**
     * @throws Exception
     */
    public function studentManagement(Request $request): Factory|View|Application
    {
//        $allStudent = User::all();
//        foreach ($allStudent as $student) {
//            $values = collect([0, 1, 2]);
//            $randomStatus = $values->random() < 0.1 ? $values->random() : 1;
//            $student->status = $randomStatus;
//            $student->save();
//        }
        $searchData = $request->search;
        $allStudent = User::query();
        if (!Helper::IsNullOrEmptyString($searchData)) {
            $allStudent->whereRaw("CONCAT(first_name, ' ', last_name) LIKE '%$searchData%'")
                ->orWhereRaw("email LIKE '%$searchData%'")
                ->orWhereRaw("phone LIKE '%$searchData%'");
        }

        $allStudent->where('role_id', '=', Constants::STUDENT_ROLE_ID);

        $allStudent = $allStudent->orderBy('id', 'DESC')->paginate(20);
        foreach ($allStudent as $student) {
            $creator = User::find($student->creator);
            if ($creator) {
                $student->creator_email = $creator->email;
            }
        }
        $data = [
            'uinfo' => auth()->user(),
            'students' => $allStudent,
            'activeSidebar' => 'studentmanagement'
        ];
        return view('admin.studentmanagement')->with($data);
    }


    /**
     * @throws Exception
     */
    public function teacherManagement(Request $request): Factory|View|Application
    {
        $searchData = $request->search;
        $allTeachers = User::query();
        if (!Helper::IsNullOrEmptyString($searchData)) {
            $allTeachers->whereRaw("CONCAT(first_name, ' ', last_name) LIKE '%$searchData%'")
                ->orWhereRaw("email LIKE '%$searchData%'")
                ->orWhereRaw("phone LIKE '%$searchData%'");
        }

        $allTeachers->where('role_id', '=', Constants::TEACHER_ROLE_ID);

        $allTeachers = $allTeachers->orderBy('id', 'DESC')->paginate(20);
        foreach ($allTeachers as $teacher) {
            $creator = User::find($teacher->creator);
            if ($creator) {
                $teacher->creator_email = $creator->email;
            }
        }
        $data = [
            'uinfo' => auth()->user(),
            'teachers' => $allTeachers,
            'activeSidebar' => 'teachermanagement'
        ];
        return view('admin.teachermanagement')->with($data);
    }


    public function courseManagement(Request $request)
    {
        $searchData = $request->search;
        $allCourses = Course::query();
        if (!Helper::IsNullOrEmptyString($searchData)) {
            $allCourses->whereRaw("CONCAT(first_name, ' ', last_name) LIKE '%$searchData%'")
                ->orWhereRaw("email LIKE '%$searchData%'")
                ->orWhereRaw("phone LIKE '%$searchData%'");
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
        }
        return view('admin.course')->with($data);
    }

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
            $course = new Course();
            $course->created_at = Helper::getCurrentTime();
        }
        $course->name = $request->name;
        $course->duration = $request->duration;
        $course->lesson_count = $request->lesson_count;
        $course->course_type = $request->course_type;
        $course->level = $request->level;
        $course->price = $request->price;
        $course->status = $request->status;
        if ($course->save()) {
            return redirect()->route('admin.coursemanagement')->with('success', 'Lưu thông tin khóa học thành công!');
        }
        return redirect()->route('admin.coursemanagement')->with('error', 'Khóa học không tồn tại!');

    }

    /**
     * @throws Exception
     */
    public function createDataUser(): void
    {
        $json = file_get_contents(public_path() . '\datatest.json');
        $objects = json_decode($json, false, 512, JSON_THROW_ON_ERROR);

        $sdt = [
            '081', '082', '083', '084', '085',
            '031', '032', '033', '034', '035', '036', '037', '038', '039',
            '070', '076', '077', '078', '079'
        ];
        $minBirthday = strtotime('1998-01-01');
        $maxBirthday = strtotime('2005-12-31');
        $birthday = random_int($minBirthday, $maxBirthday);
        $address =
            [
                'Tỉnh Lào Cai', 'Yên Bái', 'Điện Biên', 'Hoà Bình', 'Lai Châu', 'Sơn La', 'Tỉnh Hà Giang', 'Thái Bình',
                'Cao Bằng', 'Bắc Kạn', 'Lạng Sơn', 'Tuyên Quang', ' Thái Nguyên', 'Phú Thọ', 'Bắc Giang', 'Quảng Ninh',
                'Tỉnh Bắc Ninh', 'Hà Nam', 'Hà Nội', 'Hải Dương', 'Hải Phòng', 'Hưng Yên', 'Nam Định', 'Ninh Bình',
                'Vĩnh Phúc'
            ];
        $count = 0;
        foreach ($objects as $ob) {
            if ($count >= 100) {
                break;
            }
            $rand = random_int(1, 2);
            $data = get_object_vars($ob);
            $email = strtolower(str_replace(' ', '', Helper::khongdau($data['full_name']))) . '@gmail.com';
            $checkExits = User::where('email', '=', $email);
            if ($checkExits) {
                continue;
            }

            $sex = 'male';
            if ($rand === 1) {
                $sex = 'female';
            }
            $phone = $sdt[array_rand($sdt)] . random_int(1000000, 9999999);
            $add = $address[array_rand($address)];
            User::create([
                'first_name' => $data['last_name'],
                'last_name' => $data['first_name'],
                'sex' => $sex,
                'birthday' => date('Y-m-d', $birthday),
                'address' => $add,
                'email' => $email,
                'phone' => $phone,
                'role_id' => Constants::TEACHER_ROLE_ID,
                'status' => Constants::ACTIVATED_STATUS,
                'password' => Hash::make('12345678'),
                'remember_token' => Helper::getRandomText(40),
                'created_at' => Helper::getCurrentTime()
            ]);
            $count++;
        }
    }
}
