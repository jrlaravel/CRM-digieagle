<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\employee\LoginController;
use App\Http\Controllers\employee\EmployeeDashboardController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\EmployeeController;
use App\Http\Controllers\admin\DepartmentController;
use App\Http\Controllers\employee\AttendanceController;
use App\Http\Controllers\admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\admin\CardsController;


Route::get('/', [LoginController::class, 'index'])->name('emp/login'); 
Route::prefix('emp')->group(function () {

    Route::group(['middleware' => 'emp.guest'],function () {
        Route::post('authenticate', [LoginController::class, 'authenticate'])->name('emp/authenticate'); 
        // Route::get('/google/callback', [LoginController::class, 'loginWithGoogle'])->name('emp/login-with-google');
        // Route::get('/loginwithgoogle', [LoginController::class, 'redirectToGoogle'])->name('emp/login-with-google-redirect');
        Route::get('/resetpassword', [LoginController::class, 'resetpassword'])->name('emp/resetpassword');
        Route::post('/varify-email', [LoginController::class, 'varifyemail'])->name('emp/varify-email');
        Route::get('/new-password/{token}', [LoginController::class, 'newpassword'])->name('emp/new-password');
        Route::post('/new-password', [LoginController::class, 'updatepassword'])->name('emp/reset-password');
    });

    Route::group(['middleware' => 'emp.auth'],function () {
        Route::get('logout', [LoginController::class, 'logout'])->name('emp/logout'); 
        Route::get('dashboard', [EmployeeDashboardController::class, 'index'])->name('emp/dashboard'); 
        Route::get('profile', [EmployeeDashboardController::class, 'profile'])->name('emp/profile');
        Route::post('Updateprofile', [EmployeeDashboardController::class, 'updateProfile'])->name('emp/Updateprofile');
        Route::get('attendance', [EmployeeDashboardController::class, 'attendance'])->name('emp/attendance');
        Route::post('/inoutdata', [AttendanceController::class, 'inoutdata'])->name('emp/inoutdata');;
        Route::post('/download-pdf', [AttendanceController::class, 'downloadPDF'])->name('download.pdf');
        Route::post('profilephoto', [EmployeeDashboardController::class, 'profilePhoto'])->name('emp/profilephoto'); 
    });
});

Route::prefix('admin')->group(function () {

    Route::group(['middleware' => 'admin.guest'],function () {
        Route::get('login', [AdminLoginController::class, 'index'])->name('admin/login'); 
        Route::post('authenticate', [AdminLoginController::class, 'authenticate'])->name('admin/authenticate'); 
       
    });

    Route::group(['middleware' => 'admin.auth'],function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin/dashboard'); 
        Route::get('profile', [AdminDashboardController::class, 'adminProfile'])->name('admin/profile'); 
        Route::post('profilephoto', [AdminDashboardController::class, 'profilePhoto'])->name('admin/profilephoto'); 
        Route::get('attendance', [AdminAttendanceController::class, 'index'])->name('admin/attendance');
        Route::post('/inoutdata', [AdminAttendanceController::class, 'inoutdata'])->name('admin/inoutdata');
        Route::get('logout', [AdminLoginController::class, 'logout'])->name('admin/logout');
        Route::get('add-emp', [EmployeeController::class, 'index'])->name('admin/add-emp'); 
        Route::get('get-designations', [EmployeeController::class, 'getDesignations'])->name('admin/get-designations');
        Route::post('add-emp-data', [EmployeeController::class, 'store'])->name('admin/add-emp-data'); 
        Route::get('list-emp', [EmployeeController::class, 'show'])->name('admin/list-emp'); 
        Route::get('delete-emp-data/{id}', [EmployeeController::class, 'delete'])->name('admin/delete-emp-data'); 
        Route::get('edit-emp-data/{id}', [EmployeeController::class, 'edit'])->name('admin/edit-emp-data'); 
        Route::post('update-emp-data', [EmployeeController::class, 'update'])->name('admin/update-emp-data'); 
        Route::get('department', [DepartmentController::class, 'index'])->name('admin/department');         
        Route::post('add-department', [DepartmentController::class, 'store'])->name('admin/add-department');  
        Route::get('delete-department/{id}', [DepartmentController::class, 'delete'])->name('admin/delete-department'); 
        Route::get('status-department/{id}/{status}', [DepartmentController::class, 'status'])->name('admin/status-department'); 
        Route::get('designation', [DepartmentController::class, 'designationIndex'])->name('admin/designation');         
        Route::post('add-designation', [DepartmentController::class, 'designationStore'])->name('admin/add-designation');  
        Route::get('delete-designation/{id}', [DepartmentController::class, 'deletedesignation'])->name('admin/delete-designation'); 
        Route::get('status-designation/{id}/{status}', [DepartmentController::class, 'designationstatus'])->name('admin/status-designation');        
        Route::get('birthdayCalender',[EmployeeController::class, 'birthday'])->name('admin/birthdayCalender');
        Route::get('getnotification',[AdminDashboardController::class, 'notification'])->name('admin/getnotification');
        Route::post('updatenotification/{id}',[AdminDashboardController::class, 'updatenotification'])->name('admin/updatenotification');
        Route::get('mail', [EmployeeController::class, 'mail'])->name('admin/mail');
        Route::get('cards', [CardsController::class, 'index'])->name('admin/cards');
        Route::post('add-card', [CardsController::class, 'store'])->name('admin/add-card');
        Route::get('delete-card/{id}', [CardsController::class, 'delete'])->name('admin/delete-card');
        Route::post('update-card', [CardsController::class, 'store'])->name('admin/update-card');
        Route::get('assign-card', [CardsController::class, 'assign_card'])->name('admin/assign-card');
        Route::post('add-assign-card', [CardsController::class, 'assign_card_store'])->name('admin/add-assign-card');
        Route::get('delete-assign-card/{id}', [CardsController::class, 'assign_card_delete'])->name('admin/delete-assign-card');


    });
});

