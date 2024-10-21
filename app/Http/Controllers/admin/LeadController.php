<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Imports\LeadDetailImport;
use App\Exports\LeadsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Followup;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use App\Mail\LeadStatusChangedMail;
use Illuminate\Support\Facades\Mail;

class LeadController extends Controller
{
    public function index(){
        $user = DB::select("SELECT first_name,last_name,users.id FROM `users` join designation as des on users.designation = des.id where des.name =  'BDE'");
        return view('admin/add_lead_detail',compact('user'));
    }

    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'user_id' => 'required',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'status' => 'required',
            'address' => 'required|string|max:500',
        ]);
        
        if ($validator->passes()) {
            Lead::create([
                'first_name' => $request->input('fname'),
                'last_name' => $request->input('lname'),
                'company_name' => $request->input('company_name'),
                'description' => $request->input('description'),
                'user_id' => $request->input('user_id'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'city' => $request->input('city'),
                'state' => $request->input('state'),
                'status' => $request->input('status'),
                'address' => $request->input('address'),
                'inslink' => $request->input('inslink'), // Instagram link
                'facebooklink' => $request->input('facebooklink'), // Facebook link
                'weblink' => $request->input('weblink'), // Website link
                'lead_source' => $request->input('lead_source'), //
            ]);
            
            return redirect()->route('admin/lead-list')->with('success', 'Lead created successfully.');
        } 
        else {
            // Validation failed, redirect back with errors and input
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }
    
    public function show()
    {
        $data = Lead::get();
        return view('admin/lead-list',compact('data'));
    }

    public function update(Request $request)
    {
        // return $request->all();
        // Validation rules
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email' => 'required|email',
            'phone' => 'required|numeric|digits:10',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'status' => 'required',
        ]);

        // If validation fa ils
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Find the lead by ID
        $lead = Lead::findOrFail($request->id);

        // Update the lead data
        $lead->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'company_name' => $request->input('company_name'),
            'description' => $request->input('description'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'address' => $request->input('address'),
            'status' => $request->input('status'),
            'inslink' => $request->input('instagram'), // Instagram link
            'facebooklink' => $request->input('facebook'), // Facebook link
            'weblink' => $request->input('website'), // Website link
            'lead_source' => $request->input('lead_source'), //
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Lead updated successfully.');
    }


    public function delete($id)
    {
        $lead = Lead::find($id);
        $lead->delete();
        return redirect()->route('admin/lead-list');
    }

    public function lead_datail($id)
    {
        $lead = Lead::find($id);
        $followups = Followup::where('lead_id', $id)->orderBy('date','desc')->get();
        return view('admin/lead-detail', compact('lead', 'followups'));  
    }

    public function createOrUpdateFollowup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'required',
            'message' => 'required',
            'date'    => 'required|date', 
            'status'  => 'required',
            'call_date' => 'nullable|date', 
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $lead = Lead::find($request->lead_id);
        if (!$lead) {
            return redirect()->back()->with('error', 'Lead not found.');
        }
        // return $request->all();
        
        $previousStatus = $lead->status;
        $companyName = $lead->company_name; 
        $callDate = $request->call_date; 
        
        $followup = Followup::updateOrCreate(
            ['id' => $request->id], 
            [
                'title'           => $request->input('title'),
                'lead_id'         => $request->input('lead_id'),
                'message'         => $request->input('message'),
                'date'            => $request->input('date'),
                'previous_status' => $previousStatus,
                'call_date'       => $request->input('call_date'), 
            ]
        );
    
        if ($followup) {
            $newStatus = $request->status;
            $lead->status = $newStatus;
            $lead->save();
    
            $adminEmails = ['nilay.chotaliya119538@marwadiuniversity.ac.in'];
            
            if ($previousStatus != $newStatus) {
                Mail::to($adminEmails)->send(new LeadStatusChangedMail(
                    $lead, 
                    $previousStatus, 
                    $newStatus, 
                    $followup->message, 
                    $companyName,
                    $callDate
                ));
            }
        }
    
        // Return success response
        return redirect()->back()->with('success', 'Follow-up has been successfully ' . ($request->has('id') ? 'updated' : 'added') . '.');
    }
        

public function delete_followup($id)
{
    $followup = Followup::find($id);
    $status = $followup->previous_status;
    $data = Lead::find($followup->lead_id);
    $data->status = $status;
    $data->save();
    $followup->delete();
    return redirect()->back();
}
public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        if(session('user'))
        {
            $userId = session('user')->id; // Or pass any custom user_id
        }

        // Pass the custom user_id to the import class
        Excel::import(new LeadDetailImport($userId), $request->file('excel_file'));

        return response()->json(['message' => 'Leads imported successfully.']);
    }

    public function downloadExcel()
    {
        return Excel::download(new LeadsExport, 'leads.xlsx');
    }
}
