<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work_report_detail extends Model
{   
    protected $table ='work_report_detail';
    use HasFactory;
    protected $fillable = [
        'date_id',
        'company_id',
        'service_id',
        'status',
        'note',
        'start_time',
        'end_time',
        'total_time',
        'user_id'
    ];
}
