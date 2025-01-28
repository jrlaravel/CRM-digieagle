<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostingAndDomain extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural of the model name
    protected $table = 'hosting_and_domain';

    // Define the fillable properties for mass assignment
    protected $fillable = [
        'client_name',
        'logo',
        'domain_name',
        'domain_purchase_from',
        'domain_purchase_date',
        'domain_expire_date',
        'domain_amount',
        'domain_email',
        'domain_id',
        'domain_password',
        'hosting_purchase_from',
        'hosting_link',
        'hosting_amount',
        'hosting_purchase_date',
        'hosting_expire_date',
        'hosting_email',
        'hosting_id',
        'hosting_password',
    ];

    // Optionally, define the date fields for casting
    protected $dates = [
        'domain_purchase_date',
        'domain_expire_date',
        'hosting_purchase_date',
        'hosting_expire_date',
    ];
}
