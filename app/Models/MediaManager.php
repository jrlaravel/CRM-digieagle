<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaManager extends Model
{
    protected $table ='media_manager';
    protected $fillable = ['path'];
}
