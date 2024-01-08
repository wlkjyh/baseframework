<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateDashboard
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // if you route name in this array, you will not need to log in and access this page
        $_except = [

        ];

        // 不需要权限验证的路由，但是需要登录
        $_except_permission = [
            'web.getinfo'
        ];

        $ajax_return = response(['code' => 403, 'msg' => 'You don\'t have permission to access this page.']);
        $web_return = response()->view('dashboard.no_access', [], 403);

        $if_return = ($request->ajax()) ? $ajax_return : $web_return;


        if (in_array($request->route()->getName(), $_except)) {
            return $next($request);
        }

        if (!Auth::guard()->check()) {

            if ($request->ajax()) {
                return response(['code' => 401, 'msg' => __('message.user_notlogin')]);
            } else {
                $next = $request->fullUrl();
                return redirect()->route('auth.login', ['next' => $next]);
            }

        } else {

            if (in_array($request->route()->getName(), $_except_permission)) {
                return $next($request);
            }

            if (Auth::guard()->user()->status == 0) {
                if ($request->ajax()) {
                    return response(['code' => 403, 'msg' => __('message.account_forbidden')], 403);
                } else {
                    return response()->view('dashboard.no_access', [], 403);
                }
            }
            $uri = $request->path();

            // 开发集成工具需要DEBUG模式和admin权限
            if (strpos($uri, 'dashboard/web/develop') !== false) {
                if (config('app.debug') === false) {
                    return $if_return;
                }

                if (!Auth::guard()->user()->hasRole(['admin'])) {

                    return $if_return;
                }
            }

            // 如果是admin，直接放行
            if (Auth::user()->hasRole(['admin'])) {
                return $next($request);
            } else {
                // 如果是首页，直接放行
                if ($request->route()->getName() == 'web.index') {
                    return $next($request);
                }

                $userPermissions = Auth::user()->permissions();
                foreach ($userPermissions as $permission) {
                    $preg = '@^' . $permission['uri'] . '$@';
                    try {
                        if (preg_match($preg, $uri)) {
                            if (in_array('*', $permission['methods'])) {
                                return $next($request);
                            }
                            if (in_array($request->method(), $permission['methods'])) {
                                return $next($request);
                            }else{
                                return $if_return;
                            }
                        }
                    } catch (\Throwable $e) {
                        continue;
                    }
                }

                return $if_return;

            }

        }
    }
}
