<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RoleMenu
 *
 * @property int $id
 * @property int|null $menu_id
 * @property int|null $role_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class RoleMenu extends Model
{
	protected $table = 'role_menu';

	protected $casts = [
		'menu_id' => 'int',
		'role_id' => 'int'
	];


    protected $guarded = [];
}
