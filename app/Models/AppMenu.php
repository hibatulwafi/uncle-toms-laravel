<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AppMenu
 * 
 * @property int $id
 * @property string $name
 * @property string $route
 * @property string|null $icon
 * @property int|null $order
 * @property bool|null $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class AppMenu extends Model
{
	protected $table = 'app_menu';

	protected $casts = [
		'order' => 'int',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'name',
		'route',
		'icon',
		'order',
		'is_active'
	];
}
