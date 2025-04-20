<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Rating
 * 
 * @property int $id
 * @property int $member_id
 * @property int $staff_id
 * @property int $transaction_id
 * @property int $rating
 * @property string|null $review
 * @property Carbon $rating_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Member $member
 * @property Staff $staff
 * @property Transaction $transaction
 *
 * @package App\Models
 */
class Rating extends Model
{
	protected $table = 'ratings';

	protected $casts = [
		'member_id' => 'int',
		'staff_id' => 'int',
		'transaction_id' => 'int',
		'rating' => 'int',
		'rating_date' => 'datetime'
	];

	protected $fillable = [
		'member_id',
		'staff_id',
		'transaction_id',
		'rating',
		'review',
		'rating_date'
	];

	public function member()
	{
		return $this->belongsTo(Member::class);
	}

	public function staff()
	{
		return $this->belongsTo(Staff::class);
	}

	public function transaction()
	{
		return $this->belongsTo(Transaction::class);
	}
}
