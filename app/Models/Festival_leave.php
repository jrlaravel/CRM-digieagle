<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Festival_leave extends Model
{
    protected $table = 'fastival_leave';
    use HasFactory;

    protected $fillable = [
       'name',
       'start_date',
       'end_date'
    ];
}
