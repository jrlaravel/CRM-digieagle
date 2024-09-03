@extends('layout/employee-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        <img src="{{asset('storage/profile_photos').'/'.session('employee')->profile_photo_path}}" class="avatar img-fluid rounded me-1" alt="" />
    </div>
    <div class="flex-grow-1 ps-2">
           <h4 class="text-white">{{session('employee')->first_name}}</h4>
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
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid p-0">
    <div class="mb-3">
        {{-- <h1 class="h3 d-inline align-middle">Attendance Report</h1>     --}}
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Attendance Report</h5>
                </div>
            <div class="card-body">
                <form id="inoutForm" method="POST" action="{{route('emp/inoutdata')}}">
                    @csrf  
                    <input type="text" name="empcode" value="{{$data}}" hidden>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="inputAddress">From date</label>
                            <input type="text" class="form-control" data-inputmask-alias="datetime"
                            data-inputmask-inputformat="dd/mm/yyyy"  name="fdate" required id="fdate">
                            <span class="text-muted">e.g "DD/MM/YYYY"</span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="inputPassword4">To Date</label>
                            <input type="text" class="form-control" data-inputmask-alias="datetime"
                            data-inputmask-inputformat="dd/mm/yyyy"  name="tdate" required id="tdate">
                            <span class="text-muted">e.g "DD/MM/YYYY"</span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Generate</button>
                    <button class="btn btn-primary float-end" id="download-button">Download</button>
                </form>
            </div>
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
                                <th>Date</th>
                                <th>IN Time</th>
                                <th>OUT Time</th>
                                <th class="text-danger">Early Out</th>
                                <th>Worktime</th>
                                <th class="text-danger">Overtime</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="mt-2">
                        </tbody>
                    </table>
 
                    
                </div>
                <div id="totalWorkHours" class="mt-4" style="display: none;">
                    <h3>Total Work Hours in Date Range: <span id="workHours"></span></h3>
                </div>
            </div>
        </div>
    </div>  

<!-- Load jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Load jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<!-- Load html2canvas -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    $(document).ready(function() {
        // Handle form submission to fetch data
        $('#inoutForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission behavior

            let formData = {
                empcode: $('input[name="empcode"]').val(),
                fdate: $('input[name="fdate"]').val(),
                tdate: $('input[name="tdate"]').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                success: function(response) {
                    var tableBody = $('#inOutPunchTable tbody');
                    tableBody.empty(); // Clear previous data

                    if (response['InOutPunchData'].length === 0) {
                        alert('No data available for the selected date range.');
                        return;
                    }

                    var totalWorkMinutes = 0; // To store the total work minutes

                    // Populate the table with fetched data
                    $.each(response['InOutPunchData'], function(index, item) {
                        var status = item.INTime === '--:--' ? 'A' : (item.OUTTime === '--:--' ? 'Not Punch Out' : 'P');
                        var row = '<tr>' +
                            '<td>' + (index + 1) + '</td>' + 
                            '<td>' + item.DateString + '</td>' +
                            '<td>' + item.INTime + '</td>' +
                            '<td>' + item.OUTTime + '</td>' +
                            '<td class="text-danger">' + item.Erl_Out + '</td>' +
                            '<td>' + item.WorkTime + '</td>' +
                            '<td class="text-danger">' + item.OverTime + '</td>' +
                            '<td>' + status + '</td>' +
                            '</tr>';
                        tableBody.append(row);

                        var workTime = item.WorkTime.split(':');
                        var workHours = parseInt(workTime[0]);
                        var workMinutes = parseInt(workTime[1]);
                        totalWorkMinutes += (workHours * 60) + workMinutes;
                        $('#totalWorkHours').show();
                    });

                        var totalHours = Math.floor(totalWorkMinutes / 60);
                        var remainingMinutes = totalWorkMinutes % 60;
                        $('#workHours').text(totalHours + ' hrs ' + remainingMinutes + ' mins');
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
                return;
            }

            html2canvas(document.querySelector("#inOutPunchTable")).then(canvas => {
                const { jsPDF } = window.jspdf;
                var imgData = canvas.toDataURL("image/png");
                var pdf = new jsPDF('p', 'pt', 'a4');

                // Calculate width and height to maintain aspect ratio
                var imgWidth = 560; // PDF width in pt units (8 inches x 72 dpi)
                var imgHeight = canvas.height * imgWidth / canvas.width;

                pdf.addImage(imgData, 'PNG', 20, 20, imgWidth, imgHeight);
                pdf.save("Attendance_Report.pdf");
            });
        });
    });
</script>




@endsection