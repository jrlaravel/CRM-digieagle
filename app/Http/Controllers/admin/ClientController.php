<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\Main_service;
use App\Models\Sub_service;
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
    $Services = DB::select('SELECT id,main_service FROM main_service'); 
    return view('admin.service-list',compact('Services'));
}

public function getSubServices($id)
{
    // Fetch sub-services related to the selected main service
    $subServices = Sub_service::where('main_service_id', $id)->get();

    // Return sub-services as JSON
    return response()->json($subServices);
}


public function store(Request $request)
{
    // Validate the data
    $validator = Validator::make($request->all(), [
        'main_service' => 'required|string',
        'sub_services' => 'required|array|min:1',
        'sub_services.*' => 'required|string',
    ]);
    
    // Check if validation passes
    if ($validator->passes()) {
        DB::beginTransaction();
        
        try {
            // Create the main service (parent service)
            $mainService = Main_service::create([
                'main_service' => $request->main_service,
            ]);

            // Loop through the sub-services and add them (set the main service as parent)
            foreach ($request->sub_services as $subService) {
                Sub_service::create([
                    'sub_service' => $subService,
                    'main_service_id' => $mainService->id,  // Set the main service ID as parent for sub-services
                ]);
            }
            DB::commit();

            return redirect()->back()->with('success', 'Service and sub-services added successfully!');
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
        'service_id' => 'required|exists:services,id',  // Make sure the service exists
        'sub_service_name' => 'required|string|max:255',
    ]);

    $subService = new Sub_service();
    $subService->main_service_id = $validated['service_id'];
    $subService->name = $validated['sub_service_name'];
    $subService->save();

    return response()->json(['success' => true]);
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

public function storeSubService(Request $request)
{
    dd($request->all()); // For debugging

    $validated = $request->validate([
       'main_service_id' => 'required|exists:main_service,id',
        'sub_service' => 'required|string|max:255',
    ]);

    try {
        // Create the new sub-service
        Sub_service::create([
            'sub_service' => $request->sub_service,
            'main_service_id' => $request->main_service_id,
        ]);

        return response()->json(['success' => true, 'message' => 'Sub service added successfully']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Failed to add sub service']);
    }
}












    public function company_service(){
        $services = DB::select('select * from main_service');
        $details = DB::select("SELECT company_detail.id AS company_id, company_detail.name AS company_name, company_detail.industry AS company_industry, company_detail.description AS company_description, company_detail.note AS company_notes, GROUP_CONCAT(main_service.main_service SEPARATOR ', ') AS services_provided FROM company_services JOIN company_detail ON company_services.company_id = company_detail.id JOIN main_service ON company_services.service_id = main_service.id GROUP BY company_detail.id, company_detail.name, company_detail.industry, company_detail.description , company_detail.note");
        // dd($details);
        return view('admin/company_service',compact('services','details'));
    }

    public function create_company_service(Request $request)
    {
        // dd($request->all());
        // Validate the request input
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'industry' => 'required',
            'services' => 'required|array', // Ensure services is an array
            'services.*' => 'required|exists:main_service,id', // Validate that service IDs exist
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
            foreach ($request->services as $serviceId) {
                Company_service::create([
                    'company_id' => $company->id,
                    'service_id' => $serviceId,
                ]);
            }
    
            return redirect()->back()->with('message', 'Company successfully created');
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
                'services' => 'required|array', 
                 'services.*' => 'required|exists:main_service,id', // Ensure service IDs are valid
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
            foreach ($request->services as $serviceId) {
                Company_service::create([
                    'company_id' => $company->id,
                    'service_id' => $serviceId,
                ]);
            }
        
            return redirect()->back()->with('message', 'Company successfully updated');
        }

        public function delete_sub_service($id)
        {
            // Find the sub-service by ID
            $subService = Sub_service::find($id);
            $subService->delete();
            return redirect()->back()->with('message', 'Sub-service successfully deleted');
        }     
}
