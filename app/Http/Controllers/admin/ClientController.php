<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Service;
use App\Models\Company_detail;
use App\Models\Company_service;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class ClientController extends Controller
{
    public function index()
{
    // Get all main services
    $department = Department::all();
    return view('admin.service-list',compact('department'));
}

public function getSubServices($id)
{
    $services = Service::where('department_id', $id)->get();
    return response()->json($services);
}


public function store(Request $request)
{
    // Validate the data
    $validator = Validator::make($request->all(), [
        'department' => 'required|exists:department,id',
        'services' => 'required|array|min:1',
        'services.*' => 'required|string',
    ]);
    
    // Check if validation passes
    if ($validator->passes()) {
        DB::beginTransaction();
        try {
            $department = $request->department; 
            
            // Loop through the services and add them
            foreach ($request->services as $service) {
                Service::create([
                    'service_name' => $service,
                    'department_id' => $department,  // Set the department ID for each service
                ]);
            }

            DB::commit();
            
            return redirect()->back()->with('success', 'Services added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to add service. Please try again.');
        }
    } else {
        return redirect()->back()->withInput()->withErrors($validator);
    }
}

   
public function delete($id)
{
    // Delete the main service and all related sub-services
    $mainService = Main_service::find($id);
    $mainService->delete();

    return redirect()->route('admin/service-list')->with('success', 'Service and sub-services deleted successfully.');
}






public function addSubService(Request $request)
{
    $validated = $request->validate([
        'main_service_id' => 'required|exists:main_service,id',
        'sub_service' => 'required|string|max:255',
    ]);

    if($validated)
    {
        Sub_service::create([
            'sub_service' => $request->sub_service,
            'main_service_id' => $request->main_service_id,
        ]);
    
        return response()->json(['success' => true, 'message' => 'Sub service added successfully']);
    }
       else{

           return response()->json(['success' => false, 'message' => 'Failed to add sub service']);
       }
   
}

public function update(Request $request, $id)
{
    DB::beginTransaction();
    try {
        // Validate the request
        $request->validate([
            'sub_services' => 'array',
            'sub_services.*' => 'string|max:255',
            'new_sub_services' => 'array',
            'new_sub_services.*' => 'string|max:255',
        ]);

        // Update existing sub-services
        if ($request->filled('sub_services')) {
            foreach ($request->sub_services as $subServiceId => $subServiceName) {
                Sub_service::where('id', $subServiceId)->update([
                    'sub_service' => $subServiceName, // Make sure the field name matches your DB
                ]);
            }
        }

        // Add new sub-services
        if ($request->filled('new_sub_services')) {
            foreach ($request->new_sub_services as $newSubServiceName) {
                Sub_service::create([
                    'main_service_id' => $id, // Ensure the foreign key is correct
                    'sub_service' => $newSubServiceName, // Adjust the field name accordingly
                ]);
            }
        }

        DB::commit();
        return redirect()->back()->with('success', 'Sub-services updated successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        // Log the exception for debugging
        return redirect()->back()->with('error', 'Failed to update sub-services. Please try again.');
    }
}

public function company_service()
{
    $department = DB::select('select * from department');
    $details = DB::select("SELECT 
        company_detail.id AS company_id, 
        company_detail.name AS company_name, 
        company_detail.industry AS company_industry, 
        company_detail.description AS company_description, 
        company_detail.note AS company_notes, 
        GROUP_CONCAT(department.name SEPARATOR ', ') AS departments_provided 
    FROM 
        company_services 
    JOIN 
        company_detail ON company_services.company_id = company_detail.id 
    JOIN 
        department ON department.id = company_services.department_id 
    GROUP BY 
        company_detail.id, 
        company_detail.name, 
        company_detail.industry, 
        company_detail.description, 
        company_detail.note;
    ");
    return view('admin/company_service',compact('department','details'));
}

public function create_company_service(Request $request)
{
    // Validate the request input
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'description' => 'required',
        'industry' => 'required',
        'departments' => 'required|array', // Ensure department is an array
        'departments.*' => 'required|exists:department,id', // Validate that service IDs exist
    ]);
    
    if ($validator->fails()) {
        return redirect()->back()->withInput()->withErrors($validator);
    }
    
    $company = Company_detail::create([
        'name' => $request->name,
        'description' => $request->description,
        'industry' => $request->industry,
        'note' => $request->note,
    ]);
    
    // Add services to the company_service table
    foreach ($request->departments as $departmentId) {
        Company_service::create([
            'company_id' => $company->id,
            'department_id' => $departmentId,
        ]);
    }
    
    return redirect()->back()->with('success', 'Company successfully created');
}

public function delete_company_service($id)
{
    $company = Company_detail::find($id);
    $company->delete();
    return redirect()->back()->with('message', 'Company successfully deleted');
}

public function update_company_service(Request $request)
{
    // Validate the request input
    $validator = Validator::make($request->all(), [
        'company_id' => 'required|exists:company_detail,id',
        'company_name' => 'required|string|max:255',
        'company_industry' => 'required|string|max:255',
        'company_description' => 'required|string',
        'departments' => 'required|array', 
            'departments.*' => 'required|exists:department,id', // Ensure service IDs are valid
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withInput()->withErrors($validator);
    }

    // Find the company by ID
    $company = Company_detail::findOrFail($request->company_id);

    // Update the company details
    $company->update([
        'name' => $request->company_name,
        'description' => $request->company_description,
        'industry' => $request->company_industry,
        'note' => $request->company_notes,
    ]);

    // Remove existing services
    Company_service::where('company_id', $company->id)->delete();

    // Add new selected services
    foreach ($request->departments as $departmentId) {
        Company_service::create([
            'company_id' => $company->id,
            'department_id' => $departmentId,
        ]);
    }

    return redirect()->back()->with('message', 'Company successfully updated');
}


public function delete_service($id)
{
    // Find the sub-service by ID
    $subService = Service::find($id);
    $subService->delete();
    return redirect()->back()->with('message', 'service successfully deleted');
}     
}
