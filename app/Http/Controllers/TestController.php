<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class TestController extends Controller
{
    //
    public function index(Request $r)
    {

        $t = Permission::where('created_at')->get();
    }
}
