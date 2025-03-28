<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Activity_log;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Notification;
use App\Models\Festival_leave;
use App\Models\MediaManager;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use PDF;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Services\ETimeOfficeService;


class EmployeeController extends Controller
{
    private $eTimeOfficeService;

    public function __construct(ETimeOfficeService $eTimeOfficeService)
    {
        $this->eTimeOfficeService = $eTimeOfficeService;
    }

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



public function show() {
    $employees = DB::table('users')
    ->join('department as dep', 'users.department', '=', 'dep.id')
    ->join('designation as des', 'users.designation', '=', 'des.id')
    ->select(
        'users.id as uid',
        'users.first_name',
        'users.last_name',
        'users.username',
        'users.birth_date',
        'users.email',
        'users.phone',
        'users.address',
        'dep.name as depname',
        'users.empcode as code',
        'dep.id as depid',
        'des.id as desid',
        'des.name as desname'
    )
    ->orderBy('users.id', 'desc')
    ->paginate(100);

    $department = Department::all();
    $designation = Designation::all();

    return view('admin.list-emp', compact('employees', 'department', 'designation'));
}


    public function edit($id){

        $data = User::find($id);
        return response($data);
    }

    public function update(Request $request)
    {
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

        else{
            $user->first_name = $request->input('fname');
            $user->last_name = $request->input('lname');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->address = $request->input('address');
            $user->birth_date = $request->input('date');
            $user->designation = $request->input('designation');
            $user->department = $request->input('department');
            $user->empcode = $request->input('code');
            if($request->password != null)
            {
                $user->password = Hash::make($request->password);
            }  
            $user->save();
            return redirect()->route('admin/list-emp');  
        }
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

    public function activity_log()
    {
        $data = Activity_log::all();
        return view('admin/activity_log', compact('data'));
    }


    public function downloadActivityLogPDF(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'fdate' => 'required|date',
            'tdate' => 'required|date|after_or_equal:fdate',
        ]);
    
        // Fetch data from the activity_log table within the date range
        $data = DB::table('activity_log')
            ->whereBetween('created_at', [$request->fdate, $request->tdate])
            ->get();
    
        // Create the PDF
        $pdf = PDF::loadView('admin/activity_log_pdf', ['data' => $data]);
    
        // Define the file path and name
        $directory = storage_path('app/public/temp');
        $fileName = 'activity_log_' . now()->format('Y_m_d_His') . '.pdf';
        $filePath = $directory . '/' . $fileName;
    
        // Check if the directory exists, if not, create it
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true); // Permissions set to 755, recursively create directories
        }
    
        // Save the PDF to the directory
        $pdf->save($filePath);
    
        // Return the file as a download response
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function MediaManager()
    {
        $media = MediaManager::all();
        return view('admin/media-manager',compact('media'));
    }
 
    public function uploadMedia(Request $request)
    {
        // return $request->hasFile('document');
        // Check if multiple files are uploaded
        if ($request->hasFile('document')) {
            foreach ($request->file('document') as $file) {
                // Store file in the storage folder
                $filePath = $file->store('media', 'public');
                // Save file path in the database
                MediaManager::create([
                    'path' => $filePath,
                ]);
            }
    
            return back()->with('success', 'Files uploaded successfully!');
        }
    
    }

    public function deleteMedia($id)
    {
        $media = MediaManager::find($id);
        // Delete file from the storage folder
        Storage::delete('public/media'. $media->path);
        // Delete record from the database
        $media->delete();
        return redirect()->route('admin/media-manager');
    }
    
    public function empdetails($id)
    {
        $data = DB::select('SELECT users.id,users.birth_Date,users.empcode,users.profile_photo_path,users.first_name,users.last_name,dep.name as depname,users.username,users.skills,des.name as desname,users.phone,users.email,users.address,users.document FROM `users` join department as dep on users.department = dep.id join designation as des on des.id = users.designation where users.id = '.$id);
        $data = $data[0];
        $code = $data->empcode;
        $fromDate = Carbon::now()->startOfMonth()->format('d/m/Y');
        $toDate = Carbon::now()->endOfMonth()->format('d/m/Y');
        
        $attendanceData = $this->eTimeOfficeService->getInOutPunchData($code, $fromDate, $toDate);

        $collection = collect($attendanceData['InOutPunchData']);
        
        $presentDaysCount = $collection->where('Status', 'P')->count();
        $absentDaysCount = $collection->where('Status', 'A')->count();

        // Calculate remaining days in the current month
        $today = Carbon::today();
        $lastDayOfMonth = Carbon::now()->endOfMonth();
        $remainingDaysCount = $lastDayOfMonth->diffInDays($today) + 1;
        
        // Ensure it's an integer
        $remainingDaysCount = (int) round($remainingDaysCount);
         
        $appleave = DB::Select("SELECT SUM(total_days) as appleave FROM `leave` as data join leavetype on data.leave_type_id = leavetype.id WHERE leavetype.name != 'Half day' and data.status = 1 and data.user_id = " .$id);
        $appleave = $appleave[0]->appleave;
        $totalleave = 12;
        $remainingleave = $totalleave - $appleave;

        // Decode the document field (if it contains JSON)
        $documentIds = json_decode($data->document, true) ?? [];

        // Fetch actual file paths along with IDs
        $documents = MediaManager::whereIn('id', $documentIds)
            ->get(['id', 'path']) // Fetch ID and Path
            ->toArray(); // Convert to an array
            return view('admin/emp-details', compact('appleave', 'remainingleave','presentDaysCount', 'remainingDaysCount','absentDaysCount', 'data','documents'));
        }
}