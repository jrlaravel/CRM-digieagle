<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_type extends Model
{
    protected $table = 'project_type';
    use HasFactory;

    protected $fillable = [
        'name',
    ];
}
