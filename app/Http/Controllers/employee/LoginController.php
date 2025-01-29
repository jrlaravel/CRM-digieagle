<?php

namespace App\Http\Controllers\employee;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Activity_log;
use App\Models\Designation;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;


class LoginController extends Controller
{
    public function index(){
        return view('employee.login');
    }

    
    public function authenticate(Request $request)
    {
        // Ensure the login attempt is not rate-limited
        $this->ensureIsNotRateLimited($request);
        
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('emp/login')->withInput()->withErrors($validator);
        }
        
        // Attempt login with credentials
        if (Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password])) {
            // Clear throttle count on successful login
            RateLimiter::clear($this->throttleKey($request));
            
            $user = Auth::guard('web')->user();
            
            // Check if the user is an admin
            if ($user->role == 'admin') {
                Auth::guard('web')->logout();
                return redirect()->route('emp/login')->withInput()->with(['error' => 'You are not authorized to access this panel']);
            }
    
            // For employee or HR roles
            if ($user->role == 'employee' || $user->role == 'hr') {
                session()->put('employee', $user);
    
                $department = Department::find($user->department);
                $user->department_name = $department ? $department->name : 'Unknown';
    
                $designation = Designation::find($user->designation);
                session()->put('has_bde_features', $designation && $designation->name == 'BDE');
                session()->put('has_hr_features', $user->role == 'hr');
    
                // Log the activity
                $activity_log = new Activity_log();
                $activity_log->user_id = $user->id;
                $activity_log->description = 'Logged in successfully';
                $activity_log->save();
    
                return redirect()->route('emp/dashboard');
            }
    
            // If the user role doesn't match 'employee' or 'hr'
            Auth::guard('web')->logout();
            return redirect()->route('emp/login')->withInput()->with(['error' => 'You are not authorized to access this area']);
        } else {
            // Increment the throttle count on failed login
            RateLimiter::hit($this->throttleKey($request), 60); // 1 minute decay
    
            throw ValidationException::withMessages([
                'username' => [__('Invalid credentials')],
            ]);
        }
    }
    
    
    /**
     * Ensure the login attempt is not rate-limited.
     */
    protected function ensureIsNotRateLimited(Request $request)
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), 3)) {
            $seconds = RateLimiter::availableIn($this->throttleKey($request));
            Activity_log::create([
                'user_id' => $this->getUserIdByUsername($request->input('username')),
                'description' => 'Rate-limited login attempt - Too many failed login attempts.',
                'ip_address' => $request->ip(),
                'throttle_key' => $this->throttleKey($request),
                'created_at' => now(),
            ]);
    
            throw ValidationException::withMessages([
                'username' => [__('Too many login attempts. Please try again in ' . $seconds . ' seconds.')],
            ]);
        }
    }
    
    protected function getUserIdByUsername($username)
    {
        $user = User::where('username', $username)->first(); // Adjust to your actual user model and field
        return $user ? $user->id : null;  // Return null if user not found
    }

    /**
     * Generate a unique throttle key for the request.
     */
    protected function throttleKey(Request $request)
    {
        return strtolower($request->input('username')) . '|' . $request->ip();
    }

    public function resetpassword()
    {      
        return view('employee/resetpassword');
    }

    public function varifyemail(Request $request)
    {   
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
        ]);
        
        $user = User::where('email',$request->email)->first();
        if($user)
        {
            $token = Str::random(60);
  
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email, 
                'token' => $token, 
                'created_at' => Carbon::now()
              ]);

            Mail::send('employee.maillink', ['token' => $token], function($message) use($request){
              $message->to($request->email);
              $message->subject('Reset Password');
          });

          return back()->with('message', 'We have e-mailed your password reset link!');

        }

        else{
            return back()->with('error', 'User not found!');
        }
    }   

    public function newpassword($token){
        return view('employee/newpassword' ,['token' => $token]);
    }

    public function updatepassword(Request $request)
    {

        // dd($request->all());
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);


        if($validator->passes()){
            $updatePassword = DB::table('password_reset_tokens')
                                ->where([
                                'email' => $request->email, 
                                'token' => $request->token
                                ])
                                ->first();

            if(!$updatePassword){
                return back()->withInput()->with('error', 'Invalid token!');
            }

            $user = User::where('email', $request->email)
                        ->update(['password' => Hash::make($request->password)]);

            $data = User::where('email',$request->email)->get();

            // Log activity
            $activity_log = new Activity_log();
            $activity_log->user_id = $data[0]->id;
            $activity_log->description = 'Password change successfully';
            $activity_log->save();

            DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();
          
            return redirect()->route('emp/login')->with('message', 'Your password has been changed!');
        }
        else{
            return back()->withInput()->withErrors($validator);
        }
    }
    

    public function logout(){
        $userid = session('employee')->id;
        // Log activity
        $activity_log = new Activity_log();
        $activity_log->user_id = $userid;
        $activity_log->description = 'Logged out successfully';
        $activity_log->save();
        Auth::logout();
        return redirect()->route('emp/login');
    }

    public function changepassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|confirmed',
            'new_password_confirmation' => 'required'
        ]);
    
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }
    
        $user = Auth::guard('web')->user();
    
        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();

            // Log activity
            $activity_log = new Activity_log();
            $activity_log->user_id = $user->id;
            $activity_log->description = 'Password changed successfully';
            $activity_log->save();
    
            return back()->with('success_password', 'Password changed successfully!');
        } else {
            return back()->with('error_password', 'Current password does not match!');
        }
    }    
}
