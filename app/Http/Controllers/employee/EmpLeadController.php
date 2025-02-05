<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Followup;
use App\Models\ClientMeetingDetail;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;
use App\Imports\LeadDetailImport;
use App\Exports\LeadsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\LeadStatusChangedMail;
use Illuminate\Support\Facades\Mail;

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
            'phone' => 'required|numeric',
            'status' => 'required',
        ]);
        
        if ($validator->passes()) {
            // dd($request->all());
            Lead::create([
                'first_name' => $request->input('fname'),
                'company_name' => $request->input('company_name'),
                'description' => $request->input('description'),
                'lead_source' => $request->input('lead_source'), //
                'user_id' => $request->input('user_id'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'whatsappno' => $request->input('whatsappphone'),
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
            'phone' => 'required|numeric|digits:10',
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
            'lead_source' => $request->input('lead_source'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'whatsappno' => $request->input('whatsappno'), 
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
        return redirect()->back()->with('success', 'Lead deleted successfully.');
    }

    public function lead_datail($id)
    {
        $lead = Lead::find($id);
        $followups = Followup::where('lead_id', $id)->orderBy('date','desc')->get();
        return view('employee/lead-detail', compact('lead', 'followups'));  
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
    
            $adminEmails = [
                'manager.digieagleinc@gmail.com',
                'ceo.digieagleinc@gmail.com',   ];
            
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
    return redirect()->back()->with('success', 'Follow-up has been deleted successfully.');
}

public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        if(session('employee'))
        {
            $userId = session('employee')->id; // Or pass any custom user_id
        }

        // Pass the custom user_id to the import class
        Excel::import(new LeadDetailImport($userId), $request->file('excel_file'));

        return response()->json(['message' => 'Leads imported successfully.']);
    }

    public function downloadExcel()
    {
        return Excel::download(new LeadsExport, 'leads.xlsx');
    }

    public function meetingDetails()
    {
        $lead = Lead::select('id','first_name')->get();
        $meetings = DB::table('client_meeting_details')
            ->join('lead_detail', 'client_meeting_details.lead_id', '=', 'lead_detail.id')
            ->select('client_meeting_details.*', 'lead_detail.first_name')
            ->get();
        return view('employee/meeting-details',compact('lead','meetings'));
    }

    public function meetingStore(Request $request)
    {
        // dd($request->all());
        ClientMeetingDetail::create([
            'lead_id' => $request->input('lead_id'),
            'meeting_date' => $request->input('meeting_date'),
            'start_time' => $request->input('start_time'),
            'description' => $request->input('description'),
        ]);

        return response()->json(
            [
               'message' => 'Meeting details saved successfully.',
               'status' => 'success',
            ]
        );
    }

    public function meetingDelete($id)
    {

        $meeting = ClientMeetingDetail::find($id);
        $meeting->delete();
        return response()->json(
            [
               'message' => 'Meeting details deleted successfully.',
               'status' =>'success',
            ]
        );
    }

    public function meetingUpdate(Request $request)
    {
        // Find the meeting using the meeting_id from the request
        $meeting = ClientMeetingDetail::where('id', '=', $request->input('meeting_id'))->first();
    
        // If the meeting is not found, return an error
        if (!$meeting) {
            return response()->json([
                'status' => 'error',
                'message' => 'Meeting not found.',
            ], 404);
        }
    
        // Update the meeting details
        $meeting->lead_id = $request->input('lead_id');
        $meeting->meeting_date = $request->input('meeting_date');
        $meeting->start_time = $request->input('start_time');
        $meeting->description = $request->input('description');
    
        // Save the updated meeting
        $meeting->save();
    
        // Return a success response
        return response()->json([
            'status' => 'success',
            'message' => 'Meeting details updated successfully.',
        ]);
    }    
}
