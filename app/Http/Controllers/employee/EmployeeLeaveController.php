<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveType;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Mail\LeaveRequestMail;
use App\Models\Designation;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EmployeeLeaveController extends Controller
{
    public function index()
    {
        $leaves = DB::select('SELECT name,la.id,start_date,end_date,reason,la.status,total_days FROM `leave` as la join leavetype on la.leave_type_id = leavetype.id WHERE la.user_id = '.session('employee')->id);
        $leavetype = LeaveType::all();
        $appleave = Leave::where('status', 1)->where('user_id', session('employee')->id)->count();
        $rejleave = Leave::where('status', 2)->where('user_id', session('employee')->id)->count();   
        $totalleave = 12;
        $pendingleave = $totalleave - $appleave;

        return view('employee/addleave', compact('leavetype', 'leaves','appleave', 'rejleave','totalleave','pendingleave'));
    }

    public function store(Request $request)
    {
        $today = Carbon::now()->format('Y-m-d');
        $startDate = Carbon::parse($request->from);
        $endDate = Carbon::parse($request->to);
        
        // Calculate the total days for the leave
        $totalDays = $startDate->diffInDays($endDate) + 1; 
        
        // Apply the 2x rule to calculate the minimum date from startDate
        $minDateFromStart = $startDate->subDays(($totalDays * 2) + 1)->format('Y-m-d');
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'leave' => 'required',
            'to' => 'required',
            'from' => 'required',
            'reason' => 'required',
            // 'report' => 'nullable|file|mimes:pdf|max:10240', // Validate PDF file and limit size to 10MB
        ]);
        
        // Check if validation passes
        if ($validator->passes()) { 
            
            // If the leave type is Casual Leave, perform the check
            $data = LeaveType::find($request->leave);
            // dd($data);
            if ($data->name == 'Casual Leave') {
                // Check if today's date is either on or after the calculated min date
                if ($today > $minDateFromStart) {
                    Log::info("Leave request is rejected: Apply at least " . ($totalDays * 2) . " days in advance.");
                    return redirect()->back()->withErrors(['leave_date' => 'You must apply for leave at least ' . ($totalDays * 2) . ' days in advance.']);
                } else {
                    Log::info("Leave request is accepted.");
                }                
                
            }
            // Prepare leave data
            $leaveData = [
                'leave_type_id' => $request->leave,
                'user_id' => $request->id,
                'apply_date' => $today,
                'start_date' => $request->from,
                'end_date' => $request->to,
                'total_days' => $totalDays,
                'reason' => $request->reason,
                'other' => $request->other,
                'status' => '0', // Pending status
            ];

            // Handle PDF file upload for Sick Leave
            if ($request->hasFile('report')) {
                $pdf = $request->file('report');
                $pdfPath = $pdf->store('sick_leave_reports', 'public');
                $leaveData['report'] = $pdfPath;
            }
            
            // Create the leave record
            Leave::create($leaveData);

            // Send notification or email to HR (optional)
            $user = User::find($request->id);
            $designation = Designation::find($user->designation);
            $email = $user->email;

            // Send mail to HR (optional)
            $leaveDetails = [
                'name' => $request->input('name'),
                'email' => $email,
                'start_date' => $request->from,
                'end_date' => $request->to,
                'reason' => $request->reason,
                'other' => $request->other,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'designation' => $designation->name
            ];

            $mailRecipients = [
                'hr.digieagleinc@gmail.com',
                'ceo.digieagleinc@gmail.com',
                'manager.digieagleinc@gmail.com',
            ];
            
            Mail::to($mailRecipients)->send(new LeaveRequestMail($leaveDetails));
            
            
            // Redirect back with success message
            return redirect()->back()->with('success', 'Leave request submitted successfully and HR has been notified.');
        
        } else {
            // If validation fails, redirect back with errors
            return redirect()->back()->withErrors($validator);
        }
    }

    
    
    public function delete($id)
    {
        // Find the leave record by ID
        $leave = Leave::find($id);
    
        if ($leave && $leave->report) {
            // Delete the file from the 'sick_leave_reports' folder in the public storage
            $filePath = 'sick_leave_reports/' . $leave->report;
            if (Storage::disk('public')->exists($filePath)) {
                // Delete the file
                Storage::disk('public')->delete($filePath);
            }
        }
    
        $leave->delete();
    
        return redirect()->back()->with(['success' => 'Leave deleted successfully']);
    }
    
    
}
