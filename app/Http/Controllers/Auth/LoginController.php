<?php

namespace App\Http\Controllers\Auth;

use App\Classes\ActivationService;
use App\Http\Controllers\Controller;
use App\Library\Constants;
use App\Library\Helper;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    protected ActivationService $activationService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ActivationService $activationService)
    {
        $this->middleware('guest')->except('logout');
        $this->activationService = $activationService;
    }

    public function showLoginForm(): Factory|View|Application
    {
        return view('auth.login');
    }

    public function login(request $request): RedirectResponse
    {
        $user = User::where('email', '=', $request->email)->first();
        $remember = false;
        if ($request->remember === 'on') {
            $remember = true;
        }
        if ($user) {
            if ($user->status === Constants::WAIT_STATUS) {
                return redirect()->route('login')->with('error', 'Bạn cần xác thực tài khoản, chúng tôi đã gửi mã xác thực vào email của bạn, hãy kiểm tra và làm theo hướng dẫn.');
            }
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => Constants::ACTIVATED_STATUS], $remember)) {
                if (auth()->user()->role_id === Constants::ADMIN_ROLE_ID) {
                    return redirect()->route('admin.home')->with('success', 'Đăng nhập thành công');
                }
                return redirect()->route('home')->with('success', 'Đăng nhập thành công');
            }
            return redirect()->route('login')->with('error', 'Mật khẩu không đúng, vui lòng thử lại!');
        }
        return redirect()->route('login')->with('error', 'Tài khoản không tồn tại!');
    }

    public function forgotpassword(): Factory|View|Application
    {
        return view('auth.forgotpassword');
    }

    public function confirmrequest(request $request)
    {
        $user = User::where('email', '=', $request->email)->first();
        if (!$user) {
            return redirect()->route('forgotpassword')->with('warning', 'Không có email trùng khớp!');
        }
        $user->remember_token = Helper::getRandomText(40);
        $user->save();
        $this->activationService->sendPasswordRequestMail($user);
        return redirect()->route('login')->with('success', 'Yêu cầu thành công! vui lòng kiểm tra hòm thư!');
    }

    public function resetpassword($userid, $token)
    {
        $user = User::where('id', '=', $userid)
            ->where('remember_token', '=', $token)->first();
        if ($user) {
            return view('auth.resetpassword')->with('user', $user);
        }
        return redirect()->route('login')->with('error', 'Link hết thời hạn hoặc không hợp lệ, vui lòng thao tác lại!');
    }

    public function submitpassword(request $request)
    {
        $user = User::where('id', '=', $request->userId)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->remember_token = Helper::getRandomText(40);
            $user->updated_at = Helper::getCurrentTime();
            $user->save();
            return redirect()->route('login')->with('success', 'Đổi mật khẩu thành công');
        }
        return redirect()->route('login')->with('error', 'Thất bại! vui lòng thử lại');
    }
}
