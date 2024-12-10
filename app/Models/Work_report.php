<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work_report extends Model
{
    protected $table ='work_report';
    use HasFactory;
    protected $fillable = [
        'user_id',
        'date'
    ];
}
