<?php

namespace App\Http\Middleware;

use App\Library\Constants;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role_id !== Constants::ADMIN_ROLE_ID) {
            abort(403, 'Không có quyền truy cập');
        }
        return $next($request);
    }
}
