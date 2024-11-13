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
use App\Models\Designation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;


class LoginController extends Controller
{
    public function index(){
        return view('employee.login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('emp/login')->withInput()->withErrors($validator);
        }
    
        if (Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::guard('web')->user();
    
            // Check if the user's role is either employee or HR
            if ($user->role == 'employee' || $user->role == 'hr') {

                session()->put('employee', $user);
    
                $designation = Designation::find($user->designation);
    
                if ($designation && $designation->name == 'BDE') {
                    session()->put('has_bde_features', true); 
                } else {
                    session()->put('has_bde_features', false);
                }
    
                // Check if the user is HR and store HR-related session data if needed
                if ($user->role == 'hr') {
                    session()->put('has_hr_features', true); 
                } else {
                    session()->put('has_hr_features', false);
                }
    
                return redirect()->route('emp/dashboard');
            } else {
                Auth::guard('web')->logout();
                return redirect()->route('emp/login')->withInput()->with(['error' => 'You are not authorized to access this area']);
            }
        } else {
            return redirect()->route('emp/login')->withInput()->with(['error' => 'Invalid credentials']);
        }
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

            DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();
          
            return redirect()->route('emp/login')->with('message', 'Your password has been changed!');
        }
        else{
            return back()->withInput()->withErrors($validator);
        }
    }
    

    public function logout(){
        Auth::logout();
        return redirect()->route('emp/login');
    }
}
