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
use App\Http\Controllers\employee\HrCardsController;
use App\Http\Controllers\admin\LeaveController;
use App\Http\Controllers\admin\ProjectController;
use App\Http\Controllers\admin\TaskController;
use App\Http\Controllers\admin\ClientController;
use App\Http\Controllers\admin\LeadController;
use App\Http\Controllers\employee\EmpLeadController;
use App\Http\Controllers\employee\EmployeeLeaveController;
use App\Http\Controllers\employee\ProjectController as EmployeeProjectController;
use App\Http\Controllers\employee\HRleaveController;
use App\Http\Controllers\employee\HREmployeeController;
use App\Http\Controllers\employee\WorkReportController;
use App\Http\Controllers\employee\HRRequirmentController;
use App\Http\Controllers\admin\RequirmentController;



Route::get('/', [LoginController::class, 'index'])->name('emp/login'); 

Route::get('add-candidate/{token}', [HRRequirmentController::class, 'add_candidate'])->name('add-candidate');
Route::post('add-candidate-data', [HRRequirmentController::class, 'store_candidate'])->name('add-candidate-data');

Route::prefix('emp')->group(function () {

    Route::group(['middleware' => 'emp.guest'],function () {
        Route::post('authenticate', [LoginController::class, 'authenticate'])->name('emp/authenticate');
        Route::get('/resetpassword', [LoginController::class, 'resetpassword'])->name('emp/resetpassword');
        Route::post('/varify-email', [LoginController::class, 'varifyemail'])->name('emp/varify-email');
        Route::get('/new-password/{token}', [LoginController::class, 'newpassword'])->name('emp/new-password');
        Route::post('/new-password', [LoginController::class, 'updatepassword'])->name('emp/reset-password');
    });

    Route::group(['middleware' => ['emp.auth', 'bde.access']], function () {
        Route::get('lead', [EmpLeadController::class, 'index'])->name('emp/lead');
        Route::post('lead', [EmpLeadController::class, 'store'])->name('emp/add-lead');
        Route::get('lead-list', [EmpLeadController::class, 'show'])->name('emp/lead-list');
        Route::get('lead-delete/{id}', [EmpLeadController::class, 'delete'])->name('emp/lead-delete');
        Route::post('lead-update', [EmpLeadController::class, 'update'])->name('emp/lead-update');
        Route::get('lead-datail/{id}', [EmpLeadController::class, 'lead_datail'])->name('emp/lead-datail');
        Route::post('add-followup', [EmpLeadController::class, 'createOrUpdateFollowup'])->name('emp/add-followup');
        Route::get('delete-followup/{id}', [EmpLeadController::class, 'delete_followup'])->name('emp/delete-followup');
        Route::post('update-followup', [EmpLeadController::class, 'createOrUpdateFollowup'])->name('emp/update-followup');
        Route::post('/upload-excel', [EmpLeadController::class, 'uploadExcel'])->name('emp/uploadexcel');
        Route::get('download-excel', [EmpLeadController::class, 'downloadExcel'])->name('emp/downloadexcel');
        Route::get('meeting_details', [EmpLeadController::class, 'meetingDetails'])->name('emp/meeting_details');
        Route::post('/client-meeting-store', [EmpLeadController::class, 'meetingStore'])->name('emp/client-meeting-store');
        Route::get('/client-meeting-delete/{id}', [EmpLeadController::class, 'meetingDelete'])->name('emp/client-meeting-delete');
        Route::post('client-meeting-update', [EmpLeadController::class, 'meetingUpdate'])->name('emp/client-meeting-update');
        Route::get('add-client-details', [EmpLeadController::class, 'AddClientDetails'])->name('emp/add-client-details');
        Route::post('get-question', [EmpLeadController::class, 'GetQuestion'])->name('emp/get-question');
        Route::post('store-answer', [EmpLeadController::class, 'StoreAnswer'])->name('emp/store-answer');
    });

    Route::group(['middleware' => ['emp.auth', 'check.hr']], function () {
        Route::get('leave-type', [HRleaveController::class, 'index'])->name('emp/leave-type');
        Route::post('add-leavetype', [HRleaveController::class, 'store'])->name('emp/add-leavetype');
        Route::get('delete-leave-type/{id}', [HRleaveController::class, 'delete'])->name('emp/delete-leave-type');
        Route::post('edit-leave-type', [HRleaveController::class, 'store'])->name('emp/edit-leave-type');
        Route::get('leave-list', [HRleaveController::class, 'leave'])->name('emp/leave-list');
        Route::get('leave-delete/{id}', [HRleaveController::class, 'leavedelete'])->name('emp/leave-delete');
        Route::post('leave-update/{id}', [HRleaveController::class, 'leaveupdate'])->name('emp/leave-update');
        Route::get('add-emp', [HREmployeeController::class, 'index'])->name('emp/add-emp'); 
        Route::get('get-designations', [EmployeeController::class, 'getDesignations'])->name('emp/get-designations');
        Route::post('add-emp-data', [HREmployeeController::class, 'store'])->name('emp/add-emp-data'); 
        Route::get('list-emp', [HREmployeeController::class, 'show'])->name('emp/list-emp'); 
        Route::get('delete-emp-data/{id}', [HREmployeeController::class, 'delete'])->name('emp/delete-emp-data'); 
        Route::get('edit-emp-data/{id}', [HREmployeeController::class, 'edit'])->name('emp/edit-emp-data'); 
        Route::post('update-emp-data', [HREmployeeController::class, 'update'])->name('emp/update-emp-data');
        Route::get('candidate-list', [HRRequirmentController::class, 'index'])->name('emp/candidate-list');
        Route::post('generate-link', [HRRequirmentController::class, 'add'])->name('emp/generate-link');
        Route::get('delete-link/{id}', [HRRequirmentController::class, 'delete'])->name('emp/delete-link');
        Route::get('candidate-details', [HRRequirmentController::class, 'candidate_details'])->name('emp/candidate-details');
        Route::get('view-candidate/{id}', [HRRequirmentController::class, 'view_candidate'])->name('emp/view-candidate');
        Route::get('delete-candidate-details/{id}', [HRRequirmentController::class, 'candidate_details_delete'])->name('emp/delete-candidate-details');
        Route::post('assign-candidate-details', [HRRequirmentController::class, 'assign_candidate_details'])->name('emp.assign-candidate-details');
        Route::get('candidate-cv-list', [HRRequirmentController::class, 'cv_list'])->name('emp/candidate-cv-list');
        Route::post('add-cv',[HRRequirmentController::class, 'store_cv'])->name('emp/add-cv');
        Route::get('website-cv-list', [HRRequirmentController::class, 'website_cv_list'])->name('emp/website-cv-list'); 
        Route::post('reject-cv', [HRRequirmentController::class,'rejectCv'])->name('emp/reject-cv');   
        Route::get('reject-cv-list', [HRRequirmentController::class,'rejectCvList'])->name('emp/reject-cv-list'); 
        Route::post('delete-cv', [HRRequirmentController::class, 'deleteCv'])->name('emp/delete-cv');
        Route::post('interview-schedule',[HRRequirmentController::class, 'interview_schedule'])->name('emp/interview-schedule');
        Route::post('edit-interview-schedule',[HRRequirmentController::class ,'edit_interview_schedule'])->name('emp/edit-interview-schedule');
        Route::post('add-candidate-followup', [HRRequirmentController::class, 'add_followup'])->name('emp/add-candidate-followup');
        Route::get('cards', [HrCardsController::class, 'index'])->name('emp/cards');
        Route::post('add-card', [HrCardsController::class, 'store'])->name('emp/add-card');
        Route::get('delete-card/{id}', [HrCardsController::class, 'delete'])->name('emp/delete-card');
        Route::post('update-card', [HrCardsController::class, 'store'])->name('emp/update-card');
        Route::get('assign-card', [HrCardsController::class, 'assign_card'])->name('emp/assign-card');
        Route::post('add-assign-card', [HrCardsController::class, 'assign_card_store'])->name('emp/add-assign-card');
        Route::get('delete-assign-card/{id}', [HrCardsController::class, 'assign_card_delete'])->name('emp/delete-assign-card');
        Route::get('media-manager',[HREmployeeController::class, 'MediaManager'])->name('emp/media-manager');
        Route::post('upload-media', [HREmployeeController::class, 'uploadMedia'])->name('emp/upload-media');
        Route::DELETE('delete-media/{id}', [HREmployeeController::class, 'deleteMedia'])->name('emp/delete-media');

    });


    Route::group(['middleware' => 'emp.auth'],function () {
        Route::post('changepassword', [LoginController::class , 'changepassword'])->name('emp/changepassword');
        Route::get('logout', [LoginController::class, 'logout'])->name('emp/logout'); 
        Route::get('dashboard', [EmployeeDashboardController::class, 'index'])->name('emp/dashboard'); 
        Route::get('calendar', [EmployeeDashboardController::class, 'calendar'])->name('emp/calendar'); 
        Route::get('profile', [EmployeeDashboardController::class, 'profile'])->name('emp/profile');
        Route::post('Updateprofile', [EmployeeDashboardController::class, 'updateProfile'])->name('emp/Updateprofile');
        Route::get('attendance', [EmployeeDashboardController::class, 'attendance'])->name('emp/attendance');
        Route::post('/inoutdata', [AttendanceController::class, 'inoutdata'])->name('emp/inoutdata');;
        Route::post('/download-pdf', [AttendanceController::class, 'downloadPDF'])->name('download.pdf');
        Route::post('profilephoto', [EmployeeDashboardController::class, 'profilePhoto'])->name('emp/profilephoto'); 
        Route::get('leave', [EmployeeLeaveController::class, 'index'])->name('emp/leave');
        Route::post('leave', [EmployeeLeaveController::class, 'store'])->name('emp/leave-store');
        Route::get('leave/{id}', [EmployeeLeaveController::class, 'delete'])->name('emp/leave-delete');
        Route::get('getnotification',[EmployeeDashboardController::class, 'notification'])->name('emp/getnotification');
        Route::post('updatenotification/{id}',[EmployeeDashboardController::class, 'updatenotification'])->name('emp/updatenotification');
        Route::get('projects',[EmployeeProjectController::class, 'index'])->name('emp/projects');
        Route::get('work-report', [WorkReportController::class, 'index'])->name('emp/work-report');
        Route::get('/get-services/{companyId}', [WorkReportController::class, 'getServices'])->name('emp/get-services');
        Route::post('add-work-report', [WorkReportController::class, 'add_work_report'])->name('emp/add-work-report');
        Route::get('work-report-history', [WorkReportController::class, 'get_word_report'])->name('emp/work-report-history');
        Route::post('/delete-all-report', [WorkReportController::class, 'deleteAllReports'])->name('emp/deleteAllReport');
        Route::get('/delete-report-task/{id}', [WorkReportController::class, 'deletetask'])->name('emp/delete-report-task');
        Route::get('/work-report-detail/{date}', [WorkReportController::class, 'getWorkReportByDate'])->name('emp/work-report-detail');
        Route::get('edit-work-report/{id}', [WorkReportController::class, 'edit_work_report'])->name('emp/edit-work-report');
        Route::post('update-work-report', [WorkReportController::class, 'update_work_report'])->name('emp/update-work-report');
        Route::post('add-task-report', [WorkReportController::class, 'add_task_report'])->name('emp/add-task-report');
    });
});

Route::prefix('admin')->group(function () {

    Route::group(['middleware' => 'admin.guest'],function () {
        Route::get('', [AdminLoginController::class, 'index'])->name('admin/login'); 
        Route::post('authenticate', [AdminLoginController::class, 'authenticate'])->name('admin/authenticate'); 
       
    });

    Route::group(['middleware' => 'admin.auth'],function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin/dashboard'); 
        Route::get('get_follow_up', [AdminDashboardController::class, 'get_follow_up'])->name('admin/get_follow_up'); 
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
        Route::get('Calender',[EmployeeController::class, 'calender'])->name('admin/Calender');
        Route::get('getnotification',[AdminDashboardController::class, 'notification'])->name('admin/getnotification');
        Route::get('mail', [EmployeeController::class, 'mail'])->name('admin/mail');
        Route::get('cards', [CardsController::class, 'index'])->name('admin/cards');
        Route::post('add-card', [CardsController::class, 'store'])->name('admin/add-card');
        Route::get('delete-card/{id}', [CardsController::class, 'delete'])->name('admin/delete-card');
        Route::post('update-card', [CardsController::class, 'store'])->name('admin/update-card');
        Route::get('assign-card', [CardsController::class, 'assign_card'])->name('admin/assign-card');
        Route::post('add-assign-card', [CardsController::class, 'assign_card_store'])->name('admin/add-assign-card');
        Route::get('delete-assign-card/{id}', [CardsController::class, 'assign_card_delete'])->name('admin/delete-assign-card');
        Route::get('leave-type', [LeaveController::class, 'index'])->name('admin/leave-type');
        Route::post('add-leavetype', [LeaveController::class, 'store'])->name('admin/add-leavetype');
        Route::get('delete-leave-type/{id}', [LeaveController::class, 'delete'])->name('admin/delete-leave-type');
        Route::post('edit-leave-type', [LeaveController::class, 'store'])->name('admin/edit-leave-type');
        Route::get('leave', [LeaveController::class, 'leave'])->name('admin/leave');
        Route::get('leave-delete/{id}', [LeaveController::class, 'leavedelete'])->name('admin/leave-delete');
        Route::post('leave-update/{id}', [LeaveController::class, 'leaveupdate'])->name('admin/leave-update');
        Route::get('project-type', [ProjectController::class, 'index'])->name('admin/project-type');
        Route::post('add-project-type', [ProjectController::class, 'store'])->name('admin/add-project-type');
        Route::get('delete-project-type/{id}', [ProjectController::class, 'delete'])->name('admin/delete-project-type');
        Route::get('add-project-detail', [ProjectController::class, 'project_add'])->name('admin/add-project-detail');
        Route::post('add-project-detail', [ProjectController::class, 'project_add_detail'])->name('admin/add-project-detail');
        Route::get('list-project-detail', [ProjectController::class, 'project_list'])->name('admin/list-project-detail');
        Route::get('delete-project-detail/{id}', [ProjectController::class, 'project_delete_detail'])->name('admin/delete-project-detail');
        Route::post('/projects-detail-update', [ProjectController::class, 'update'])->name('admin/project-detail-update');
        Route::post('/project-user-update', [ProjectController::class, 'updateAssignedUsers'])->name('admin/project-user-update');
        Route::get('festival-leave',[LeaveController::class, 'festival_leave'])->name('admin/festival-leave');
        Route::post('festival-leave-create',[LeaveController::class, 'festival_leave_create'])->name('admin/festival-leave-create');
        Route::get('festival-leave-delete/{id}',[LeaveController::class, 'festival_leave_delete'])->name('admin/festival-leave-delete');
        Route::post('festival-leave-update',[LeaveController::class, 'festival_leave_update'])->name('admin/festival-leave-update');
        Route::get('lead',[LeadController::class, 'index'])->name('admin/lead');
        Route::post('lead',[LeadController::class, 'store'])->name('admin/add-lead');
        Route::post('/upload-excel', [LeadController::class, 'uploadExcel'])->name('admin/uploadexcel');
        Route::get('download-excel', [LeadController::class, 'downloadExcel'])->name('admin/downloadexcel');
        Route::get('lead-list',[LeadController::class, 'show'])->name('admin/lead-list');
        Route::get('lead-delete/{id}',[LeadController::class, 'delete'])->name('admin/lead-delete');
        Route::post('lead-update',[LeadController::class, 'update'])->name('admin/lead-update');
        Route::get('lead-datail/{id}',[LeadController::class, 'lead_datail'])->name('admin/lead-datail');
        Route::post('add-followup',[LeadController::class, 'createOrUpdateFollowup'])->name('admin/add-followup');
        Route::get('delete-followup/{id}',[LeadController::class, 'delete_followup'])->name('admin/delete-followup');
        Route::post('update-followup',[LeadController::class, 'createOrUpdateFollowup'])->name('admin/update-followup');
        Route::get('task/{id}',[TaskController::class, 'index'])->name('admin/task');
        Route::get('service-list',[ClientController::class, 'index'])->name('admin/service-list');
        Route::post('add-service', [ClientController::class, 'store'])->name('admin/add-service');  
        Route::get('delete-service/{id}', [ClientController::class, 'delete'])->name('admin/delete-service');
        Route::get('/services/{id}', [ClientController::class, 'getSubServices'])->name('admin/services');
        Route::get('company-service', [ClientController::class, 'company_service'])->name('admin/company-service');  
        Route::post('add-company-service', [ClientController::class, 'create_company_service'])->name('admin/add-company-service');  
        Route::get('delete-company-service/{id}', [ClientController::class, 'delete_company_service'])->name('admin/delete-company-service');
        Route::post('update-company-service', [ClientController::class, 'update_company_service'])->name('admin/update-company-service');
        Route::get('delete-service/{id}', [ClientController::class, 'delete_service'])->name('admin/delete-service');
        Route::post('add-status' , [ClientController::class, 'add_status'])->name('admin/add-status');
        Route::get('status/{id}' , [ClientController::class, 'get_status'])->name('admin/status');
        Route::get('delete-status/{id}' , [ClientController::class, 'delete_status'])->name('admin/delete-status');
        Route::get('work-report' , [EmployeeController::class, 'work_report'])->name('admin/work-report');
        Route::post('get-work-report', [EmployeeController::class, 'get_work_report'])->name('admin/get-work-report');
        Route::get('/work-report-detail/{date}/{id}', [EmployeeController::class, 'getWorkReportByDate'])->name('admin/work-report-detail');
        Route::post('report-download', [EmployeeController::class, 'report_download'])->name('admin/report-download');
        Route::get('activity_log', [EmployeeController::class, 'activity_log'])->name('admin/activity_log');
        Route::post('activity-log/download', [EmployeeController::class, 'downloadActivityLogPDF'])->name('admin/activity_log/download');
        Route::get('candidate-list', [RequirmentController::class, 'index'])->name('admin/candidate-list');
        Route::post('generate-link', [RequirmentController::class, 'add'])->name('admin/generate-link');
        Route::get('delete-link/{id}', [RequirmentController::class, 'delete'])->name('admin/delete-link');
        Route::get('candidate-details', [RequirmentController::class, 'candidate_details'])->name('admin/candidate-details');
        Route::get('view-candidate/{id}', [RequirmentController::class, 'view_candidate'])->name('admin/view-candidate');
        Route::get('delete-candidate-details/{id}', [RequirmentController::class, 'candidate_details_delete'])->name('admin/delete-candidate-details');
        Route::get('hosting_data', [ClientController::class, 'hosting_data'])->name('admin/hosting_data');
        Route::post('add-hosting-data', [ClientController::class, 'hosting_data_store'])->name('admin/add-hosting-data');
        Route::delete('delete-hosting-data', [ClientController::class, 'hosting_data_delete'])->name('admin/delete-hosting-data');
        Route::post('update-hosting-data', [ClientController::class, 'update_hosting_data'])->name('admin/update-hosting-data');
        Route::post('assign-candidate-details', [RequirmentController::class, 'assign_candidate_details'])->name('admin.assign-candidate-details');
        Route::post('add-candidate-followup', [RequirmentController::class, 'add_followup'])->name('admin/add-candidate-followup');
        Route::get('lead_questions',[LeadController::class, 'lead_question'])->name('admin/lead_questions');
        Route::post('add_lead_question',[LeadController::class, 'add_lead_question'])->name('admin/add_lead_question');
        Route::delete('/delete-lead-question/{id}', [LeadController::class, 'delete_lead_question'])->name('admin/delete-lead-question');
        Route::put('/update-lead-question/{id}', [LeadController::class, 'update_lead_question'])->name('admin/update-lead-question');
        Route::get('media-manager',[EmployeeController::class, 'MediaManager'])->name('admin/media-manager');
        Route::post('upload-media', [EmployeeController::class, 'uploadMedia'])->name('admin/upload-media');
        Route::DELETE('delete-media/{id}', [EmployeeController::class, 'deleteMedia'])->name('admin/delete-media');
    });
});

