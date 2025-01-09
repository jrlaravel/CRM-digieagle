<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Status extends Model
{
    protected $table = 'status';
    use HasFactory;

    protected $fillable = [ 'department_id','name'];
}
