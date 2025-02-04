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
        'company_name',
        'lead_source',
        'description',
        'user_id',
        'email',
        'phone',
        'whatsappno',
        'lead_source',
        'status',
        'address',
        'city',
        'state',
        'inslink',
        'facebooklink',
        'weblink'
    ];
}
