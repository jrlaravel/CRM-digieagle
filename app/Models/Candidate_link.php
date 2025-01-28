<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate_link extends Model
{
    protected $table = 'candidate_link';
    protected $fillable = ['name','token', 'link'];
}
