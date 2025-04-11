<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InterviewReview;

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
    
    
}
