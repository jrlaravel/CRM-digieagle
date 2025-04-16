<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InterviewReview;
use App\Models\Candidate_details;
use App\Models\Candidate_link;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index($candidate)
    {
        return view('employee.review-form', compact('candidate'));
    }
    
    public function store(Request $request)
    {
        // Validate request data
        $validated = $request->validate([
            'candidate_name'    => 'required|string|max:255',
            'interviewer_name'  => 'required|string|max:255',
            'answer1'           => 'required|string',
            'answer2'           => 'required|string',
            'rate'              => 'required|integer|min:1|max:5',
            'consent'           => 'accepted',
        ]);
        
    
        // Check if a review by the same candidate already exists
        $existingReview = InterviewReview::where('candidate_name', $validated['candidate_name'])->first();
    
        if ($existingReview) {
            return view('layout.success')->with('success', 'Thank you for your review!');
        }
    
        // Create new review
        InterviewReview::create($validated);
    
        // Return thank-you view
        return view('layout.success')->with('success', 'Thank you for your review!');
    }
    
    public function add_candidate_link(Request $request)
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

    public function store_candidate(Request $request)
    {
        // return $request->all();
        $candidate = Candidate_details::where('email', '=', $request->email)->first();
        if ($candidate != null) {
            return view('layout/success');
        }
        // Validate all fields including profile photo
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:candidate_details',
            'phone' => 'required|regex:/^[0-9]{10,15}$/', // Allows 10 to 15 digits
            'designation' => 'required|string|max:255',
            'experience' => 'required|string',
            'reference_name' => 'nullable|string|max:255',
            'reference_phone' => 'nullable|string|max:15',
            'organization_name' => 'required|string|max:255',
            'position_name' => 'required|string|max:255',
            'notice_period' => 'required|string',
            'expected_date' => 'required|date',
            'current_ctc' => 'required|string',
            'expected_ctc' => 'required|string',
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        // Retrieve the token
        $token = $request->input('_token');
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
}
