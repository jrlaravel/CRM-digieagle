<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company_detail extends Model
{
    protected $table = 'company_detail';
    use HasFactory;

    protected $fillable = [
        'name',
        'industry',
        'description',
        'note'  
    ];
}
