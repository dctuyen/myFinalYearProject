<?php

namespace App\Http\Controllers;

use App\Library\Constants;
use App\Library\Helper;
use App\Models\Classes;
use App\Models\Course;
use App\Models\Shift;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return RedirectResponse|Application|Factory|View
     */
    public function index(): View|Factory|RedirectResponse|Application
    {
        $data = [
            'uinfo' => auth()->user(),
            'activeSidebar' => 'home'
        ];
        return view('home')->with($data);
    }

    /**
     * @throws \JsonException
     */
    public function new(Request $request): View|Factory|RedirectResponse|Application
    {
        $uinfo = auth()->user();
        $userId = $request->id;
        $user = $uinfo;
        $action = 'edit';
        $roleId = null;

        $routeName = Route::current()->uri;

        if (!Helper::IsNullOrEmptyString($userId)) {
            $user = User::where('id', '=', $userId)->first();
            if (!$user) {
                if ($uinfo->role_id === Constants::ADMIN_ROLE_ID) {
                    return redirect()->route('admin.home')->with('error', 'Tài khoản không tồn tại!');
                }
                return redirect()->route('home')->with('error', 'Tài khoản không tồn tại!');
            }
        }

        if ($routeName === 'newstudent') {
            $action = 'new';
            $roleId = Constants::STUDENT_ROLE_ID;
        } else if ($routeName === 'newteacher') {
            $action = 'new';
            $roleId = Constants::TEACHER_ROLE_ID;
        } else if ($routeName === 'newcarer') {
            $action = 'new';
            $roleId = Constants::CARER_ROLE_ID;
        }

        $data = [
            'uinfo' => $uinfo,
            'userEdit' => $user,
            'action' => $action,
            'role' => $roleId
        ];

        if ($action === 'edit') {
            if (!Helper::IsNullOrEmptyString($user->certificate)) {
                $data['listCertificate'] = json_decode($user->certificate, false, 512, JSON_THROW_ON_ERROR);
                foreach ($data['listCertificate'] as $key => $certificate) {
                    $data['listCertificate'][$key] = get_object_vars($certificate);
                }
            }
        }

        if ($routeName === 'account/my') {
            $data['activeSidebar'] = 'myaccount';
        } elseif ($routeName === 'newstudent' || $routeName === 'account/edit/{id}') {
            $data['activeSidebar'] = 'studentmanagement';
        } elseif ($routeName === 'newteacher' || $routeName === 'account/edit/{id}') {
            $data['activeSidebar'] = 'teachermanagement';
        } elseif ($routeName === 'account/view/{id}') {
            $data['activeSidebar'] = 'none';
            $data['action'] = 'view';
            $data['backurl'] = url()->previous();
        }
        return view('account')->with($data);
    }

    /**
     * @throws Exception
     */
    public function editAccount(Request $request): Redirector|Application|RedirectResponse
    {
        $uinfo = auth()->user();
        $userId = $request->userId;
        $action = $request->action;
        if ($action == 'edit') {
            $account = User::find($userId);
            if (!$account) {
                return redirect(url()->previous())->with('error', 'Tài khoản không tồn tại!');
            }
            $account->updated_at = Helper::getCurrentTime();
        } else if ($action == 'new') {
            $account = new User();
            $account->role_id = $request->roleId;
            $account->created_at = Helper::getCurrentTime();
            $account->email = $request->email;
            $account->status = Constants::ACTIVATED_STATUS;
        }

        $dataPost = $request->all();
        $account->first_name = $dataPost['firstname'];
        $account->last_name = $dataPost['lastname'];
        $account->sex = $dataPost['sex'];
        $account->birthday = $dataPost['birthday'];
        $account->address = $dataPost['address'];
        $account->phone = $dataPost['phone'];

        if ($request->hasFile('backgroundUrl')) {
            $directoryPath = public_path() . '/images/avatars';
            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true); // Tham số thứ ba `true` để tạo cả các thư mục con nếu chúng chưa tồn tại
            }
            $fileName = random_int(10000, 99999) . '_avt.' . $request->file('backgroundUrl')->extension();
            $fileLink = $request->file('backgroundUrl')->storeAs('/images/avatars', $fileName);
            $account->background_url = $fileLink;
        }
        if (!Helper::IsNullOrEmptyString($dataPost['password'])) {
            $account->password = Hash::make($dataPost['password']);
        }
        if (!Helper::IsNullOrEmptyString($dataPost['certificateJson'])) {
            $directoryPath = public_path() . '/files/certificates';
            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true); // Tham số thứ ba `true` để tạo cả các thư mục con nếu chúng chưa tồn tại
            }
            $listCert = json_decode($dataPost['certificateJson'], false, 512, JSON_THROW_ON_ERROR);
            foreach ($listCert as $key => $cert) {
                $cert = get_object_vars($cert);
                if ($request->hasFile($cert['fileKey'])) {
                    $fileName = date('Ymd') . 'cert' . random_int(1000, 9999) . '.' . $request->file($cert['fileKey'])->extension();
                    $fileLink = $request->file($cert['fileKey'])->storeAs('/files/certificates', $fileName);
                    $listCert[$key]->fileUrl = $fileLink;
                }
                unset($listCert[$key]->fileKey);
            }
            $account->certificate = json_encode($listCert, JSON_THROW_ON_ERROR);
        }
        if ($action === 'edit') {
            if ($account->update()) {
                return redirect(url()->previous())->with('success', 'Cập nhật thông tin thành công!');
            }
            return redirect(url()->previous())->with('error', 'Cập nhật thông tin thất bại!');
        }
        if ($account->save()) {
            if ($request->roleId == Constants::STUDENT_ROLE_ID) {
                return redirect()->route('studentmanagement')->with('success', 'Tạo mới tài khoản thành công!');
            }
            //      trang chủ quản lý giảng viên
            return redirect()->route('teachermanagement')->with('success', 'Tạo mới tài khoản thành công!');
        }
        if ($request->roleId == Constants::STUDENT_ROLE_ID) {
            return redirect()->route('studentmanagement')->with('error', 'Tạo mới tài khoản thất bại!');
        }
        //      trang chủ quản lý giảng viên
        return redirect()->route('teachermanagement')->with('error', 'Tạo mới tài khoản thất bại!');
    }

    public function deleteAccount($userId): JsonResponse
    {
        $user = User::where('id', '=', $userId);
        if (!$user) {
            return response()->json(['status' => 0, 'message' => 'Tài khoản không tồn tại!']);
        }

        if ($user->delete()) {
            return response()->json(['status' => 1, 'message' => 'Xóa tài khoản thành công!']);
        }
        return response()->json(['status' => 0, 'message' => 'Xóa tài khoản không thành công!']);
    }

    /**
     * @throws Exception
     */
    public function class(Request $request)
    {
        $data = [
            'uinfo' => auth()->user(),
            'activeSidebar' => 'classmanagement'
        ];
        $data['action'] = 'new';
        $routeName = Route::current()->uri;
        $classId = $request->id;
        if (!Helper::IsNullOrEmptyString($classId)) {
            $classEdit = Classes::where('id', '=', $classId)->first();
            if (!$classEdit) {
                return redirect()->route('admin.coursemanagement')->with('error', 'Khóa học không tồn tại!');
            }
            $data['class'] = $classEdit;
            $data['action'] = 'edit';
            if ($routeName === 'class/view/{id}') {
                $data['action'] = 'view';
                $data['backurl'] = url()->previous();
                $data['activeSidebar'] = 'none';
                $course = Course::where('id', '=', $classEdit->course_id)->first();
                if (!Helper::IsNullOrEmptyString($course->document)) {
                    $data['listCertificate'] = json_decode($course->document, false, 512, JSON_THROW_ON_ERROR);
                    foreach ($data['listCertificate'] as $key => $document) {
                        $data['listCertificate'][$key] = get_object_vars($document);
                    }
                }
            }
        }

        $listCourses = Course::all();
        $listCarers = User::where('role_id', '=', Constants::CARER_ROLE_ID)->get();
        $listTeachers = User::where('role_id', '=', Constants::TEACHER_ROLE_ID)->get();
        $data['listCourses'] = $listCourses;
        $data['listCarers'] = $listCarers;
        $data['listTeachers'] = $listTeachers;
        $data['listShift'] = Shift::all();

        return view('class')->with($data);
    }

    public function saveclass(Request $request)
    {
        $classId = $request->classid;
        if (!Helper::IsNullOrEmptyString($classId)) {
            $class = Classes::where('id', '=', $classId)->first();
            if (!$class) {
                return redirect()->route('admin.coursemanagement')->with('error', 'Lớp học không tồn tại!');
            }
            $class->updated_at = Helper::getCurrentTime();
        } else {
            $class = new Classes();
            $class->created_at = Helper::getCurrentTime();
        }

        $class->name = $request->name;
        $class->carer_id = $request->carer_id;
        $class->teacher_id = $request->teacher_id;
        $class->course_id = $request->course_id;
        $class->start_date = $request->start_date;
        $class->end_date = $request->end_date;
        $class->number_of_students = $request->number_of_students;
        $class->shift_id = $request->shift_id;
        $class->status = Constants::ACTIVATED_STATUS;
        if ($class->save()) {
            return redirect()->route('admin.coursemanagement')->with('success', 'Lưu thông tin lớp học thành công!');
        }
        return redirect()->route('admin.coursemanagement')->with('error', 'Lớp học không tồn tại!');
    }

    public function checkExistsClassName(Request $request)
    {
        $className = $request->json()->all()['name'];
        $checkExists = Classes::whereRaw("name LIKE '%$className%'")->count();
        if ($checkExists > 0)
            $result = true;
        else
            $result = false;
        return response()->json(['result' => $result]);

    }

    public function classmanagement(Request $request)
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
                'teacher.email as teacher_email', 'carer.email as carer_email', 'Classes.*',
                'Courses.name as course_name', 'Shifts.classroom', 'Shifts.dates', 'Shifts.start_at', 'Shifts.end_at',
                DB::raw('CONCAT(teacher.first_name, " ", teacher.last_name) as teacher'),
                DB::raw('CONCAT(carer.first_name, " ", carer.last_name) as carer'),
                DB::raw('COUNT(Enrollments.id) as number_registered')
            );
        if (!Helper::IsNullOrEmptyString($searchData)) {
            $allClasses->where("Classes.name LIKE '%$searchData%'");
        }

        $routeName = Route::current()->uri;
        $sideBar = 'classmanagement';
        $now = date('Y-m-d');
        if ($routeName === 'classes/wait') {
            $allClasses->where("Classes.start_date", '>', $now);
            $sideBar = 'classwaiting';
        } else {
            $allClasses->where("Classes.start_date", '<=', $now);
        }

        if ($uinfo->role_id === Constants::TEACHER_ROLE_ID) {
            $allClasses->where('teacher_id', '=', $uinfo->id);
        } else if ($uinfo->role_id === Constants::CARER_ROLE_ID) {
            $allClasses->where('carer_id', '=', $uinfo->id);
        }
        $allClasses = $allClasses->groupBy('Classes.id')
            ->orderBy('Classes.id', 'DESC')->paginate(20);


        $data = [
            'uinfo' => auth()->user(),
            'classes' => $allClasses,
            'activeSidebar' => $sideBar
        ];
        return view('classmanagement')->with($data);
    }

    public function userManagement(Request $request): Factory|View|Application
    {
        $searchData = $request->search;
        $routeName = Route::current()->uri;
        if ($routeName === 'student' || $routeName = 'account/view/{id}') {
            $role_id = Constants::STUDENT_ROLE_ID;
            $title = 'Học Viên';
        } else if ($routeName === 'teacher') {
            $role_id = Constants::TEACHER_ROLE_ID;
            $title = 'Giảng Viên';
        } else if ($routeName === 'carer') {
            $role_id = Constants::CARER_ROLE_ID;
            $title = 'Nhân Viên Chăm Sóc';
        }

        $allUsers = DB::table('Users')
            ->leftJoin('Enrollments', 'Enrollments.student_id', '=', 'Users.id')
            ->leftJoin('Classes', 'Classes.id', '=', 'Enrollments.class_id')
            ->select('Users.*');


        if (!Helper::IsNullOrEmptyString($searchData)) {
            $allUsers->whereRaw("CONCAT(first_name, ' ', last_name) LIKE '%$searchData%'")
                ->orWhereRaw("email LIKE '%$searchData%'")
                ->orWhereRaw("phone LIKE '%$searchData%'");
        }
        $uinfo = auth()->user();
        $allUsers->where('role_id', '=', $role_id);
        if ($uinfo->role_id == Constants::TEACHER_ROLE_ID) {
            $allUsers->where('Classes.teacher_id', '=', $uinfo->id);
        }

        if (!Helper::IsNullOrEmptyString($request->id)) {
            $allUsers->where('Classes.id', '=', $request->id);
        }
        $allUsers = $allUsers->orderBy('id', 'DESC')->paginate(20);
        foreach ($allUsers as $user) {
            $creator = User::find($user->creator);
            if ($creator) {
                $user->creator_email = $creator->email;
            }
        }
        $data = [
            'uinfo' => auth()->user(),
            'users' => $allUsers,
            'activeSidebar' => $routeName . 'management',
            'title' => $title
        ];
        return view('usermanagement')->with($data);
    }
}
