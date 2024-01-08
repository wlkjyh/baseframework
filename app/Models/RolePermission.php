<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RolePermission
 *
 * @property int $id
 * @property int|null $role_id
 * @property int|null $permission_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class RolePermission extends Model
{
	protected $table = 'role_permission';

	protected $casts = [
		'role_id' => 'int',
		'permission_id' => 'int'
	];


    protected $guarded = [];
}
