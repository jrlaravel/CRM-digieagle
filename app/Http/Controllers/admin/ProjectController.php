<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;   
use Illuminate\Support\Facades\DB;
use App\Models\Project_type;
use App\Models\Project_detail;

class ProjectController extends Controller
{
    public function index(){
        $data = Project_type::all();
        return view('admin/project_type',compact('data'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' =>'required',
        ]);

        if($validator->passes()){ 
            Project_type::create([
                'name' => $request->name,
            ]);

            return back()->with('success', 'Project Type Added Successfully');
        }

        return back()->with('error', 'Project Type Already Exists');
    }

    public function delete($id){
        $data = Project_type::find($id);
        $data->delete();
        return back()->with('success', 'Project Type Deleted Successfully');
    }

    public function project_add(){
        $type = Project_type::all();
        $user = DB::select('select first_name,last_name,id from users');
        return view('admin/add_project_detail',compact('type','user'));
    }

    public function project_add_detail(Request $request)
    {
        // Validate the input data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'platform' => 'required|array', // Ensure 'platform' is an array
            'startdate' => 'required',
            'type' => 'required',
            'member' => 'required|array', // Ensure 'member' is an array
            'tagetage' => 'required',
            'targetcity' => 'required',
        ]);
    
        if ($validator->passes()) {
            // Create the project in the Project_detail table
            $project = Project_detail::create([
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => $request->startdate,
                'deadline' => $request->deadlinedate,
                'project_type' => $request->type,
                'target_audience_age' => $request->tagetage,
                'target_city' => $request->targetcity,
                'platform' => json_encode($request->platform), // Store platforms as JSON
                'status' => '0'
            ]);
    
            // Get the project ID
            $project_id = $project->id;
    
            // Store the user IDs with the project ID in the project_user table
            foreach ($request->member as $user_id) {
                DB::table('project_user')->insert([
                    'project_id' => $project_id,
                    'user_id' => $user_id,
                ]);
            }
    
            return redirect()->route('admin/list-project-detail');
        }
    
        return back()->with('error', 'Please fill all required fields');
    }

    public function update(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:project_detail,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:0,1,2', 
        ]);
        
        $project = Project_detail::find($request->project_id);
        

        $project->name = $request->input('name');
        $project->description = $request->input('description');
        $project->status = $request->input('status');
        $project->priority = $request->input('priority');

        $project->save();

        return redirect()->back()->with('success', 'Project updated successfully.');
    }

    public function updateAssignedUsers(Request $request)
    {
        // Validate the form data
        $request->validate([
            'project_id' => 'required|exists:project_detail,id',
            'assigned_users' => 'array', // This expects an array of user IDs
            'assigned_users.*' => 'exists:users,id' // Each user ID must exist in the users table
        ]);
    
        // Get the project ID and assigned users
        $projectId = $request->input('project_id');
        $assignedUsers = $request->input('assigned_users');
    
        // Remove all existing users assigned to this project from the project_user table
        DB::table('project_user')->where('project_id', $projectId)->delete();
    
        // Insert new user assignments into the project_user table
        if (!empty($assignedUsers)) {
            $userAssignments = [];
            foreach ($assignedUsers as $userId) {
                $userAssignments[] = [
                    'project_id' => $projectId,
                    'user_id' => $userId
                ];
            }
    
            // Bulk insert new records
            DB::table('project_user')->insert($userAssignments);
        }
    
        // Redirect back or return success response
        return redirect()->back()->with('success', 'Assigned users updated successfully.');
    }
    
    public function project_list(){
        $project = DB::select('SELECT DISTINCT pd.id as project_id,pd.priority,pd.name,pd.description,pd.status,pt.id as pt_id,pt.name as typename FROM `project_detail` as pd join project_type as pt on pd.project_type = pt.id join project_user on pd.id = project_user.project_id');
        $user = DB::select('SELECT project_id,user_id,profile_photo_path,first_name,last_name FROM `project_user` as pu JOIN users on pu.user_id = users.id');
        $employee = DB::select('SELECT id,first_name,last_name FROM users');
        return view('admin/list_project_detail',compact('project', 'user', 'employee'));
    }

    public function project_delete_detail($id)
    {
        $project = Project_detail::find($id);
        $project->delete();
        return redirect()->route('admin/list-project-detail')->with('success', 'Project Detail Deleted Successfully');    
    }
}
