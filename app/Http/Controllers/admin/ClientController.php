<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Service;
use App\Models\Status;
use App\Models\Company_detail;
use App\Models\Company_service;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\HostingAndDomain; 


class ClientController extends Controller
{
    public function index()
    {
        // Get all main status
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
            'department_id' => 'required|exists:department,id',
            'services' => 'required|array|min:1',
            'services.*' => 'required|string',
        ]);
        
        // Check if validation passes
        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $department = $request->department_id; 
                
                // Loop through the services and add them
                foreach ($request->services as $service) {
                    Service::create([
                        'service_name' => $service,
                        'department_id' => $department,  // Set the department ID for each service
                    ]);
                }
                
                DB::commit();
                
                // return $request->all();
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
            company_detail.note
        ORDER BY company_detail.id DESC;
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

    public function add_status(Request $request)
    {
        // return $request->all();
        // Validate the data
        $validator = Validator::make($request->all(), [
            'department_id' => 'required|exists:department,id',
            'status' => 'required|array|min:1',
            'status.*' => 'required|string',
        ]);
        
        // Check if validation passes
        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $department = $request->department_id; 
                
                // Loop through the status and add them
                foreach ($request->status as $status) {
                    Status::create([
                        'name' => $status,
                        'department_id' => $department,  // Set the department ID for each statu$status
                    ]);
                }
                
                DB::commit();
                
                // return $request->all();
                return redirect()->back()->with('success', 'status added successfully!');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Failed to add statu$status. Please try again.');
            }
        } else {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function get_status($id)
    {
        $status = Status::where('department_id', $id)->get();
        return response()->json($status);
    }

    public function delete_status($id)
    {
        // Delete the main status and all related sub-status
        $mainStatus = Status::find($id);
        $mainStatus->delete();

        return redirect()->route('admin/service-list')->with('success', 'Status deleted successfully.');
    }

    public function hosting_data()
    {
        $data = HostingAndDomain::all();
        return view('admin/hosting_and_domain',compact('data'));
    }
    
    public function hosting_data_store(Request $request)
    {
        // Validate the incoming data with custom messages
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
        ], [
            // Grouped custom error messages
            '*.required' => 'This field is required.',
        ]);
        
        
        try {
            // Handle file upload for the client logo
            $clientLogoPath = null;
        
            // Check if the file exists in the request
            if ($request->hasFile('client_logo')) {
                // Define the folder path
                $folderPath = 'client_logos';
                
                // Check if the folder exists, if not, create it
                if (!Storage::exists($folderPath)) {
                    Storage::makeDirectory($folderPath);
                }
                
                // Store the file in the 'public' disk
                $clientLogoPath = $request->file('client_logo')->store($folderPath, 'public');
                
                // Ensure the logo was stored successfully
                if (!$clientLogoPath) {
                    throw new \Exception('Failed to store client logo.');
                }
            }
        
            try {
                HostingAndDomain::create([
                    'client_name' => $validated['client_name'],
                    'logo' => $clientLogoPath,
                    'domain_name' => $validated['domain_name'],
                    'domain_purchase_from' => $validated['domain_purchase_from'] ?? null,
                    'domain_purchase_date' => $validated['domain_purchase_date'] ?? null,
                    'domain_expire_date' => $validated['domain_expire_date'] ?? null,
                    'domain_amount' => $validated['domain_amount'] ?? null,
                    'domain_email' => $validated['domain_email'] ?? null,
                    'domain_id' => $validated['domain_id'] ?? null,
                    'domain_password' => $validated['domain_password'] ?? null,
                    'hosting_purchase_from' => $validated['hosting_purchase_from'] ?? null,
                    'hosting_link' => $validated['hosting_link'] ?? null,
                    'hosting_purchase_date' => $validated['hosting_purchase_date'] ?? null,
                    'hosting_expire_date' => $validated['hosting_expire_date'] ?? null,
                    'hosting_amount' => $validated['hosting_amount'] ?? null,
                    'hosting_email' => $validated['hosting_email'] ?? null,
                    'hosting_id' => $validated['hosting_id'] ?? null,
                    'hosting_password' => $validated['hosting_password'] ?? null,
                ]);
        
                return redirect()->back()->with('success', 'Hosting data added successfully!');
            } catch (\Exception $e) {
                // Log the error and show a user-friendly error message
                \Log::error('Error saving hosting data: ' . $e->getMessage());
                return redirect()->back()->withErrors(['error' => 'Failed to save hosting data.'])->withInput();
            }
        } catch (\Exception $e) {
            // Log the error if file upload fails
            \Log::error('Error uploading client logo: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to upload client logo.'])->withInput();
        }
        
    }

    public function hosting_data_delete(Request $request)
    {
        $id = $request->input('client_id');
        
        // Find the client by ID and delete it
        $client = HostingAndDomain::find($id);
        if ($client) {
            $client->delete();
            return redirect()->back()->with('success', 'Client deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Client not found!');
        }
    }

    public function update_hosting_data(Request $request)
    {
        // Retrieve the record by ID
        $hostingData = HostingAndDomain::findOrFail($request->id);
    
        // Update the record with the request data
        $hostingData->update($request->all());
    
        // Return a success response
        return redirect()->back()->with('success', 'Client hosting information updated successfully');
    } 

}
