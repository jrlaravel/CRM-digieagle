<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeadsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Lead::select('first_name', 'last_name', 'company_name','lead_source', 'description', 'email', 'phone','city','state', 'address', 'status')->get();
    }

    /**
     * Return the headers for the columns
     * @return array
     */
    public function headings(): array
    {
        return [
            'First Name', 
            'Last Name', 
            'Company_name', 
            'Lead_source',
            'Description', 
            'Email', 
            'Phone',
            'City', 
            'State', 
            'Address', 
            'Status'
        ];
    }
}
