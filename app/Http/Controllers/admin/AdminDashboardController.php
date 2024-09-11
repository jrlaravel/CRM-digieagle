<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminDashboardController extends Controller
{
    public function index()
    {   
        $totalUsers = User::where('role','employee')->count();
        return view('admin.admindashboard',compact('totalUsers'));
    }

    public function adminProfile(){
        $data = User::find(session('user')->id);
        return view('admin.profile', compact('data'));
    }

    public function profilePhoto(Request $request){
         
        // return $request->hasFile('profile_photo');
        $validator = Validator::make($request->all(), [
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('profile_photos', $filename, 'public');

                // Save $filename to the user's profile in the database, if applicable
                $user =  User::find(session('user')->id);
                $user->profile_photo_path = $filename;
                $user->save();
            }

            return back()->with('success', 'Profile photo updated successfully.');
    }

    public function notification(Request $request)
    {
        // Fetch unread notifications for the authenticated user
        $notifications = Notification::where('user_id', session('user')->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response($notifications);
    }
}
