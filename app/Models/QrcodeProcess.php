<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrcodeProcess extends Model
{
    use HasFactory;

    protected $table = 'qrcode_processes';

    protected $fillable = [
        'branch_id',
        'member_id',
        'qrcode',
        'process_time',
        'status'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}