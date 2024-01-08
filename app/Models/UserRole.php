<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserRole
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $role_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class UserRole extends Model
{
	protected $table = 'user_role';

	protected $casts = [
		'user_id' => 'int',
		'role_id' => 'int'
	];


    protected $guarded = [];
}
