<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AppVersion
 * 
 * @property int $id
 * @property string $platform
 * @property string $min_version
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class AppVersion extends Model
{
	protected $table = 'app_versions';

	protected $fillable = [
		'platform',
		'min_version'
	];
}
