<?php

namespace App\Http\Controllers\employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\Candidate_link;
use App\Models\User;
use App\Models\CvDetail;
use App\Models\InterviewDetail;
use App\Models\Candidate_details;
use App\Models\CandidateFollowup;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\InterviewScheduledMail;
use Carbon\Carbon;

class HRRequirmentController extends Controller
{

    public function index()
    {    
        $candidates = Candidate_link::all();
        return view('employee/candidate-list', compact('candidates'));
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
        return view('employee.add-candidate', ['token' => $token]);
    }


    public function store_candidate(Request $request)
    {
        // dd($request->all());
        $candidate = Candidate_details::where('email', '=', $request->email)->first();
        // dd($candidate);
        if($candidate != null)
        {
            return view('layout/success');

        }
        // return $request->all();
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
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Retrieve the token
        $token = $request->input('token');
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
        $data = candidate_details::all();
        $users = User::where('role', 'admin')->select('id', 'first_name')->get();
        return view('employee/candidate-details',compact('data','users'));
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
    
    public function cv_list()
    {
        $cvs = CvDetail::all();
        $followup = CandidateFollowup::all();
        $count = DB::select("SELECT s.status, COALESCE(c.count, 0) AS total FROM ( SELECT 'Selection' AS status UNION ALL SELECT 'Phone Interview' UNION ALL SELECT 'Technical Interview' UNION ALL SELECT 'Practical Interview' UNION ALL SELECT 'Background Verification' UNION ALL SELECT 'Finalisation' ) s LEFT JOIN ( SELECT status, COUNT(*) AS count FROM cv_details GROUP BY status ) c ON s.status = c.status;");
        return view('employee/cv_list',compact('cvs', 'followup', 'count'));
    }

    
    public function store_cv(Request $request)
    {
        $cvPath = null;

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255|unique:cv_details',
            'phone' => 'required|string|max:15',
            'designation' => 'required|string|max:255',
            'notice_period' => 'required|string|max:255',
            'experience' => 'required',
            'current_ctc' => 'nullable',
            'expected_ctc' => 'nullable',
            'cv' => 'nullable|mimes:pdf,jpeg,png,jpg|max:2048', // Accept both PDF and images (Max 2MB)
            'cv_url' => 'nullable|url', // Allow a CV URL
        ]);
    
    
        // Check if a file is uploaded
        if ($request->hasFile('cv')) 
        {
                $cvFile = $request->file('cv');
                $cvName = time() . '_' . $cvFile->getClientOriginalName();
                $cvDirectory = 'cv';
        
                // Ensure the directory exists
                if (!Storage::disk('public')->exists($cvDirectory)) {
                    Storage::disk('public')->makeDirectory($cvDirectory);
                }
        
                // Store the file
                $cvPath = $cvFile->storeAs($cvDirectory, $cvName, 'public');
            } elseif ($request->cv_url) {
                // If no file is uploaded but a URL is provided, store the URL
                $cvPath = $request->cv_url;
        }
   
        // Store Data in Database
        $cvDetail = CvDetail::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'source' => $request->source,
            'designation' => $request->designation,
            'notice_period' => $request->notice_period,
            'experience' => $request->experience,
            'current_ctc' => $request->current_ctc,
            'expected_ctc' => $request->expected_ctc,
            'cv_path' => $cvPath, // This now supports both uploaded files and URLs
        ]);
    
        if ($cvDetail)
        {
            return response()->json([
                'status' => 'success',
                'message' => 'CV Details Saved Successfully!',
                'url' => "candidate-cv-list",
            ]);
        } 
        else
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to Save CV Details!',
            ]);
        }
    }

    public function rejectCv(Request $request)
    {
        $cv = CvDetail::find($request->id);
        if ($cv) {
            $cv->status = 'Rejected'; // Update status
            $cv->save();

            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }


    
    public function rejectCvList()
    {
        $data = CvDetail::where('status', 'Rejected')->get();
        return view('employee/rejected_cv',compact('data'));
    }

    public function deleteCv(Request $request)
    {
        $employee = CvDetail::find($request->id);
    
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Record not found!']);
        }
    
        // Nullify candidate_id in candidate_follow_up instead of deleting the record
        $data = CandidateFollowUp::where('candidate_id', $employee->id);

        $data2 = InterviewDetail::where('candidate_id', $employee->id);

        $data->delete();

        $data2->delete();
    
        // Delete the CV file if it exists and is not a URL
        if ($employee->cv_path && !filter_var($employee->cv_path, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($employee->cv_path);
        }
    
        // Delete the main record from `cv_details`
        $employee->delete();
    
        return response()->json(['success' => true, 'message' => 'Record deleted successfully!']);
    }


    public function website_cv_list()
    {
        $data = http::get('https://digieagleinc.com/wp-json/custom-api/v1/form-entries/2');
        $data = $data->json();
        // dd($data);
        return view('employee/website_cv_list',compact('data'));
    }

    public function interview_schedule(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'candidate_id' => 'required|exists:cv_details,id',
            'interview_type' => 'required|string',
            'interview_date' => 'required|date',
            'interview_time' => 'required|date_format:H:i',
        ]);
    
         // Retrieve candidate details
        $data = CvDetail::find($request->candidate_id);

        if (!$data) {
            return response()->json(['status' => 'error', 'message' => 'Candidate not found']);
        }

        // Save the interview schedule to the database
        $interviewSchedule = InterviewDetail::create([
            'candidate_id' => $request->candidate_id,
            'interview_type' => $request->interview_type,
            'interview_date' => $request->interview_date,
            'interview_time' => $request->interview_time,
        ]);

         // Attach candidate name separately
        $interviewSchedule->candidate_name = $data->name;
        
         // Send email to admin
        // Mail::to('manager.digieagleinc@gmail.com')->send(new InterviewScheduledMail($interviewSchedule));

        // Return success response
        if ($interviewSchedule) {
            return response()->json(['status' => 'success', 'message' => 'Interview scheduled successfully']);
        }
    
        // Return error response if something goes wrong
        return response()->json(['status' => 'error', 'message' => 'Failed to schedule interview']);
    } 

    public function edit_interview_schedule(Request $request)
    {
        // dd($request->all());
        // Retrieve the interview schedule
        $interviewSchedule = InterviewDetail::find($request->id);

        if (!$interviewSchedule) {
            return response()->json(['status' => 'error','message' => 'Interview schedule not found']);
        }

        // Validate the incoming request
        $request->validate([
            'interview_type' => ['required','string'],
            'interview_date' => ['required', 'date'],
            'interview_time' => ['required'],
        ]);

        $time = Carbon::parse($request->interview_time)->format('H:i');
        $interviewSchedule->interview_type = $request->interview_type;
        $interviewSchedule->interview_date = $request->interview_date;
        $interviewSchedule->interview_time = $time;

        // Save the changes
        if ($interviewSchedule->save()) {
            return response()->json(['status' => 'success','message' => 'Interview schedule updated successfully']);
        }

        // Return error response if something goes wrong
        return response()->json(['status' => 'error','message' => 'Failed to update interview schedule']);
    }
    
    public function add_followup(Request $request)
    {   
        if($request->interview_id == null)
        {
            $request->validate([
                'candidate_id' => 'required|exists:cv_details,id',
                'notes' => 'required|string|max:500',
                'status' => 'required',
            ]);
            
            // Create a new follow-up record
            $followup = CandidateFollowup::create([
                'candidate_id' => $request->candidate_id,
                'follow_up' => $request->notes,
            ]);
            
            $data = CvDetail::find($request->candidate_id);
            $data->status = $request->status;
            $data->save();
            // dd($data);
            
            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Follow-up added successfully!',
            ]);
        }
        else{
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
}
