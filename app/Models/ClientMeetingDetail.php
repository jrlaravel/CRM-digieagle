<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientMeetingDetail extends Model
{
    use HasFactory;

    protected $table = 'client_meeting_details'; // Table name

    protected $fillable = [
        'lead_id',
        'description',
        'meeting_date',
        'start_time',
    ];

    // Relationship with Lead model
    public function lead()
    {
        return $this->belongsTo(LeadDetail::class, 'lead_id');
    }
}
