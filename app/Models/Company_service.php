<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company_service extends Model
{
    protected $table = 'company_services';
    use HasFactory;
    protected $fillable = [
        'company_id',
        'service_id',
    ];
}