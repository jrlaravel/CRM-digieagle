<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use App\Models\Leave;
use App\Models\User;
use App\Models\Festival_leave;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Mail\LeaveStatusMail;
use Illuminate\Support\Facades\Mail;


class LeaveController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::all();
        return view('admin/leavetype', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        // Check if this is an update
        if ($request->leavetype_id) {
            // Update existing leave type
            $leaveType = LeaveType::find($request->leavetype_id);
            if ($leaveType) {
                $leaveType->name = $request->name;
                $leaveType->description = $request->description;
                $leaveType->save();
                return back()->with('success', 'Leave Type updated successfully.');
            }
        } else {
            // Create new leave type
            LeaveType::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            return back()->with('success', 'Leave Type added successfully.');
        }
    }
    
   public function delete($id)
   {
        $leaveType = LeaveType::find($id);
        $leaveType->delete();
        return redirect()->back()->with(['success' => 'Leave Type deleted successfully']);
   }

   public function leave()
   {
        $leaves = DB::select('SELECT first_name,last_name,start_date,end_date,total_days,la.id,leavetype.name,la.reason,la.status,la.report FROM `leave` as la join leavetype on leavetype.id = la.leave_type_id join users on la.user_id = users.id');
        return view('admin/leavelist',compact('leaves'));
   }

   public function leavedelete($id)
   {    
        $leave = Leave::find($id);
        $leave->delete();
        return redirect()->back()->with(['success' => 'Leave deleted successfully']);
   }

   public function leaveupdate(Request $request, $id)
   {
       $leave = Leave::find($id);
       $statusText = '';
       $reason = $request->input('reason'); // Get the reason from the request
   
       // Retrieve status from the request
       $status = $request->input('status');
   
       if ($status == 1) {
           // Approve the leave
           $statusText = 'approved';
           $leave->status = 1;
   
           Notification::create([
               'user_id' => $leave->user_id,
               'title' => 'Leave approved',
               'url' => 'leave',
               'message' => 'Your leave has been approved. Reason: ' . $reason,
           ]);
       } else {
           // Reject the leave
           $statusText = 'rejected';
           $leave->status = 2;
   
           Notification::create([
               'user_id' => $leave->user_id,
               'title' => 'Leave rejected',
               'url' => 'leave',
               'message' => 'Your leave has been rejected. Reason: ' . $reason,
           ]);
       }
   
       // Update leave status
       $leave->save();
   
       $user  = User::find($leave->user_id);
   
       // Send email to employee, passing the reason
       Mail::to($user->email)->send(new LeaveStatusMail($leave, $statusText, $user, $reason));
   
       return redirect()->back()->with(['success' => 'Leave status updated successfully']);
   }
   
   

    public function festival_leave()
    {
        $festivalleaves = Festival_leave::all();
        return view('admin/festival_leave', compact('festivalleaves'));
    }

    public function festival_leave_create(Request $request)
    {
        Festival_leave::create([
            'name' => $request->name,
            'start_date' => $request->startdate,
            'end_date' => $request->enddate
        ]);

        return redirect()->back()->with('success', 'Festival Leave added successfully.');
    }

    public function festival_leave_delete($id)
    {
        $festivalLeave = Festival_leave::find($id);
        $festivalLeave->delete();
        return redirect()->back()->with(['success' => 'Festival Leave deleted successfully']);
    }
    public function festival_leave_update(Request $request)
{
    // Validate request
    $request->validate([
        'name' => 'required|string|max:255',
        'startdate' => 'required|date',
        'enddate' => 'required|date',
    ]);

    // Find the festival leave by ID and update the fields
    $festivalLeave = Festival_leave::find($request->id);
    if ($festivalLeave) {
        $festivalLeave->name = $request->input('name');
        $festivalLeave->start_date = $request->input('startdate');
        $festivalLeave->end_date = $request->input('enddate');
        $festivalLeave->save();

        // Redirect with success message
        return redirect()->back()->with('success', 'Festival leave updated successfully');
    }

    return redirect()->back()->with('error', 'Festival leave not found');
}


}
