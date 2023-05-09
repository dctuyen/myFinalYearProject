<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Library\Constants;
use App\Library\Helper;
use App\Models\User;

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

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $data = [];

        $data['uinfo'] = auth()->user();
        return view('admin.home')->with($data);
    }

    public function studentManagement()
    {
        $allStudent = User::where('role_id', '=', Constants::STUDENT_ROLE_ID)->get()->toArray();

        $data = [
            'uinfo' => auth()->user(),
            'students' => $allStudent
        ];
        return view('admin.studentmanagement')->with($data);
    }
}
