<?php

namespace App\Http\Controllers\employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Services\ETimeOfficeService;
use Carbon\Carbon;


class AttendanceController extends Controller
{
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

    public function downloadPDF(Request $request)
{
    $data = $request->input('data'); // Get JSON data from the request
    
    // Optionally decode JSON to an array
    $data = json_decode($data, true);

    // Load the view with data
    $pdf = Pdf::loadView('your-pdf-view', compact('data'));

    // Download the generated PDF
    return $pdf->download('data.pdf');
}
}
