<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;

class AdminLoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            'username' => 'required',
            'password' => 'required'
        ]);

        if($validator->passes()){
            if(Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password])){
                $user = Auth::guard('admin')->user();

                if(Auth::guard('admin')->user()->role != 'admin'){
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin/login')->withInput()->with(['error' => 'You are not authorized to access this area']);
                }
            
                session()->put('user', $user);
                return redirect()->route('admin/dashboard');
            }
            else{
                return redirect()->route('admin/login')->withInput()->with(['error' => 'Invalid credentials']);
            }
        }
        else{
            return redirect()->route('admin/login')->withInput()->withErrors($validator);
 
        }   
    }
    
    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin/login');
    }

}
