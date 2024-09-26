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

class EmployeeLeaveController extends Controller
{
    public function index()
    {
        $leaves = DB::select('SELECT name,la.id,start_date,end_date,reason,la.status FROM `leave` as la join leavetype on la.leave_type_id = leavetype.id WHERE la.user_id = '.session('employee')->id);
        $leavetype = LeaveType::all();
        $appleave = Leave::where('status', 1,'user_id = '.session('employee')->id)->count();
        $rejleave = Leave::where('status', 2,'user_id = '.session('employee')->id)->count();
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
        ]);
    
        if ($validator->passes()) { 
            // Create the leave request
            Leave::create([
                'leave_type_id' => $request->leave,
                'user_id' => $request->id,
                'apply_date' => Carbon::now()->format('Y-m-d'),
                'start_date' => $request->from,
                'end_date' => $request->to,
                'reason' => $request->reason,
                'other' => $request->other,
                'status' => '0'
            ]);
    
            // Send notification to admin
            // Notification::create([
            //     'user_id' => 1, // admin id
            //     'title' => 'New leave request',
            //     'url' => 'leave',
            //     'message' => 'A new leave request has been made by ' . $request->input('name')
            // ]);
            
            $user = User::find($request->id);
            $designation = Designation::find($user->designation);
            // Send mail to HR
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
    
            Mail::to('hr.digieagleinc@gmail.com') // Replace with HR's email address
                ->send(new LeaveRequestMail($leaveDetails));
    
            // Redirect back with success message
            return redirect()->back()->with('success', 'Leave request submitted successfully and HR has been notified.');
    
        } else {
            return redirect()->back()->withErrors($validator);
        }
    }
    

    public function delete($id)
   {
        $leave = Leave::find($id);
        $leave->delete();
        return redirect()->back()->with(['success' => 'Leave deleted successfully']);
   }
}
