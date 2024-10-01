<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Followup;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;

class EmpLeadController extends Controller
{
    public function index(){
        $user = DB::select("SELECT first_name,last_name,users.id FROM `users` join designation as des on users.designation = des.id where des.name =  'BDE'");
        return view('employee/add_lead_detail',compact('user'));
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
            // dd($request->all());
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
            ]);
            
            return redirect()->route('emp/lead-list')->with('success', 'Lead created successfully.');
        } 
        else {
            // Validation failed, redirect back with errors and input
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }
    
    public function show()
    {
        $data = Lead::get();
        return view('employee/lead-list',compact('data'));
    }

    public function update(Request $request)
    {
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
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Lead updated successfully.');
    }


    public function delete($id)
    {
        $lead = Lead::find($id);
        $lead->delete();
        return redirect()->route('emp/lead');
    }

    public function lead_datail($id)
    {
        $lead = Lead::find($id);
        $followups = Followup::where('lead_id', $id)->orderBy('date','desc')->get();
        return view('employee/lead-detail', compact('lead', 'followups'));  
    }


    public function createOrUpdateFollowup(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'title'   => 'required',
            'message' => 'required',
            'date'    => 'required',
            'status'  => 'required',
        ]);
    
        // If validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Fetch the lead and previous status
        $lead = Lead::find($request->lead_id);
        if (!$lead) {
            return redirect()->back()->with('error', 'Lead not found.');
        }
        
        $previousStatus = $lead->status;
        
        // Update or create the followup record
        $followup = Followup::updateOrCreate(
            ['id' => $request->id], // Match by 'id' to update, or create a new one
            [
                'title'           => $request->input('title'),
                'lead_id'         => $request->input('lead_id'),
                'message'         => $request->input('message'),
                'date'            => $request->input('date'),
                'previous_status' => $previousStatus,
            ]
        );
    
        // Update the lead's status if follow-up is successfully created or updated
        if ($followup) {
            $lead->status = $request->status; 
            $lead->save();
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
    return redirect()->back()->with('success', 'Follow-up has been deleted successfully.');
}

}
