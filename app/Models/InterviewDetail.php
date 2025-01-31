<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewDetail extends Model
{
    use HasFactory;

    protected $table = 'interview_details';

    // Mass assignable attributes
    protected $fillable = [
        'candidate_id',
        'interview_type',
        'interview_date',
        'interview_time',
    ];
}
