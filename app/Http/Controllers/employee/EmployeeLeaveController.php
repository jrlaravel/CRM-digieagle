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
use App\Jobs\SendLeaveRequestEmail;

class EmployeeLeaveController extends Controller
{
    public function index()
    {
        $leaves = DB::select('SELECT name,la.id,start_date,end_date,reason,la.status,total_days FROM `leave` as la join leavetype on la.leave_type_id = leavetype.id WHERE la.user_id = '.session('employee')->id);
        $leavetype = LeaveType::all();
        $appleave = DB::Select("SELECT COUNT(*) as appleave FROM `leave` as data join leavetype on data.leave_type_id = leavetype.id WHERE leavetype.name != 'Half day' and data.status = 1 and data.user_id = " .session('employee')->id);
        $appleave = $appleave[0]->appleave;
        $rejleave = DB::Select("SELECT COUNT(*) as rejleave FROM `leave` as data join leavetype on data.leave_type_id = leavetype.id WHERE leavetype.name != 'Half day' and data.status = 2 and data.user_id = " .session('employee')->id);
        $rejleave = $rejleave[0]->rejleave;
        $totalleave = 12;
        $pendingleave = $totalleave - $appleave;

        return view('employee/addleave', compact('leavetype', 'leaves','appleave', 'rejleave','totalleave','pendingleave'));
    }

    public function store(Request $request)
    {
        $today = Carbon::now()->format('Y-m-d');
        $startDate = Carbon::parse($request->from);
        $endDate = Carbon::parse($request->to);
        
        if ($startDate > $endDate) {
            return redirect()->back()->withErrors(['leave_date' => 'Start date cannot be after end date.']);
        }
        
        // Calculate the total days for the leave
        $totalDays = $startDate->diffInDays($endDate) + 1;
        // return $request->total_days;

        // Check for Casual Leave condition

        if($request->total_days != null)
        {
            if ($totalDays != $request->total_days) {
                return redirect()->back()->withErrors(['leave_date' => 'Invalid dates']);
            }
        }
        // Validate the request
        $validator = Validator::make($request->all(), [
            'leave' => 'required',
            'to' => 'required',
            'from' => 'required',
            'reason' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors(['leave_date' => 'Leave Application failed']);
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

        // Handle file upload for Sick Leave
        if ($request->hasFile('report')) {
            $pdf = $request->file('report');
            $pdfPath = $pdf->store('sick_leave_reports', 'public');
            $leaveData['report'] = $pdfPath;
        }

        // Create leave record
        Leave::create($leaveData);
        $user = User::find($request->id);

        Notification::create([
            'user_id' => 1,
            'title' => 'Leave Request',
            'url' => 'leave',
            'message' => $user->first_name . ' has requested leave. Reason: ' . $request->reason,
        ]);

        // Email Details
        $designation = Designation::find($user->designation);
        $leaveDetails = [
            'name' => $request->input('name'),
            'email' => $user->email,
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

        // Dispatch the email job to be processed in the background
        dispatch(new SendLeaveRequestEmail($leaveDetails, $mailRecipients));

        return redirect()->back()->with('success', 'Leave request submitted successfully. HR will be notified shortly.');
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
