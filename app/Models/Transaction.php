<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 * 
 * @property int $id
 * @property int $member_id
 * @property int $branch_id
 * @property Carbon $transaction_date
 * @property string $service_type
 * @property float $price
 * @property float|null $discount
 * @property int|null $points_earned
 * @property string|null $payment_method
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Member $member
 * @property Branch $branch
 * @property Collection|Rating[] $ratings
 *
 * @package App\Models
 */
class Transaction extends Model
{
	protected $table = 'transactions';

	protected $casts = [
		'member_id' => 'int',
		'branch_id' => 'int',
		'transaction_date' => 'datetime',
		'price' => 'float',
		'discount' => 'float',
		'final_price' => 'float',
		'points_earned' => 'int'
	];

	protected $fillable = [
		'member_id',
		'branch_id',
		'transaction_date',
		'service_type',
		'price',
		'discount',
		'final_price',
		'points_earned',
		'payment_method',
		'notes'
	];

	public function member()
	{
		return $this->belongsTo(Member::class);
	}

	public function branch()
	{
		return $this->belongsTo(Branch::class);
	}

	public function ratings()
	{
		return $this->hasMany(Rating::class);
	}
}
