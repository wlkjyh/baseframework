<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Menu
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $parent_id
 * @property string|null $path
 * @property string|null $icon
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Menu extends Model
{
	protected $table = 'menus';

	protected $casts = [
		'parent_id' => 'int'
	];


    protected $guarded = [];

    public static function getUserMenu($user_instance)
    {
    //     id parent_id name path icon
        if($user_instance->superAdmin()){
            $menus = Menu::get();
        }else{
            $menus = $user_instance->roles()->with('menus')->get()->pluck('menus')->collapse()->unique('id');
        }
        $menus = $menus->toArray();
        $menus = array_map(function ($menu){
            return [
                'id' => $menu['id'],
                'parent_id' => $menu['parent_id'],
                'name' => $menu['name'],
                'path' => $menu['path'],
                'icon' => $menu['icon'],
            ];
        }, $menus);
        return $menus;
    }

    public static function getTrees()
    {
    //    id Pid name
        $menus = [];
    //    第一层的Pid是0，后面层次的Pid是上一层的id
        $first = Menu::get();
        foreach ($first as $key => $val) {
            $menus[] = [
                'id' => $val->id,
                'pId' => $val->parent_id,
                'name' => $val->name,
                'open' => true,
            ];
        }

        return $menus;

    }

    public static function deleteAndChildren($id)
    {
        $menu = Menu::find($id);
        if ($menu) {
            $menu->delete();
            Menu::where('parent_id', $id)->delete();
        }
    }
}
