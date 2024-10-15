<?php

namespace App\Imports;

use App\Models\Lead; // Ensure you have the correct model
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadDetailImport implements ToModel, WithHeadingRow
{
    protected $userId;

    // Add a constructor to accept a custom user_id
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function model(array $row)
    {
        return new Lead([
            'first_name'   => $row['first_name'],   // Ensure these match your Excel column headers
            'last_name'    => $row['last_name'],    
            'company_name' => $row['company_name'], 
            'description'  => $row['description'],   
            'user_id'      => $this->userId,        // Set custom user_id here
            'email'        => $row['email'],         
            'phone'        => $row['phone'],         
            'city'         => $row['city'],          
            'state'        => $row['state'],         
            'address'      => $row['address'],       
            'status'       => $row['status'],        
        ]);
    }
}
