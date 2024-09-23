<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $table = 'lead_detail';
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'company_name',
        'description',
        'user_id',
        'email',
        'phone',
        'status',
        'address',
        'city',
        'state'
    ];
}
