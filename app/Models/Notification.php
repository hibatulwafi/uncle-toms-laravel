<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Notification
 * 
 * @property int $id
 * @property string|null $title
 * @property string|null $message
 * @property string|null $type
 * @property bool|null $is_broadcast
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Member[] $members
 *
 * @package App\Models
 */
class Notification extends Model
{
	protected $table = 'notifications';

	protected $casts = [
		'is_broadcast' => 'bool'
	];

	protected $fillable = [
		'title',
		'message',
		'type',
		'is_broadcast'
	];

	public function members()
	{
		return $this->belongsToMany(Member::class, 'notification_member')
					->withPivot('id', 'is_read');
	}
}
