<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Services\ETimeOfficeService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function __construct(ETimeOfficeService $eTimeOfficeService)
    {
        $this->eTimeOfficeService = $eTimeOfficeService;
    }

    public function index()
    {
        // $attendanceData = $this->eTimeOfficeService->getInOutPunchData('0027', '01/08/2024', '06/08/2024');
        // return $attendanceData ;
        $data = DB::select('SELECT id,first_name,last_name,empcode FROM users where role = '."'employee'");
        return view('admin/attendance', compact('data'));
    }

    public function inoutdata(Request $request)
    {
        // return $request->all();
        $code = $request->input('empcode');
        $fdate = $request->input('fdate');
        $tdate = $request->input('tdate');

        // Call the service method with the formatted dates
        $attendanceData = $this->eTimeOfficeService->getInOutPunchData($code, $fdate, $tdate);

        // Return the data as JSON
        return response()->json($attendanceData);
    }
}
