<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = 'card';
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image'
    ];
}
