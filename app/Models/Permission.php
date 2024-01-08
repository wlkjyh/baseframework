<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $uri
 * @property array|null $methods
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Permission extends Model
{
	protected $table = 'permissions';

	protected $casts = [
		'methods' => 'json',

	];


    protected $guarded = [];

    public function roles(){
        return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id');
    }
}
