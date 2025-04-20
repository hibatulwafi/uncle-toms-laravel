<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Onboarding
 * 
 * @property int $id
 * @property int $index
 * @property string|null $img
 * @property string|null $deskripsi
 * @property string|null $title
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Onboarding extends Model
{
	protected $table = 'onboarding';

	protected $casts = [
		'index' => 'int',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'index',
		'img',
		'deskripsi',
		'title',
		'is_active'
	];
}
