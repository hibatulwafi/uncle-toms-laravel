<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Faq
 * 
 * @property int $id
 * @property string $question
 * @property string $answer
 * @property string|null $category
 * @property bool $is_active
 * @property int|null $order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Faq extends Model
{
	protected $table = 'faqs';

	protected $casts = [
		'is_active' => 'bool',
		'order' => 'int'
	];

	protected $fillable = [
		'question',
		'answer',
		'category',
		'is_active',
		'order'
	];
}
