<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Project_detail;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index($id)
    {
        $project = Project_detail::find($id);
        return view('admin/task',compact('project'));
    }
}