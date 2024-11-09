<?php

namespace App\Http\Controllers\employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Services\ETimeOfficeService;
use Carbon\Carbon;


class AttendanceController extends Controller
{
    private $eTimeOfficeService;
    
    public function __construct(ETimeOfficeService $eTimeOfficeService)
    {
        $this->eTimeOfficeService = $eTimeOfficeService;
    }

    public function inoutdata(Request $request)
    {
        $code = $request->input('empcode');
        $fdate = $request->input('fdate');
        $tdate = $request->input('tdate');

        $attendanceData = $this->eTimeOfficeService->getInOutPunchData($code, $fdate, $tdate);

        // Return the data as JSON
        return response()->json($attendanceData);
    }

}
