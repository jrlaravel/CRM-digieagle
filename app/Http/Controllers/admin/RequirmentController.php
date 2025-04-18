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
use App\Models\InterviewReview;
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
        $token = $request->get('_token');
        $name = $request->get('name');
        $link = route('add-candidate', ['token' => $name]);
    
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
        $candidate = Candidate_link::where('name', $token)->first();

        // If candidate is not found, show 404 error
        if (!$candidate) {
            abort(404);
        }

        // Pass the token to the view
        return view('admin/add-candidate', ['token' => $token]);
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
  
    public function candidateReview()
    {
        $data = InterviewReview::all();
        return view('admin/candidate_review', compact('data'));
    }

    public function deleteReview($id)
    {
        $data = InterviewReview::find($id);
        $data->delete();
        return redirect()->back()->with('success', 'Review deleted successfully');
    }
}
