<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvDetail extends Model
{
    use HasFactory;

    // Table name (optional, Laravel auto-detects)
    protected $table = 'cv_details';

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'phone',
        'designation',
        'notice_period',
        'experience',
        'current_ctc',
        'expected_ctc',
        'cv_path',
        'source',
    ];

    // Cast attributes to correct data types
    protected $casts = [
        'phone' => 'integer',
        'experience' => 'integer',
        'current_ctc' => 'integer',
        'expected_ctc' => 'integer',
    ];
}
