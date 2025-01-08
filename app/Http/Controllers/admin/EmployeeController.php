<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Notification;
use App\Models\Festival_leave;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;

class EmployeeController extends Controller
{
    public function index(){
        $department = DB::select('SELECT DISTINCT name,id FROM `department` WHERE status = 1;');
        return view('admin.add-emp',compact('department'));
    }

    public function getDesignations(Request $request)
    {
        $designations = Designation::where('department_id', $request->department_id)->get();
        return response()->json($designations);
    }

    public function store(Request $request)
{
    // Validation rules
    $validator = Validator::make($request->all(), [
        'fname' => 'required|string|max:20',
        'lname' => 'required|string|max:20',
        'email' => 'required|email|unique:users,email',
        'birthdate' => 'required|date',
        'empcode' => 'required|numeric|digits:4|unique:users,empcode',
        'username' => 'required|unique:users,username',
        'phone' => 'required|numeric|digits:10',
        'department' => 'required',
        'designation' => 'required',
        'address' => 'required|string|max:255',
        'password' => 'required|string|min:6',
        'skills' => 'nullable|array',
        'skills.*' => 'string|max:50',
    ]);
    
    if ($validator->passes()) {
        $skills = $request->input('skills', []);
                                                                                                                                      
        if (is_string($skills)) {
            $skillsArray = array_map('trim', explode(',', $skills));
        } elseif (is_array($skills)) {
            $skillsArray = $skills;
        } else {
            $skillsArray = [];
        }

        User::create([
            'first_name' => $request->input('fname'),
            'last_name' => $request->input('lname'),
            'username' => $request->input('username'),
            'birth_date' => $request->input('birthdate'), // Note the corrected field name
            'empcode' => $request->input('empcode'),
            'role' => 'employee',
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'skills' => json_encode($skillsArray), // Encode skills array as JSON
            'department' => $request->input('department'),
            'designation' => $request->input('designation'),
            'address' => $request->input('address'),
            'password' => Hash::make($request->input('password')),
            'profile' => null,
        ]);
        
            Notification::create([
                'user_id' => session('user')->id,
                'title' => 'Employee added',
                'url' => 'list-emp',
                'message' => 'A new employee has been added: '. $request->input('fname').' '. $request->input('lname'),
            ]);

            return redirect()->route('admin/list-emp')->with(['success' => 'Employee added successfully']);
    } 
    else {
        // Validation failed, redirect back with errors and input
        return redirect()->route('admin/add-emp')->withInput()->withErrors($validator);
    }
}



    public function show(){
        
        $employees = DB::select('SELECT users.id as uid,users.first_name,users.last_name,users.username,users.birth_date,users.email,users.phone,users.address,dep.name as depname,users.empcode as code,dep.id as depid,des.id as desid,des.name as desname FROM `users` join department as dep on users.department = dep.id join designation as des on users.designation = des.id;');
        $department = Department::all();
        $designation = Designation::all();
        return view('admin.list-emp', compact('employees','department','designation'));
    }

    public function edit($id){

        $data = User::find($id);
        return response($data);
    }

    public function update(Request $request){
        
        $user = User::find($request->id);
        if($user->role == 'admin'){
            $user->first_name = $request->input('fname');
            $user->last_name = $request->input('lname');
            $user->phone = $request->input('phone');
            $user->address = $request->input('address');
            $user->username = $request->input('username');
            if($user->save())
            {
                return redirect()->route('admin/profile');
            }
            else{
                echo 'error';
            }
        }

       
        $user->first_name = $request->input('fname');
        $user->last_name = $request->input('lname');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->birth_date = $request->input('date');
        $user->designation = $request->input('designation');
        $user->department = $request->input('department');
        $user->empcode = $request->input('code');
        $user->save();

        return redirect()->route('admin/list-emp');  
    
    }

    public function delete($id){
        $employee = User::find($id);
        $employee->delete();
        return redirect()->route('admin/list-emp');
    }

    public function calender(){
        $data = DB::select('SELECT concat(first_name) as name, DATE_FORMAT(birth_date, "%d-%m") AS start FROM users');
        $leave = DB::select('SELECT first_name,start_date,end_date,reason FROM `leave` as la join users on la.user_id = users.id WHERE status = 1');
        $festivalleave = Festival_leave::all();
        return view('admin/calendar',compact('data','leave','festivalleave'));
    }

    public function mail(){
        return view('admin/birthdayReminder');
}

    public function work_report()
    {
        $data = DB::select('SELECT id,first_name,last_name FROM users where role = '."'employee'");
        return view('admin/work-report',compact('data'));
    }

    public function get_work_report(Request $request)
    {
        
        // Custom validation for the start and end dates
        $validator = Validator::make($request->all(), [
            'employee' => 'required|integer',
            'sdate' => 'required',
        ]);
    
        // If validation fails, return validation error messages
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ]);
        }
        
        // Retrieve input values and format dates
        $userId = $request->input('employee');
        $sdate = $request->input('sdate');
        $edate = $request->input('edate');
        if($edate == null)
        {
            $edate = date('Y-m-d');
        }
        
        try {
            // Fetch data using the query
            $data = DB::table('work_report as wr')
            ->join('work_report_detail as wrd', 'wr.id', '=', 'wrd.date_id')
            ->join('company_detail as cd', 'wrd.company_id', '=', 'cd.id')
            ->select(
                'wr.report_date as report_date',
                DB::raw('GROUP_CONCAT(DISTINCT cd.name ORDER BY cd.name ASC) as company_list'),
                DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(wrd.end_time, wrd.start_time)))) as total_time'),
                'wr.user_id as user_id'  // user_id should be part of select
                )
                ->where('wr.user_id', $userId)
                ->whereBetween('wr.report_date', [$sdate, $edate])
                ->groupBy('wr.report_date', 'wr.user_id') // Include user_id in GROUP BY
                ->orderBy('wr.report_date', 'ASC')
                ->get();
                
                // Check if data is empty
                if ($data->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No reports found for the selected criteria.',
                    ]);
                }
                
                // Return the fetched data as JSON response
                return response()->json([
                    'success' => true,
                    'data' => $data,
                ]);
            
        } catch (QueryException $e) {
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the data.',
            ]);
        }
    }

    public function getWorkReportByDate($date, $userId)
    {
        // Fetch data from the database based on the date and user ID
        $reportDetails = DB::table('work_report_detail as wrd')
            ->join('company_detail as cd', 'wrd.company_id', '=', 'cd.id')
            ->join('services as ss', 'wrd.service_id', '=', 'ss.id')
            ->join('work_report as wr', 'wrd.date_id', '=', 'wr.id')
            ->select(
                'cd.name as client_name',
                'ss.service_name as task_name',
                'wrd.start_time',
                'wrd.note',
                'wrd.end_time',
                DB::raw("CASE 
                            WHEN wrd.status = 'completed' THEN 'success'
                            WHEN wrd.status = 'pending' THEN 'danger'
                            ELSE 'warning'
                        END as status_class"),
                'wrd.status'
            )
            ->where('wr.report_date', $date)
            ->where('wr.user_id', $userId)  // Filter by user_id
            ->get();    

        return response()->json([
            'details' => $reportDetails,
        ]);
    }
    

    public function report_download(Request $request)
    {
        $userId = $request->input('employee');
        $sdate = Carbon::createFromFormat('d/m/Y', $request->input('sdate'))->format('Y-m-d');
        $edate = Carbon::createFromFormat('d/m/Y', $request->input('edate'))->format('Y-m-d');

        $data = DB::table('work_report as wr')
        ->join('work_report_detail as wrd', 'wr.id', '=', 'wrd.date_id')
        ->join('company_detail as cd', 'wrd.company_id', '=', 'cd.id')
        ->join('sub_service as ss', 'wrd.service_id', '=', 'ss.id') // Join with sub_service table
        ->join('users as u', 'wr.user_id', '=', 'u.id') // Join with users table
        ->select(
            'wr.report_date as report_date',
            DB::raw('GROUP_CONCAT(DISTINCT cd.name ORDER BY cd.name ASC) as company_list'),
            DB::raw('GROUP_CONCAT(DISTINCT ss.sub_service ORDER BY ss.sub_service ASC) as sub_service_list'),
            DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(wrd.end_time, wrd.start_time)))) as total_time'),
            'wr.user_id as user_id',
            'u.first_name as user_first_name', // Select user first name
            'u.last_name as user_last_name',  // Select user last name
            'u.email as user_email',           // Select user email
            DB::raw('MIN(wrd.start_time) as first_task_start_time'), // First task start time
            DB::raw('MAX(wrd.end_time) as last_task_end_time')      // Last task end time
        )
        ->where('wr.user_id', $userId)
        ->whereBetween('wr.report_date', [$sdate, $edate])
        ->groupBy('wr.report_date', 'wr.user_id', 'u.first_name', 'u.last_name', 'u.email') // Include user fields in groupBy
        ->orderBy('wr.report_date', 'ASC')
        ->get();
    
    
        return $data;
    }

}