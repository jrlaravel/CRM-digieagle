<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadQuestion extends Model
{
    protected $table = 'lead_question';
    protected $fillable = ['service_name', 'question'];
}
