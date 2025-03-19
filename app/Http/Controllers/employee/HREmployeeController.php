<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Notification;
use App\Models\MediaManager;
use App\Services\ETimeOfficeService;
use Carbon\Carbon;

class HREmployeeController extends Controller
{
    private $eTimeOfficeService;

    public function __construct(ETimeOfficeService $eTimeOfficeService)
    {
        $this->eTimeOfficeService = $eTimeOfficeService;
    }

    public function index()
    {
        $department = DB::select('SELECT DISTINCT name,id FROM `department` WHERE status = 1;');
        return view('employee.add-emp',compact('department'));
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

                return redirect()->route('emp/list-emp')->with(['success' => 'Employee added successfully']);
        } 
        else {
            // Validation failed, redirect back with errors and input
            return redirect()->route('emp/add-emp')->withInput()->withErrors($validator);
        }
    }

    public function show()
    {
        
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
        return view('employee.list-emp', compact('employees','department','designation'));
    }

    public function edit($id)
    {

        $data = User::find($id);
        return response($data);
    }

    public function update(Request $request)
    {
        
        $user = User::find($request->id);  
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

        return redirect()->route('emp/list-emp');  
    
    }

    public function delete($id)
    {
        $employee = User::find($id);
        $employee->delete();
        return redirect()->route('emp/list-emp');
    }
    
    public function MediaManager()
    {
        $media = MediaManager::all();
        return view('employee/media-manager',compact('media'));
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
        return redirect()->route('emp/media-manager');
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
        return view('employee/emp-details', compact('appleave', 'remainingleave','presentDaysCount', 'remainingDaysCount','absentDaysCount', 'data','documents'));
    }

    public function employeeDocument(Request $request)
    {
        // Validate request
        $request->validate([
            'images' => 'required|array', // Accept multiple files
            'images.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048', // Validate each file
            'id' => 'required|exists:users,id',
        ]);
        
        $user = User::find($request->id);
        
        // Check if files are present in request
        if (!$request->hasFile('images')) {
            return response()->json(['error' => 'No files uploaded'], 400);
        }
        
        $documentIds = []; // To store all uploaded document IDs
        
        foreach ($request->file('images') as $file) {
            // Store File in 'public/media' directory
            $filePath = $file->store('media', 'public');
            
            // Save File Path in Media Manager
            $media = MediaManager::create(['path' => $filePath]);
            
            $documentIds[] = $media->id; // Collect document IDs
        }
        // return $documentIds;
    
        // Update User's document JSON column
        $existingDocuments = $user->document ? json_decode($user->document, true) : [];
        $user->document = json_encode(array_merge($existingDocuments, $documentIds));
        $user->save();
    
        return redirect()->back()->with('success','Document added successfully');
    }
}
