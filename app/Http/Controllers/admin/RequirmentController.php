<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Candidate_link;
use App\Models\User;
use App\Models\CandidateFollowup;
use App\Models\Candidate_details;
use App\Models\InterviewDetail;
use Illuminate\Support\Facades\Validator;

class RequirmentController extends Controller
{
    public function index()
    {    
        $candidates = Candidate_link::all();
        return view('admin/candidate-list', compact('candidates'));
    }

    public function add(Request $request)
    {
        // return $request->all();
        $token = $request->get('_token');
        $name = $request->get('name');
        $link = route('add-candidate', ['token' => $token]);
    
        // Save using the model
        Candidate_link::create([
            'name' => $name,
            'token' => $token,
            'link' => $link,
        ]);
    
        return redirect()->back()->with('success','Link created Successfully');  
    }

    public function delete($id)
    {
        $candidate = Candidate_link::find($id);
        $candidate->delete();
        return redirect()->back()->with('success','Link deleted Successfully');   
    }

    public function add_candidate($token)
    {
        // Verify the token
        $candidate = Candidate_link::where('token', $token)->first();

        // If candidate is not found, show 404 error
        if (!$candidate) {
            abort(404);
        }

        // Pass the token to the view
        return view('admin/add-candidate', ['token' => $token]);
    }

    public function store_candidate(Request $request)
    {
        // return $request->all();
        if(Candidate_details::find($request->name))
        {
            return view('layout./success');
        }
        // Validate all fields including profile photo
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|regex:/^[0-9]{10,15}$/', // Allows 10 to 15 digits
            'designation' => 'required|string|max:255',
            'experience' => 'required|integer',
            'reference_name' => 'nullable|string|max:255',
            'reference_phone' => 'nullable|string|max:15',
            'organization_name' => 'required|string|max:255',
            'position_name' => 'required|string|max:255',
            'notice_period' => 'required|string',
            'expected_date' => 'required|date',
            'current_ctc' => 'required|numeric',
            'expected_ctc' => 'required|numeric',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'career_goal' => 'nullable|string',
            'position_responsibilities' => 'nullable|string',
            'areas_of_expertise' => 'nullable|string',
            'improve_your_knowledge' => 'nullable|string',
            'service_are_we_providing' => 'nullable|string',
            'reason_for_leaving' => 'nullable|string',
            'reason_for_applying' => 'nullable|string',
        ]);

        // Return errors in JSON format if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Retrieve the token
        $token = $request->get('_token');
        
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token provided.'
            ]);
        }

        // Save candidate details
        $data = Candidate_details::create($request->all());

        if ($data) {
            
            $link = Candidate_link::where('token', $token)->first();
            if ($link) {
                $link->delete();
            }
          return view('layout/success');
        }

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while submitting the data.'
        ]);
    }

    public function candidate_details()
    {
        $data = candidate_details::where(function ($query) {
            $query->where('assign_to', '=', session('user')->id)  // Candidate assigned to the logged-in user
                  ->orWhere('assign_to', '=', 0);                  // Candidate not assigned to anyone
        })->get();
        $users = User::where('role', 'admin')->select('id', 'first_name')->get();
        return view('admin/candidate-details',compact('data','users'));
    }

    public function candidate_details_delete($id)
    { 
        $data = candidate_details::find($id);
        $data->delete();
        return back()->with('success', 'Candidate details deleted successfully.');
    }

    public function assign_candidate_details(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:candidate_details,id',
            'assign_to' => 'required',
        ]);
    
        $candidate = Candidate_details::find($request->id);
        if (!$candidate) {
            return response()->json(['error' => 'Candidate not found'], 404);
        }
    
        $candidate->assign_to = $request->assign_to;
        $candidate->save();
    
        return response()->json(['success' => 'Candidate assigned successfully!']);
    }
    
    public function add_followup(Request $request)
    {   
        // Validate the incoming request
        $request->validate([
            'candidate_id' => 'required|exists:cv_details,id',
            'notes' => 'required|string|max:500',
        ]);
    
        // Create a new follow-up record
        $followup = CandidateFollowup::create([
            'candidate_id' => $request->candidate_id,
            'follow_up' => $request->notes,
        ]);

        $data = InterviewDetail::find($request->interview_id);
        $data->status = "1";
        $data->save();
    
        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Follow-up added successfully!',
        ]);
    }
    
}
