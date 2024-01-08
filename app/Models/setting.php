<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 *
 * @property string $k
 * @property string $v
 * @property string|null $desc
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Setting extends Model
{
	protected $table = 'settings';
	protected $primaryKey = 'k';

	public $incrementing = false;
    protected $guarded = [];
}
