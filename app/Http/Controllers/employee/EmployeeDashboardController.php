<?php

namespace App\Http\Controllers\employee;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Services\ETimeOfficeService;
use Carbon\Carbon;
use App\Models\Notification;
use App\Models\Festival_leave;


class EmployeeDashboardController extends Controller
{
    public function __construct(ETimeOfficeService $eTimeOfficeService)
    {
        $this->eTimeOfficeService = $eTimeOfficeService;
    }

    public function index()
    {
        $code = session('employee')->empcode;
        $fromDate = Carbon::now()->startOfMonth()->format('d/m/Y');
        $toDate = Carbon::now()->endOfMonth()->format('d/m/Y');
        

        $attendanceData = $this->eTimeOfficeService->getInOutPunchData($code, $fromDate, $toDate);

        $collection = collect($attendanceData['InOutPunchData']);
        
        $presentDaysCount = $collection->where('Status', 'P')->count();
        $absentDaysCount = $collection->where('Status', 'A')->count();

        if(session('has_bde_features')){
            $follow_ups = DB::table('follow_up')
            ->join('lead_detail', 'lead_detail.id', '=', 'follow_up.lead_id')
            ->select('lead_detail.first_name', 'lead_detail.id as lead_id', 'lead_detail.status','follow_up.id as follow_id', 'lead_detail.company_name', 'lead_detail.phone', 'lead_detail.last_name', 'follow_up.call_date')
            ->where(DB::raw('DATE(follow_up.call_date)'), '=', DB::raw('CURDATE()')) // Use CURDATE() for today's date
            ->get();
            $cards = DB::select('SELECT card.name,card.image,ac.message,ac.date FROM `assign_card` as ac join card on ac.card_id = card.id WHERE ac.user_id = '.session('employee')->id);
            return view('employee.employeedashboard',compact('presentDaysCount', 'absentDaysCount','cards','follow_ups'));
        }

        $cards = DB::select('SELECT card.name,card.image,ac.message,ac.date FROM `assign_card` as ac join card on ac.card_id = card.id WHERE ac.user_id = '.session('employee')->id);
        return view('employee.employeedashboard',compact('presentDaysCount', 'absentDaysCount','cards'));
    }

    public function profile()
    {
        $data = DB::select('SELECT users.id,users.birth_Date,users.profile_photo_path,users.first_name,users.last_name,dep.name as depname,users.username,users.skills,des.name as desname,users.phone,users.email,users.address FROM `users` join department as dep on users.department = dep.id join designation as des on des.id = users.designation where users.id = '.session('employee')->id);
        // return $data;
        return view('employee.profile',compact('data'));
    }

    public function updateProfile(Request $request)
    {
        $data = User::find($request->id);
        // $validator = Validator::make($request->all(), [
        //     'fname' => 'required|string|max:20',
        //     'lname' => 'required|string|max:20',
        //     // 'username' => 'required|unique:users,username', 
        //     'phone' => 'required|numeric|digits:10',
        //     'address' => 'required|string|max:255', 
        //     'birth_date' => 'required|date',
        // ]);

        // if ($validator->fails()) {
        //     return redirect()->route('emp/profile')
        //     ->withErrors($validator)
        //     ->withInput();
        // }
        // else{
            // return $data;
            $data->first_name = $request->input('fname');
            $data->last_name = $request->input('lname');
            $data->phone = $request->input('phone');
            $data->address = $request->input('address');
            $data->username = $request->input('username');  
            $data->birth_date = $request->input('birthdate');
            $data->save();
        // }
        
        return redirect()->route('emp/profile'); 
    }

    public function attendance() {
       $data = User::where('id', session('employee')->id)->pluck('empcode')->first();
        return view('employee/attendance',compact('data'));
    }

    public function profilePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();   
        }
        
            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('profile_photos', $filename, 'public');
                $user =  User::find(session('employee')->id);
                $user->profile_photo_path = $filename;
                $user->save();
            }

            return back()->with('success', 'Profile photo updated successfully.');
    }

    public function notification()
    {
        // Fetch unread notifications for the authenticated user
        $notifications = Notification::where('user_id', session('employee')->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response($notifications);
    }

    public function calendar()
    {
        $leave = Festival_leave::all();
        $data = DB::select('SELECT concat(first_name) as name, DATE_FORMAT(birth_date, "%d-%m") AS start FROM users');
        return view('employee/calendar',compact('data','leave'));
    }
}
