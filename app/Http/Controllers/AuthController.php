<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Setting;

class AuthController extends Controller
{
    // 登录
    public function login(Request $r)
    {
        try {
            if (Setting::get('dashboard_capcha', false)) {
                $r->validate([
                    'captcha' => 'required|captcha'
                ], [
                    'captcha.required' => '验证码不能为空',
                    'captcha.captcha' => '验证码错误'
                ]);
            }
            $r->validate([
                'name' => 'required|min:5|max:20',
                'password' => 'required|min:4'
            ]);

            $credentials = $r->only('name', 'password');
            $credentials['status'] = 1;
            if (Auth::attempt($credentials)) {
                return Response()->json(['code' => 200, 'msg' => '登录成功']);
            }

            return Response()->json(['code' => 400, 'msg' => '用户名、密码错误或者用户被管理员禁止登录。']);


        } catch (\Throwable $e) {
            if ($e instanceof ValidationException) {
                return Response()->json(['code' => 400, 'msg' => $e->validator->errors()->first()]);
            }
            return Response()->json(['code' => 500, 'msg' => '服务器出现了一些错误，请稍后再试']);
        }
    }

    public function logout(Request $r)
    {
        try {
            Auth::logout();
            return redirect()->route('auth.login');
        } catch (\Throwable $e) {
            return pageErrorInfo;
        }

    }
}
