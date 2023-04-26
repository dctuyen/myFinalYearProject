<?php

namespace App\Http\Controllers\Auth;

use App\Classes\ActivationService;
use App\Http\Controllers\Controller;
use App\Library\Constants;
use App\Library\Helper;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected string $redirectTo = RouteServiceProvider::HOME;
    protected ActivationService $activationService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ActivationService $activationService)
    {
        $this->middleware('guest');
        $this->activationService = $activationService;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \Illuminate\Validation\Validator
     */
    protected function validation(array $data): \Illuminate\Validation\Validator
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:13', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8'],
        ]);

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data): User
    {
        return User::create([
            'first_name' => $data['firstname'],
            'last_name' => $data['lastname'],
            'sex' => $data['sex'],
            'birthday' => $data['birthday'],
            'address' => $data['address'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'role_id' => $data['role_id'],
            'status' => $data['status'],
            'background_url' => $data['avtUrl'],
            'password' => Hash::make($data['password']),
            'remember_token' => Helper::getRandomText(40),
            'created_at' => Helper::getCurrentTime()
        ]);
    }

    public function index()
    {
        return view('auth.register');
    }

    /**
     * @throws Exception
     */
    public function new(Request $request)
    {
         $this->validation($request->all());

        $fileName = null;
        if ($request->hasFile('backgroundUrl')) {
            $fileName = random_int(10000, 99999) . '_avt.' . $request->file('backgroundUrl')->extension();
            $request->file('backgroundUrl')->storeAs('public/images/avatars/', $fileName);
        }

        $request->merge(['avtUrl' => $fileName]);
        $request->merge(['role_id' => Constants::STUDENT_ROLE_ID]);
        $request->merge(['status' => Constants::WAIT_STATUS]);
        $user = $this->create($request->all());
        event(new Registered($user));
        $this->activationService->sendActivationMail($user);
        return redirect('/login')->with('success', 'Bạn đã đăng ký tài khoản thành công! Kiểm tra email để kích hoạt tài khoản');
    }

    public function activateUser($token): Redirector|Application|RedirectResponse
    {
        $userActive = User::where('remember_token', $token)->where('status', '=', Constants::WAIT_STATUS)->first();
        if (!$userActive) {
            return redirect('/login')->with('error', 'Kích hoạt tài khoản không thành công, link không tồn tại!');
        }
        $userActive->status = Constants::ACTIVATED_STATUS;
        $userActive->remember_token = Helper::getRandomText(40);
        $userActive->save();
        return redirect('/login')->with('success', 'Kích hoạt tài khoản thành công, đăng nhập để tiếp tục!');
    }
}

