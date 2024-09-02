<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Notification;
use App\Models\Designation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
 
class DepartmentController extends Controller
{
    public function index()
    {
        $data = Department::get();
        return view('admin.department',compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' =>'required',
        ]);

        if($validator->passes()){ 
            Department::create([
                'name' => $request->name,   
                'status' => 1
            ]);

            Notification::create([
                'user_id' => session('user')->id,
                'title' => 'Department added',
                'url' => 'department',
                'message' => 'A new department has been added: '. $request->input('name')
            ]);

            return redirect()->back();
        }
        else
        {
            return redirect()->back()->withInput()->withErrors($validator);
        }

    }

    public function delete($id){
        $department = Department::find($id);
        $department->delete();
        return redirect()->route('admin/department');
    }

    public function status($id,$status){
        $department = Department::find($id);
        if($status == 1){
            $status = 0;
        }else{
            $status = 1;
        }
        $department->status = $status;
        $department->save();
        return redirect()->route('admin/department');
    }

    public function designationIndex()
    {
        $data = Department::where('status' , 1)->get();
        $designation = DB::select('select dep.name as depname,des.id as desid,des.name as desname,des.status as desstatus,dep.status as depstatus from designation as des join department as dep on des.department_id = dep.id;');
        return view('admin/designation',compact('data','designation'));
    }

    public function designationStore(Request $request)
    {
        Designation::create([
            'department_id' => $request->department,
            'name' => $request->name,
            'status' => 1
        ]);

        Notification::create([
            'user_id' => session('user')->id,
            'title' => 'designation added',
            'url' => 'designation',
            'message' => 'A new designation has been added: '. $request->input('name')
        ]);

        return redirect()->back();
    }

    public function deletedesignation($id){
        $designation = Designation::find($id);
        $designation->delete();
        return redirect()->route('admin/designation');
    }

    public function designationstatus($id,$status){
        $designation = Designation::find($id);
        if($status == 1){
            $status = 0;
        }else{
            $status = 1;
        }
        $designation->status = $status;
        $designation->save();
        return redirect()->route('admin/designation');
    }
}
