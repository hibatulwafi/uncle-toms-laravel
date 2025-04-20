<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Service
 * 
 * @property int $id
 * @property string $service_name
 * @property string|null $description
 * @property float $price
 * @property int|null $duration_minutes
 * @property string|null $category
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Service extends Model
{
	protected $table = 'services';

	protected $casts = [
		'price' => 'float',
		'duration_minutes' => 'int'
	];

	protected $fillable = [
		'service_name',
		'description',
		'price',
		'duration_minutes',
		'category'
	];
}
