<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Booking
 * 
 * @property int $id
 * @property int|null $member_id
 * @property int|null $service_id
 * @property int|null $branch_id
 * @property int|null $staff_id
 * @property Carbon|null $booking_date
 * @property Carbon|null $booking_time
 * @property string|null $status
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Member|null $member
 * @property Service|null $service
 * @property Branch|null $branch
 * @property Staff|null $staff
 *
 * @package App\Models
 */
class Booking extends Model
{
	protected $table = 'bookings';

	protected $casts = [
		'member_id' => 'int',
		'service_id' => 'int',
		'branch_id' => 'int',
		'staff_id' => 'int',
		'booking_date' => 'datetime',
		'booking_time' => 'datetime'
	];

	protected $fillable = [
		'member_id',
		'service_id',
		'branch_id',
		'staff_id',
		'booking_date',
		'booking_time',
		'status',
		'notes'
	];

	public function member()
	{
		return $this->belongsTo(Member::class);
	}

	public function service()
	{
		return $this->belongsTo(Service::class);
	}

	public function branch()
	{
		return $this->belongsTo(Branch::class);
	}

	public function staff()
	{
		return $this->belongsTo(Staff::class);
	}
}
