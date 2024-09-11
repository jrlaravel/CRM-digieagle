<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_detail extends Model
{
    protected $table = 'project_detail';
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'project_type',
        'platform',
        'start_date',
        'target_audience_age',
        'target_city',
        'deadline',
        'status',
    ];
}
