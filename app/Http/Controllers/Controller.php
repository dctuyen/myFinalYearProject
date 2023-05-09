<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Library\Constants;
use App\Library\Helper;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $arrAuthName = [
                'login',
                'register',
                'logout',
                'forgotpassword',
                'resetpassword',
                'activation',
                'myaccount',
                'editaccount'
            ];
            $routeName = Route::current()->uri;
            if (!in_array($routeName, $arrAuthName)) {
                // Kiểm tra xem người dùng đã đăng nhập chưa
                if (!auth()->check()) {
                    return redirect()->route('login');
                }

                $uinfo = auth()->user();
                if ($uinfo->role_id !== Constants::ADMIN_ROLE_ID && strpos($routeName, 'admin')) {
                    $route = str_replace("admin/", '', $routeName);
                    return redirect()->route($route)->withInput();
                }
                $haveAdmin = false;
                if (str_contains($routeName, 'admin') or $routeName === 'myaccount')
                    $haveAdmin = true;
                if ($uinfo->role_id === Constants::ADMIN_ROLE_ID && !$haveAdmin) {
                    return redirect()->route('admin.' . $routeName)->withInput();
                }
            }
            return $next($request);
        });
    }
}
