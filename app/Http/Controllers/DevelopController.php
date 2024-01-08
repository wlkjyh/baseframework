<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use App\Plugin\Notify\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DevelopController extends Controller
{
    // 菜单管理器
    public function menu(Request $r)
    {
        $menus = Menu::get();
        // print_r(Menu::getTrees());
        $zNodes = Menu::getTrees();
        return view('dashboard.develop.menu', compact('menus', 'zNodes'));


    }

//     创建菜单
    public function menuCreate(Request $r)
    {
        try {
            $this->validate($r, [
                'name' => 'required|unique:menus,name',
                'path' => 'required',
                'icon' => 'required',
                'parent_id' => 'required|integer',
            ]);

            if ($r->parent_id != 0) {
                $parent = Menu::find($r->parent_id);
                if (!$parent) {
                    return Response()->json([
                        'code' => 500,
                        'msg' => '父级菜单不存在'
                    ]);
                }
            }
            $menu = Menu::create([
                'name' => $r->name,
                'path' => $r->path,
                'icon' => $r->icon,
                'parent_id' => $r->parent_id,
            ]);
            return Response()->json([
                'code' => 200,
                'msg' => '创建成功'
            ]);


        } catch (\Exception $e) {
            return Response()->json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

//    保存层级
    public function menuSaveTree(Request $r)
    {
        try {
            $this->validate($r, [
                'data' => 'required|json',
            ]);
            $data = json_decode($r->data, true);
            foreach ($data as $item) {
                $menu = Menu::find($item['id']);
                if ($item['pId'] == null) {
                    $menu->parent_id = 0;
                    $menu->save();
                } else {
                    $parent = Menu::find($item['pId']);
                    if (!$parent) {
                        return Response()->json([
                            'code' => 500,
                            'msg' => '父级菜单不存在'
                        ]);
                    }
                    $menu->parent_id = $item['pId'];
                    $menu->save();
                }
            }
            return Response()->json([
                'code' => 200,
                'msg' => '保存成功'
            ]);
        } catch (\Exception $e) {
            return Response()->json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function menuDelete(Request $r)
    {
        try {
            $this->validate($r, [
                'id' => 'required|integer',
            ]);
            $menu = Menu::find($r->id);
            if (!$menu) {
                return Response()->json([
                    'code' => 500,
                    'msg' => '菜单不存在'
                ]);
            }
            Menu::deleteAndChildren($r->id);
            return Response()->json([
                'code' => 200,
                'msg' => '删除成功'
            ]);
        } catch (\Exception $e) {
            return Response()->json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function menuEdit(Request $r)
    {
        try {
            $this->validate($r, [
                'id' => 'required|integer|exists:menus,id',
            ]);
            $row = Menu::find($r->id);
            return view('dashboard.develop.menuEdit', compact('row'));
        } catch (\Exception $e) {
            return pageErrorInfo;
        }
    }

    public function menuEditSubmit(Request $r)
    {
        try {
            $this->validate($r, [
                'id' => 'required|integer|exists:menus,id',
                'names' => 'required|unique:menus,name,' . $r->id,
                'paths' => 'required',
                'icons' => 'required',
            ]);
            $menu = Menu::find($r->id);
            $menu->name = $r->names;
            $menu->path = $r->paths;
            $menu->icon = $r->icons;
            $menu->save();
            return Response()->json([
                'code' => 200,
                'msg' => '修改成功'
            ]);

        } catch (\Exception $e) {
            return Response()->json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }


    public function role(Request $r)
    {
        try {
            $rows = Role::paginate(15);
            return view('dashboard.develop.role', compact('rows'));
        } catch (\Exception $e) {
            return pageErrorInfo;
        }
    }

    public function roleCreateSubmit(Request $r)
    {
        try {

            $this->validate($r, [
                'name' => 'required|unique:roles,name,',
                'menus' => 'required|array',
                'permissions' => 'required|array',
            ]);
            $role = Role::create([
                'name' => $r->name,
            ]);
            $role->menus()->sync($r->menus);
            $role->permissions()->sync($r->permissions);
            return Response()->json([
                'code' => 200,
                'msg' => '创建成功'
            ]);

        } catch (\Exception $e) {
            return Response()->json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function roleEdit(Request $r, $id)
    {
        try {
            $row = Role::find($id);
            if (!$row) {
                return '角色不存在';
            }
            if ($row->name == 'admin' || $id == 1) {
                return 'admin角色不允许修改';
            }

            $role_menus = $row->menus->pluck('id')->toArray();
            $role_permissions = $row->permissions->pluck('id')->toArray();

            return view('dashboard.develop.roleEdit', compact('row', 'role_menus', 'role_permissions'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function roleEditSubmit(Request $r)
    {
        try {
            $this->validate($r, [
                'id' => 'required|integer|exists:roles,id',
                'name' => 'required|unique:roles,name,' . $r->id,
                'menus' => 'required|array',
                'permissions' => 'required|array',
            ]);
            $role = Role::find($r->id);
            $role->name = $r->name;
            $role->save();
            $role->menus()->sync($r->menus);
            $role->permissions()->sync($r->permissions);
            return Response()->json([
                'code' => 200,
                'msg' => '修改成功'
            ]);
        } catch (\Exception $e) {
            return Response()->json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function roleDelete(Request $r, $id)
    {
        try {
            $row = Role::find($id);
            if (!$row) {
                return Response()->json([
                    'code' => 500,
                    'msg' => '角色不存在'
                ]);
            }
            if ($row->name == 'admin' || $id == 1) {
                return Response()->json([
                    'code' => 500,
                    'msg' => 'admin角色不允许删除'
                ]);
            }
            $row->delete();
            $row->menus()->detach();
            return Response()->json([
                'code' => 200,
                'msg' => '删除成功'
            ]);
        } catch (\Exception $e) {
            return Response()->json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }

    }

    //权限管理器
    public function permission(Request $r)
    {
        try {
            $rows = Permission::paginate(15);
            return view('dashboard.develop.permission', compact('rows'));
        } catch (\Exception $e) {
            return pageErrorInfo;
        }
    }

    public function permissionCreateSubmit(Request $r)
    {
        try {
            $this->validate($r, [
                'name' => 'required|unique:permissions,name,',
                'uri' => 'required',
                'methods' => 'required|array',
            ]);
            if (in_array('*', $r->methods)) {
                $r->methods = ['*'];
            }

            if (str_starts_with($r->uri, '/')) {
                $r->uri = substr($r->uri, 1);
            }
            if (str_ends_with($r->uri, '/')) {
                $r->uri = substr($r->uri, 0, -1);
            }

            if (str_starts_with($r->uri, 'dashboard/develop')) {
                return Response()->json([
                    'code' => 500,
                    'msg' => '不能在开发集成工具上应用权限。'
                ]);
            }

            Permission::create([
                'name' => $r->name,
                'uri' => $r->uri,
                'methods' => $r->methods,
            ]);
            return Response()->json([
                'code' => 200,
                'msg' => '创建成功'
            ]);

        } catch (\Exception $e) {
            return Response()->json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function permissionEdit(Request $r, $id)
    {
        try {
            $row = Permission::find($id);
            if (!$row) {
                return '权限不存在';
            }
            return view('dashboard.develop.permissionEdit', compact('row'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function permissionEditSubmit(Request $r)
    {
        try {
            $this->validate($r, [
                'id' => 'required|integer|exists:permissions,id',
                'name' => 'required|unique:permissions,name,' . $r->id,
                'uri' => 'required',
                'methods' => 'required|array',
            ]);
            if (in_array('*', $r->methods)) {
                $r->methods = ['*'];
            }

            if (str_starts_with($r->uri, '/')) {
                $r->uri = substr($r->uri, 1);
            }
            if (str_ends_with($r->uri, '/')) {
                $r->uri = substr($r->uri, 0, -1);
            }

            if (str_starts_with($r->uri, 'dashboard/develop')) {
                return Response()->json([
                    'code' => 500,
                    'msg' => '不能在开发集成工具上应用权限。'
                ]);
            }

            $permission = Permission::find($r->id);

            $permission->name = $r->name;
            $permission->uri = $r->uri;
            $permission->methods = $r->methods;
            $permission->save();
            return Response()->json([
                'code' => 200,
                'msg' => '修改成功'
            ]);
        } catch (\Exception $e) {
            return Response()->json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }

    }

    public function permissionDelete(Request $r, $id)
    {
        try {
            $row = Permission::find($id);
            if (!$row) {
                return Response()->json([
                    'code' => 500,
                    'msg' => '权限不存在'
                ]);
            }
            $row->delete();
            $row->roles()->detach();
            return Response()->json([
                'code' => 200,
                'msg' => '删除成功'
            ]);
        } catch (\Exception $e) {
            return Response()->json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }

    }

    public function test(Request $r)
    {
        print_r(Menu::getUserMenu(Auth::user()));
    }

    public function getinfo(Request $r)
    {
        try {
            $user = Auth::user();

            $notify = Notify::getOne();

            return Response()->json([
                'code' => 200,
                'msg' => '获取成功',
                'data' => [
                    'notify' => $notify
                ]
            ]);

        } catch (\Exception $e) {
            return Response()->json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
    }
}
