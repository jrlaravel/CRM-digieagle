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
            // Parse the dates
            $startDate = Carbon::parse($request->from);
            $endDate = Carbon::parse($request->to);
            $totalDays = $startDate->diffInDays($endDate) + 1; 
            
            // Prepare leave data
            $leaveData = [
                'leave_type_id' => $request->leave,
                'user_id' => $request->id,
                'apply_date' => Carbon::now()->format('Y-m-d'),
                'start_date' => $request->from,
                'end_date' => $request->to,
                'total_days' => $totalDays,
                'reason' => $request->reason,
                'other' => $request->other,
                'status' => '0', // Pending status
            ];
    
            // Handle PDF file upload for Sick Leave
            if ($request->hasFile('report')) {
                // Store the uploaded file in 'sick_leave_reports' folder within the 'public' disk
                $pdf = $request->file('report');
                $pdfPath = $pdf->store('sick_leave_reports', 'public'); // Store file in the 'sick_leave_reports' folder
                $leaveData['report'] = $pdfPath; // Save file path in the database
            }
            
            // Create the leave record
            Leave::create($leaveData);
    
            // Send notification or email to HR (optional)
            $user = User::find($request->id);
            $designation = Designation::find($user->designation);
    
            // Send mail to HR (optional)
            $leaveDetails = [
                'name' => $request->input('name'),
                'start_date' => $request->from,
                'end_date' => $request->to,
                'reason' => $request->reason,
                'other' => $request->other,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'designation' => $designation->name
            ];
    
            // Uncomment below to send email to HR
            // Mail::to('hr@example.com')->send(new LeaveRequestMail($leaveDetails));
            
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
