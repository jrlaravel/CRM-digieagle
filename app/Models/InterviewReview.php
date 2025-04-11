<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewReview extends Model
{
    protected $table = "interview_review";
    protected $fillable = ['candidate_name' , 'interviewer_name' , 'answer1' , 'answer2', 'rate' ];

}
