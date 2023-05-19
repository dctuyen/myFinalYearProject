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
        return view('home')->with('uinfo', auth()->user());
    }

    public function new(Request $request): View|Factory|RedirectResponse|Application
    {
        $uinfo = auth()->user();
        $userId = $request->query('id');
        $user = $uinfo;
        $action = 'edit';
        $roleId = null;

        $routeName = Route::current()->uri;

        if ($request->filled('id')) {
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
        }
        $data = [
            'uinfo' => $uinfo,
            'userEdit' => $user,
            'action' => $action,
            'role' => $roleId
        ];
        if ($routeName === 'myaccount') {
            $data['activeSidebar'] = 'myaccount';
        } elseif ($routeName === 'editstudent' || $routeName === 'newstudent') {
            $data['activeSidebar'] = 'studentmanagement';
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
        }

        $dataPost = $request->all();
        $account->first_name = $dataPost['firstname'];
        $account->last_name = $dataPost['lastname'];
        $account->sex = $dataPost['sex'];
        $account->birthday = $dataPost['birthday'];
        $account->address = $dataPost['address'];
        $account->phone = $dataPost['phone'];

        if ($request->hasFile($dataPost['backgroundUrl'])) {
            $fileName = random_int(10000, 99999) . '_avt.' . $request->file('backgroundUrl')->extension();
            $fileLink = $request->file('backgroundUrl')->storeAs('/images/avatars', $fileName);
            $account->background_url = $fileLink;
        }
        if (!Helper::IsNullOrEmptyString($dataPost['password'])) {
            $account->password = Hash::make($dataPost['password']);
        }

        if ($action == 'edit') {
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
            return redirect()->route('admin.studentmanagement')->with('success', 'Tạo mới tài khoản thành công!');
        }
        if ($request->roleId == Constants::STUDENT_ROLE_ID) {
            return redirect()->route('admin.studentmanagement')->with('error', 'Tạo mới tài khoản thất bại!');
        }
//      trang chủ quản lý giảng viên
        return redirect()->route('admin.studentmanagement')->with('error', 'Tạo mới tài khoản thất bại!');
    }

    public
    function deleteAccount($userId): JsonResponse
    {
        $user = User::where('id', '=', $userId);
        if (!$user) {
            return response()->json(['status' => 0, 'message' => 'Tài khoản không tồn tại!']);
        }

        $user->delete();
        return response()->json(['status' => 1, 'message' => 'Xóa tài khoản thành công!']);

    }
}
