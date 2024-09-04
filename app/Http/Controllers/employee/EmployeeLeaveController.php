<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveType;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;
use App\Models\Leave;
use Illuminate\Support\Facades\DB;

class EmployeeLeaveController extends Controller
{
    public function index()
    {
        $leaves = DB::select('SELECT name,la.id,start_date,end_date,reason,la.status FROM `leave` as la join leavetype on la.leave_type_id = leavetype.id WHERE la.user_id = '.session('employee')->id);
        $leavetype = LeaveType::all();
        return view('employee/addleave', compact('leavetype', 'leaves'));
    }

    public function store(Request $request)
    {
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'leave' =>'required',
            'to' => 'required',
            'from' => 'required',
            'reason' => 'required',
        ]);

        if($validator->passes()){ 

            Leave::create([
                'leave_type_id' => $request->leave,
                'user_id' => $request->id,
                'start_date' => $request->from,
                'end_date' => $request->to,
                'reason' => $request->reason,
                'status' => '0'
            ]);

            // Send notification to admin
            Notification::create([
                'user_id' => 1, // admin id
                'title' => 'New leave request',
                'url' => 'admin/leavelist',
                'message' => 'A new leave request has been made by '. $request->input('name')
            ]);

            
            // Send notification to employee
            // Notification::create([
            //     'user_id' => $request->id, // employee id
            //     'title' => 'Leave request submitted',
            //     'url' => 'emp/leave'
            //     'message' => 'Your leave request has been submitted successfully'
            // ]);

            // Redirect back with success message
            return redirect()->back()->with('success', 'Leave request submitted successfully');

            return redirect()->back()->with('success', 'Leave request submitted successfully');
        }

        return redirect()->back()->withErrors($validator);
    }

    public function delete($id)
   {
        $leave = Leave::find($id);
        $leave->delete();
        return redirect()->back()->with(['success' => 'Leave deleted successfully']);
   }
}
