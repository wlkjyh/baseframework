<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // 控制台首页
    public function index(Request $r){

        try {
            $userrow = Auth::user();
            // 可以在这里根据用户角色来判断跳转到不同的页面
            // $userrole = $userrow->roles()->pluck('name')->toArray();
            // if (in_array('admin',$userrole)){
            //     return view('dashboard.web.index');
            // }

            return view('dashboard.web.index',compact('userrow'));


        }catch (\Throwable $e){

            return pageErrorInfo;
        }

    }
}
