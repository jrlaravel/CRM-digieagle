<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{

    protected $table = 'leave';
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'leave_type_id',
        'apply_date',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'other',
        'status'
    ];
}
