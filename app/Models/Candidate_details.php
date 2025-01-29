<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Candidate_details extends Model
{
    protected $table = 'candidate_details';
    use HasFactory;
    protected $fillable = [
        'name',
        'assign_to',
        'email',
        'phone',
        'address',
        'designation',
        'experience',
        'reference_name',
        'reference_phone',
        'organization_name',
        'position_name',
        'notice_period',
        'expected_date',
        'current_ctc',
        'expected_ctc',
        'strengths',
        'weaknesses',
        'career_goal',
        'position_responsibilities',
        'areas_of_expertise',
        'improve_your_knowledge',
        'service_are_we_providing',
        'reason_for_leaving',
        'reason_for_applying',
    ];
}
