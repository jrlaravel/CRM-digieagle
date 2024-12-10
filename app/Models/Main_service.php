<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Main_service extends Model
{
    protected $table ='main_service';
    use HasFactory;
    protected $fillable = [
        'main_service',
    ];
}
