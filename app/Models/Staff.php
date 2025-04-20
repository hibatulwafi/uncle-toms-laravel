<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Staff
 * 
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $photo
 * @property string|null $specialization
 * @property int $branch_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Branch $branch
 * @property Collection|Rating[] $ratings
 *
 * @package App\Models
 */
class Staff extends Model
{
	protected $table = 'staffs';

	protected $casts = [
		'branch_id' => 'int'
	];

	protected $fillable = [
		'name',
		'email',
		'phone',
		'photo',
		'specialization',
		'branch_id'
	];

	public function branch()
	{
		return $this->belongsTo(Branch::class);
	}

	public function ratings()
	{
		return $this->hasMany(Rating::class);
	}
}
