<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadAnswer extends Model
{
    protected $table = 'lead_answer_detail';
    protected $fillable = ['lead_id', 'lead_question_id', 'answer'];
}
