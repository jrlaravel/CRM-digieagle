<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Followup extends Model
{
    protected $table = 'follow_up';
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'lead_id',
        'date',
        'previous_status',
    ];
}
