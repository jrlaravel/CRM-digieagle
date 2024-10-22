<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Notification;


class HREmployeeController extends Controller
{
    public function index(){
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



    public function show(){
        
        $employees = DB::select('SELECT users.id as uid,users.first_name,users.last_name,users.username,users.birth_date,users.email,users.phone,users.address,dep.name as depname,dep.id as depid,des.id as desid,des.name as desname FROM `users` join department as dep on users.department = dep.id join designation as des on users.designation = des.id;');
        $department = Department::all();
        $designation = Designation::all();
        return view('employee.list-emp', compact('employees','department','designation'));
    }

    public function edit($id){

        $data = User::find($id);
        return response($data);
    }

    public function update(Request $request){
        
        $user = User::find($request->id);  
        $user->first_name = $request->input('fname');
        $user->last_name = $request->input('lname');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->birth_date = $request->input('date');
        $user->designation = $request->input('designation');
        $user->department = $request->input('department');
        $user->save();

        return redirect()->route('emp/list-emp');  
    
    }

    public function delete($id){
        $employee = User::find($id);
        $employee->delete();
        return redirect()->route('emp/list-emp');
    }

}
