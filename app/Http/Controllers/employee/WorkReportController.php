<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company_detail;
use App\Models\Sub_service;
use App\Models\User;
use App\Models\Work_Report;
use App\Models\Work_report_detail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WorkReportController extends Controller
{
    public function index()
    {
        $companydata = DB::select('select id,name from company_detail');
        return view('employee/work-report', compact('companydata'));
    }

    public function getServices($companyId)
    {
        $services = DB::select('SELECT company_id,sub_service,sub_service.id as serviceid FROM `company_services` JOIN main_service on company_services.service_id = main_service.id join sub_service on sub_service.main_service_id = main_service.id WHERE company_id = '.$companyId);
        return response()->json(['services' => $services]);
    }


    public function add_work_report(Request $request)
    {
        // return $request->all();  
        // Get the report data and other fields from the request
        $reportData = $request->input('report_data');
        $date = $request->input('report_date');
        $user_id = $request->input('user_id');    
        
        // Get today's date for comparison
        $today = Carbon::today()->toDateString(); // Get today's date in YYYY-MM-DD format
    
        // Check if all required fields are present
        if (!$reportData || empty($reportData) || !$date || !$user_id) {
            return response()->json(['error' => 'Missing required fields'], 400);
        }
    
        // Check if the report date is today or if the user is allowed to add tasks to reports of other dates
        if ($date !== $today) {
            return response()->json(['error' => 'You can only add tasks to today\'s report'], 400);
        }
    
        // Check if a report for this user and date already exists
        $workReport = Work_Report::where('user_id', $user_id)->where('report_date', $date)->first();
        if (!$workReport) {
            // Create a new entry in Work_Report
            try {
                $workReport = Work_Report::create([
                    'user_id' => $user_id,
                    'report_date' => $date,
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error creating work report: ' . $e->getMessage()], 500);
            }
        } else {
            // Report for this date already exists, proceed to adding tasks
        }
    
        // Loop through the reportData to handle multiple rows
        foreach ($reportData as $data) {
            // Validate each report data row
            if (!isset($data['companyName'], $data['serviceName'], $data['startTime'], $data['endTime'], $data['status'])) {
                return response()->json(['error' => 'Missing data in one or more rows'], 400);
            }
    
            // Retrieve company_id by company name
            $company = Company_Detail::where('name', $data['companyName'])->first();
            if (!$company) {
                return response()->json(['error' => 'Company not found for: ' . $data['companyName']], 404);
            }
    
            // Retrieve service_id by service name
            $service = Sub_service::where('sub_service', $data['serviceName'])->first();
            if (!$service) {
                return response()->json(['error' => 'Service not found for: ' . $data['serviceName']], 404);
            }
    
            // Calculate total time dynamically
            try {
                $start_time = Carbon::parse($data['startTime']);
                $end_time = Carbon::parse($data['endTime']);
                $total_time_in_seconds = $end_time->diffInSeconds($start_time);
                $total_time = gmdate('H:i:s', $total_time_in_seconds); // Format total time as H:i:s
            } catch (\Exception $e) {
                return response()->json(['error' => 'Invalid time format for row: ' . json_encode($data)], 400);
            }
    
            // Insert data into Work_Report_Detail table
            try {
                Work_Report_Detail::create([
                    'date_id' => $workReport->id, // Link with the Work_Report
                    'company_id' => $company->id,
                    'service_id' => $service->id,
                    'status' => $data['status'],
                    'note' => $data['note'],
                    'start_time' => $start_time->format('H:i:s'),
                    'end_time' => $end_time->format('H:i:s'),
                    'total_time' => $total_time,
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error inserting data into details: ' . $e->getMessage()], 500);
            }
        }
    
        return response()->json(['success' => 'Report added successfully']);
    }
    

    public function get_word_report(Request $request)
    {
        $userId = session('employee')->id;
    
        // Get the current year and month
        $currentMonth = date('Y-m'); // This will give the format YYYY-MM
    
        // Modify the query to filter by current month
        $data = DB::select('
           SELECT 
            wr.id as report_id,
            wr.report_date AS report_date, 
            GROUP_CONCAT(DISTINCT cd.name ORDER BY cd.name ASC) AS company_list, 
            SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(wrd.end_time, wrd.start_time)))) AS total_time 
            FROM work_report wr 
            JOIN work_report_detail wrd ON wr.id = wrd.date_id 
            JOIN company_detail cd ON wrd.company_id = cd.id 
            WHERE wr.user_id = :userId 
            AND DATE_FORMAT(wr.report_date, "%Y-%m") = :currentMonth 
            GROUP BY wr.report_date, wr.id
            ORDER BY wr.report_date ASC', 
            ['userId' => $userId, 'currentMonth' => $currentMonth]
        );
    
        return view('employee.work-report-history', compact('data'));
    }
    

    public function getWorkReportByDate($date)
    {
        // Fetch data from the database based on the date
        $reportDetails = DB::table('work_report_detail as wrd')
            ->join('company_detail as cd', 'wrd.company_id', '=', 'cd.id')
            ->join('sub_service as ss', 'wrd.service_id', '=', 'ss.id')
            ->join('work_report as wr' , 'wrd.date_id','=', 'wr.id')
            ->select(
                'cd.name as client_name',
                'ss.sub_service as task_name',
                'wrd.start_time',
                'wrd.end_time',
                DB::raw("CASE 
                            WHEN wrd.status = 'completed' THEN 'success'
                            WHEN wrd.status = 'pending' THEN 'danger'
                            ELSE 'warning'
                        END as status_class"),
                'wrd.status'
            )
            ->where('wr.report_date', $date)
            ->get();    

        return response()->json([
            'details' => $reportDetails,
        ]);
    }
    
    public function deletetask($id)
    {
        $data = Work_report_detail::find($id);
        $data->delete();
        return redirect()->back()->with('success', 'Task deleted successfully.');
    }

    public function edit_work_report($id)
    {
        // Get today's date
        $today = Carbon::today()->format('Y-m-d');
        
        // Fetch company data
        $companydata = DB::select('SELECT id, name FROM company_detail');
        
        // Fetch report data only if the report's date matches today's date
        $data = DB::select("
            SELECT 
                wr.report_date AS date,
                wr.id AS report_id,
                wrd.note AS note,
                wrd.id AS wrdid,
                cd.id AS cid,
                cd.name AS cname,
                ss.sub_service AS sname,
                ss.id AS sid,
                wrd.status,
                wrd.start_time,
                wrd.end_time,
                TIMEDIFF(wrd.end_time, wrd.start_time) AS total_time
            FROM 
                work_report wr
            JOIN 
                work_report_detail wrd ON wr.id = wrd.date_id
            JOIN 
                company_detail cd ON wrd.company_id = cd.id
            JOIN 
                sub_service ss ON wrd.service_id = ss.id
            WHERE 
                wr.id = :id AND wr.report_date = :today
        ", ['id' => $id, 'today' => $today]);
    
        // Check if data is empty (no report for today's date)
        if (empty($data)) {
            return redirect()->back()->with('error', 'No work report found');
        }
    
        // Return view with data
        return view('employee/edit-work-report', compact('data', 'companydata'));
    }
    

    public function add_task_report(Request $request)
    {
        $workReport = Work_report::where('report_date', $request->report_date)->first();
        if($workReport == '')
        {
            return redirect()->back()->with('error', 'Report not found');
        }
        else{
            $workReportDetail = new Work_report_detail();
            $workReportDetail->date_id = $workReport->id;
            $workReportDetail->company_id = $request->company_name;
            $workReportDetail->service_id = $request->service;
            $workReportDetail->status = $request->status;
            $workReportDetail->note = $request->note;
            $workReportDetail->start_time = $request->start_time;
            $workReportDetail->end_time = $request->end_time;
            $workReportDetail->total_time = $request->total_time;
            $workReportDetail->save();
            return redirect()->back()->with('success', 'Task added successfully.');
        }
    }

    public function update_work_report(Request $request)
    {
        // Find the Work_report by the given report date
        // return $request->all();
        $workReport = Work_report::where('report_date', $request->report_date)->first();
        if($workReport == '')
        {
            return redirect()->back()->with('error', 'Report not found');
        }
        else{

            $company_id = is_array($request->company_name) ? $request->company_name[0] : $request->company_name;
            $service_id = is_array($request->service) ? $request->service[0] : $request->service;
            // Check if the fields are arrays, and extract the first element
            $start_time = is_array($request->start_time) ? $request->start_time[0] : $request->start_time;
            $end_time = is_array($request->end_time) ? $request->end_time[0] : $request->end_time;
            $status = is_array($request->status) ? $request->status[0] : $request->status;
            $note = is_array($request->note) ? $request->note[0] : $request->note;
            $total_time = is_array($request->total_time) ? $request->total_time[0] : $request->total_time;


            $workReportDetail = Work_report_detail::find($request->wrdid);
            $workReportDetail->company_id = $company_id;
            $workReportDetail->service_id = $service_id;
            $workReportDetail->start_time = $start_time;
            $workReportDetail->end_time = $end_time;
            $workReportDetail->status = $status;        
            $workReportDetail->note = $note;    
            $workReportDetail->total_time = $total_time;
            $workReportDetail->save();
            return redirect()->back()->with('success', 'Task updated successfully.');
        }
    }

}
