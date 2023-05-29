<?php

namespace App\Http\Controllers;

use App\Library\Constants;
use App\Library\Helper;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
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
        $userId = $wid;
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

        if ($routeName === 'myaccount') {
            $data['activeSidebar'] = 'myaccount';
        } elseif ($routeName === 'newstudent' || $routeName === 'editaccount/{id}') {
            $data['activeSidebar'] = 'studentmanagement';
        } elseif ($routeName === 'newteacher' || $routeName === 'editaccount/{id}') {
            $data['activeSidebar'] = 'teachermanagement';
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
            $directoryPath = public_path() . '/images/certificates';
            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true); // Tham số thứ ba `true` để tạo cả các thư mục con nếu chúng chưa tồn tại
            }
            $listCert = json_decode($dataPost['certificateJson'], false, 512, JSON_THROW_ON_ERROR);
            foreach ($listCert as $key => $cert) {
                $cert = get_object_vars($cert);
                if ($request->hasFile($cert['fileKey'])) {
                    $fileName = date('Ymd') . 'cert' . random_int(1000, 9999) . '.' . $request->file($cert['fileKey'])->extension();
                    $fileLink = $request->file($cert['fileKey'])->storeAs('/images/certificates', $fileName);
                    $listCert[$key]->fileUrl = $fileLink;
                    unset($listCert[$key]->fileKey);
                }
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
                return redirect()->route('admin.studentmanagement')->with('success', 'Tạo mới tài khoản thành công!');
            }
        //      trang chủ quản lý giảng viên
            return redirect()->route('admin.teachermanagement')->with('success', 'Tạo mới tài khoản thành công!');
        }
        if ($request->roleId == Constants::STUDENT_ROLE_ID) {
            return redirect()->route('admin.studentmanagement')->with('error', 'Tạo mới tài khoản thất bại!');
        }
        //      trang chủ quản lý giảng viên
        return redirect()->route('admin.teachermanagement')->with('error', 'Tạo mới tài khoản thất bại!');
    }

    public
    function deleteAccount($userId): JsonResponse
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
}
