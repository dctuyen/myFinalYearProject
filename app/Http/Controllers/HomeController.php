<?php

namespace App\Http\Controllers;

use App\Library\Constants;
use App\Library\Helper;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
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
        parent::__construct();
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

    public function myaccount(Request $request): View|Factory|RedirectResponse|Application
    {
        $uinfo = auth()->user();
        $userId = $request->query('id');
        $user = $uinfo;
        if ($request->filled('id')) {
            $user = User::where('id', '=', $userId)->first();
            if (!$user) {
                if ($uinfo->role_id === Constants::ADMIN_ROLE_ID) {
                    return redirect()->route('admin.home')->with('error', 'Tài khoản không tồn tại!');
                }
                return redirect()->route('home')->with('error', 'Tài khoản không tồn tại!');
            }
        }
        $data = [
            'uinfo' => $uinfo,
            'userEdit' => $user
        ];

        return view('myaccount')->with($data);
    }

    /**
     * @throws Exception
     */
    public function editAccount(Request $request): Redirector|Application|RedirectResponse
    {
        $uinfo = auth()->user();
        $userId = $request->userId;
        $account = User::find($userId);
        if (!$account) {
            if ($uinfo->role_id === Constants::ADMIN_ROLE_ID) {
                return redirect(url()->previous())->with('error', 'Tài khoản không tồn tại!');
            }
            return redirect(url()->previous())->with('error', 'Tài khoản không tồn tại!');
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
        $account->created_at = Helper::getCurrentTime();
        if ($account->update()) {
            return redirect(url()->previous())->with('success', 'Cập nhật thông tin thành công!');
        }
        return redirect(url()->previous())->with('success', 'Cập nhật thông tin thất bại!');
    }
}
