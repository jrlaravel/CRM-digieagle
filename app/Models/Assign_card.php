<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assign_card extends Model
{
    protected $table = 'assign_card';
    use HasFactory;
    protected $fillable = [
        'card_id',
        'user_id',
        'message',
        'date'
    ];
}
