<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use App\Models\Leave;
use App\Models\User;
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
        $leaves = DB::select('SELECT first_name,last_name,start_date,end_date,la.id,leavetype.name,la.reason,la.status FROM `leave` as la join leavetype on leavetype.id = la.leave_type_id join users on la.user_id = users.id');
        return view('admin/leavelist',compact('leaves'));
   }

   public function leavedelete($id)
   {    
        $leave = Leave::find($id);
        $leave->delete();
        return redirect()->back()->with(['success' => 'Leave deleted successfully']);
   }

  

    public function leaveupdate($id, $status)
    {
        $leave = Leave::find($id);
        $statusText = '';

        if ($status == 1) {
            $statusText = 'approved';
            $status = 1;

            Notification::create([
                'user_id' => $leave->user_id,
                'title' => 'Leave approved',
                'url' => 'emp/leave',
                'message' => 'Your leave has been approved.',
            ]);
        } else {
            $statusText = 'rejected';
            $status = 2;

            Notification::create([
                'user_id' => $leave->user_id,
                'title' => 'Leave rejected',
                'url' => 'emp/leave',
                'message' => 'Your leave has been rejected.',
            ]);
        }

        // Update leave status
        $leave->status = $status;
        $leave->save();

        $user  = User::find($leave->user_id);

        // Send email to employee
        Mail::to($user->email)->send(new LeaveStatusMail($leave, $statusText, $user));

        return redirect()->back()->with(['success' => 'Leave status updated successfully']);
    }

}
