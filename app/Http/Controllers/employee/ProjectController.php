<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use App\Models\Project_type;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $type = Project_type::all();
        $project = collect(DB::select('SELECT project_type,name,project_detail.id FROM project_detail join project_user on project_detail.id = project_user.project_id WHERE project_user.user_id = '.session('employee')->id));
        return view('employee/project', compact('type','project'));
    } 
}
    