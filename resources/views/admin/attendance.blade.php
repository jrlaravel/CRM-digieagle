@extends('layout/admin-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        <img src="{{asset('storage/profile_photos').'/'.session('user')->profile_photo_path}}" class="avatar img-fluid rounded me-1"  />
    </div>
    <div class="flex-grow-1 ps-2">
       
           <p class="text-white">{{session('user')->first_name}}</p>
        {{-- <div class="sidebar-user-subtitle">Designer</div> --}}
    </div>
</div>
@endsection
@section('content')
    <style>
        .table-container {
            overflow: auto;
             height: 500px;
        }

        .table {
            width: 100%;
            border-collapse: collapse; /* Optional: Improve table layout */
        }

        .table th, .table td {
            padding: 8px; /* Adjust padding as needed */
            border: 1px solid #ddd; /* Optional: Add border for better visibility */
        }

        .table thead th {
            position: sticky;
            top: 0;
            z-index: 1;
            background-color: #f8f9fa; /* Optional: Header background color */
        }
    </style>
<div class="container-fluid p-0">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="mb-3">
        {{-- <h1 class="h3 d-inline align-middle">Attendance Report</h1>     --}}
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title float-start">Attendance Report</h5>
                <button class="btn btn-primary float-end" id="download-button">Download</button>
            </div>
            <div class="card-body">
                <form id="inoutForm" method="POST" action="{{route('admin/inoutdata')}}">
                    @csrf  
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputAddress">Select Employee</label>
                            <select name="employee" class="form-control" id="employee">
                                <option>Selelct Employee</option>
                                @foreach($data as $employee)
                                <option value="{{$employee->empcode}}">{{$employee->first_name.' '.$employee->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputAddress">From date</label>
                            <input type="text" class="form-control" data-inputmask-alias="datetime"
                            data-inputmask-inputformat="dd/mm/yyyy"  name="fdate" required id="fdate">
                            <span class="text-muted">e.g "DD/MM/YYYY"</span>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="inputPassword4">To Date</label>
                            <input type="text" class="form-control" data-inputmask-alias="datetime"
                            data-inputmask-inputformat="dd/mm/yyyy"  name="tdate" required id="tdate">
                            <span class="text-muted">e.g "DD/MM/YYYY"</span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success float-end">Generate</button>
                </form>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12"> 
            <div class="card">
                <div class="table-container">
                    <table id="inOutPunchTable" class="table" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>IN Time</th>
                                <th>OUT Time</th>
                                <th class="text-danger">Early Out</th>
                                <th>Worktime</th>
                                <th class="text-success">Overtime</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="mt-2">
                        </tbody>
                    </table>
 
                    
                </div>
                <div id="totalWorkHours" class="mt-4 row" style="display: none; margin-bottom: 20px">
                    <h4 class="col-4" style="margin-left: 22rem">Total Work Hours: <span id="workHours"></span></h4>
                    <h4 class="col-4">Total OT Hours: <span id="otHours"></span></h4>
                </div>
            </div>
        </div>
    </div>  

<!-- Load jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>

<script>
    $(document).ready(function() {
        // Handle form submission to fetch data
        $('#inoutForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission behavior

            let empName = $('#employee option:selected').text(); 
            let fdate = $('input[name="fdate"]').val();
            let tdate = $('input[name="tdate"]').val();

            let formData = {
                empcode: $('#employee').val(),
                fdate: fdate,
                tdate: tdate,
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                success: function(response) {

                    console.log(response);
                    var tableBody = $('#inOutPunchTable tbody');
                    tableBody.empty(); // Clear previous data

                    if (response['InOutPunchData'].length === 0) {
                        alert('No data available for the selected date range.');
                        return;
                    }

                    var totalWorkMinutes = 0;
                    var totalOTMinutes = 0;


                    // Populate the table with fetched data
                    $.each(response['InOutPunchData'], function(index, item) {
                        var status = item.INTime === '--:--' ? 'A' : (item.OUTTime === '--:--' ? 'Not Punch Out' : 'P');
                        var row = '<tr>' +
                            '<td>' + (index + 1) + '</td>' + 
                            '<td>' + item.Name + '</td>' +
                            '<td>' + item.DateString + '</td>' +
                            '<td>' + item.INTime + '</td>' +
                            '<td >' + item.OUTTime + '</td>' +
                            '<td class="text-danger">' + item.Erl_Out + '</td>' +
                            '<td>' + item.WorkTime + '</td>' +
                            '<td class="text-danger">' + item.OverTime + '</td>' +
                            '<td>' + status + '</td>' +
                            '</tr>';
                             tableBody.append(row);

                             // Calculate total work minutes
                            var workTime = item.WorkTime.split(':');
                            var workHours = parseInt(workTime[0]);
                            var workMinutes = parseInt(workTime[1]);
                            totalWorkMinutes += (workHours * 60) + workMinutes;

                            var overtime = item.OverTime.split(':');
                            var otHours = parseInt(overtime[0]);
                            var otMinutes = parseInt(overtime[1]);
                            totalOTMinutes += (otHours * 60) + otMinutes;

                        }); 

                            // Convert total work minutes to hours and minutes
                            $('#totalWorkHours').show();

                            var totalWorkHours = Math.floor(totalWorkMinutes / 60);
                            var remainingWorkMinutes = totalWorkMinutes % 60;
                            $('#workHours').text(totalWorkHours + ' hrs ' + remainingWorkMinutes + ' mins');

                            var totalOTHours = Math.floor(totalOTMinutes / 60);
                            var remainingOTMinutes = totalOTMinutes % 60;
                            $('#otHours').text(totalOTHours + ' hrs ' + remainingOTMinutes + ' mins');

                            $('#download-button').data('empName', empName);
                            $('#download-button').data('dateRange', fdate + " to " + tdate);
                            $('#download-button').data('totalHours', totalWorkHours + ' hrs ' + remainingWorkMinutes + ' mins');

                },
                error: function(error) {
                    alert('An error occurred while fetching data.');
                    console.log(error);
                }
            });
        });

        // Handle PDF generation after data is loaded
        $('#download-button').click(function() {    
            if ($('#inOutPunchTable tbody tr').length === 0) {
                alert('No data available to generate PDF.');
                $('#totalWorkHours').hide(); // Hide the total work hours div if there's no data
                return;
            }

            // Retrieve employee details
            let empName = $(this).data('empName');
            let dateRange = $(this).data('dateRange');
            let totalHours = $(this).data('totalHours'); // Retrieve total hours

            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF('p', 'pt', 'a4');

            // Define the table headers and data
            const headers = [];
            const data = [];
           
            // Get table headers
            $('#inOutPunchTable thead tr th').each(function() {
                headers.push($(this).text());
            });

            // Get table body data and calculate totals for Worktime and Overtime
            $('#inOutPunchTable tbody tr').each(function() {
                const row = [];
                $(this).find('td').each(function(index) {
                    const cellText = $(this).text();
                    row.push(cellText);

                    // Assuming Worktime is in column 5 and Overtime is in column 6
                    if (index === 6) { // Adjust the index if necessary
                        totalWorktimeMinutes += convertTimeToMinutes(cellText);
                    } 
                });
                data.push(row);
            });

           
            // Add employee details at the top of the PDF
            pdf.setFontSize(12);
            pdf.text("Employee Name: " + empName, 40, 30);
            pdf.text("Date Range: " + dateRange, 40, 50);
            pdf.text("Total Work Hours: " + totalHours, 40, 70);

            // Use autoTable to add the table with headers and data
            pdf.autoTable({
                head: [headers],
                body: data,
                startY: 90, // Start table below the employee details
                margin: { top: 50, left: 20, right: 20 },
                theme: 'striped',
                styles: {
                    fontSize: 8,
                    cellPadding: 3,
                },
                headStyles: {
                    fillColor: [52, 58, 64],
                    textColor: [255, 255, 255],
                },
                didDrawPage: function(data) {
                    // Footer on each page
                    const pageCount = pdf.internal.getNumberOfPages();
                    pdf.setFontSize(10);
                    pdf.text(`Page ${data.pageNumber} of ${pageCount}`, pdf.internal.pageSize.width - 40, pdf.internal.pageSize.height - 10);
                },
            });

        

            // Save the PDF
            pdf.save(empName + "_attendance_report.pdf");
        });
    });
</script>

@endsection