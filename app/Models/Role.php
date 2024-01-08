<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Role extends Model
{
	protected $table = 'roles';

	protected $guarded = [];


    public function menus(){
        return $this->belongsToMany(Menu::class, 'role_menu', 'role_id', 'menu_id');
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }

}
