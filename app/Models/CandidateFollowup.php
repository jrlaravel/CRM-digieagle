<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateFollowup extends Model
{
    protected $table = 'candidate_follow_up';

    protected $fillable = ['candidate_id', 'follow_up'];
}
