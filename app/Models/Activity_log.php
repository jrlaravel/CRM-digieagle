<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity_log extends Model
{
    protected $table = 'activity_log';
    use HasFactory;
    protected $fillable = [
        'user_id',
        'description',
        'ip_address',
        'throttle_key',
    ];
}
