<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $table = 'leavetype';
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];
}
