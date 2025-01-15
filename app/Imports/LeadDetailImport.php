<?php

namespace App\Imports;

use App\Models\Lead; // Ensure you have the correct model
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadDetailImport implements ToModel, WithHeadingRow
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function model(array $row)
    {
        // Check if a lead with this email already exists
        $lead = Lead::where('company_name', $row['company_name'])->first();

        if ($lead) {
            // If lead exists, update the record
            $lead->update([
                'first_name'   => $row['first_name'],
                'last_name'    => $row['last_name'],
                'company_name' => $row['company_name'],
                'lead_source' => $row['lead_source'],
                'description'  => $row['description'],
                'user_id'      => $this->userId,    // Custom user_id passed
                'phone'        => $row['phone'],
                'whatsappno' => $row['whatsappno'],
                'city'         => $row['city'],
                'state'        => $row['state'],
                'status'       => $row['status'],
                'address'      => $row['address'],
            ]);
            return null; // Return null to avoid inserting a new record
        } else {
            // Insert new record if not found
            return new Lead([
                'first_name'   => $row['first_name'],
                'last_name'    => $row['last_name'],
                'company_name' => $row['company_name'],
                'lead_source' => $row['lead_source'],
                'description'  => $row['description'],
                'user_id'      => $this->userId,    // Custom user_id passed
                'email'        => $row['email'],
                'phone'        => $row['phone'],
                'whatsappno' => $row['whatsappno'],
                'city'         => $row['city'],
                'city'         => $row['city'],
                'state'        => $row['state'],
                'status'       => $row['status'],
                'address'      => $row['address'],
            ]);
        }
    }
}
