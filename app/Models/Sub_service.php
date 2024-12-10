<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sub_service extends Model
{
    use HasFactory;
    protected $table = 'sub_service';
    protected $fillable = [
        'main_service_id',
        'sub_service',
    ];
}
