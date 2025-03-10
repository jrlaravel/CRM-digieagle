@php 
if(Session::has('leave_date'))
echo Session::get('leave_date')
@endphp

@extends('layout/employee-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        @if(session('employee') && session('employee')->profile_photo_path)
            <img src="{{ asset('storage/profile_photos') . '/' . session('employee')->profile_photo_path }}" class="avatar img-fluid rounded" />
        @else
            <img src="{{ asset('storage/profile_photos/default.png') }}" class="avatar img-fluid rounded" />
        @endif	
    </div>
    <div class="flex-grow-1 ps-2">
           <h4 class="text-white">{{session('employee')->first_name}}</h4>
    </div>
</div>
@endsection
@section('content')
<style>
    .reason-cell {
        max-width: 200px; /* Set a maximum width */
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap; /* Prevent wrapping */
        position: relative; /* Position for tooltip */
    }

    .reason-cell:hover {
        white-space: normal; /* Allow wrapping on hover */
        background: #fff;
        z-index: 10; /* Ensure it's on top */
    }

    .reason-cell:hover::after {
        content: attr(data-reason);
        position: absolute;
        background: #fff;
        border: 1px solid #ccc;
        padding: 5px;
        z-index: 10;
        white-space: normal; /* Allow wrapping in tooltip */
        max-width: 300px; /* Set max width for tooltip */
        word-wrap: break-word; /* Break long words */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        left: 0; /* Align tooltip */
        top: 100%; /* Position below the cell */
    }
</style>

<div class="modal fade" id="cancelConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="cancelConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelConfirmationModalLabel">Cancel Leave Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to cancel this leave request?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form id="cancelLeaveForm" action="" method="get">
                    @csrf
                    <button type="submit" class="btn btn-danger">Cancel Leave</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid p-0">
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Approved leave</h5>
                        </div>
                        {{-- <div class="col-auto">
                            <div class="stat text-primary">
                                <i class="align-middle" data-feather="dollar-sign"></i>
                            </div>
                        </div> --}}
                    </div>
                    <h1 class="mt-1 mb-3">{{$appleave}}</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Rejected leave</h5>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{$rejleave}}</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Pending leave</h5>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{$pendingleave}}</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Total leave</h5>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{$totalleave}}</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Leave List</h1> 
        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#defaultModalSuccess">+ Add</button>    
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Leave type</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Total Days</th>
                                    <th>Reason</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaves as $key => $data)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$data->name}}</td>
                                    <td>{{ $data->start_date ? \Carbon\Carbon::parse($data->start_date)->format('d-m-Y') : 'N/A' }}</td>
                                    <td>{{ $data->end_date ? \Carbon\Carbon::parse($data->end_date)->format('d-m-Y') : 'N/A' }}</td>
                                    <td>{{$data->total_days}}</td>
                                    <td class="reason-cell" data-reason="{{ $data->reason }}">{{$data->reason}}</td>
                                    <td>
                                        @if($data->status == 0)
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelConfirmationModal" data-href="{{ route('emp/leave-delete', $data->id) }}">Cancel</button>
                                            @endif
                                    </td>
                                    <td>
                                        @if($data->status == 0)
                                        <span class="badge bg-warning">Pending</span>
                                        @elseif($data->status == 1)
                                        <span class="badge bg-success">Approved</span>
                                        @else
                                        <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>

@if ($errors->has('leave_date'))
    <script>
        // Trigger the modal to open when there is an error
        document.addEventListener("DOMContentLoaded", function() {
            var myModal = new bootstrap.Modal(document.getElementById('leaveDateModal'));
            myModal.show();
        });
    </script>
    
    {{-- <div class="alert alert-danger">
        {{ $errors->first('leave_date') }}
    </div> --}}
@endif

<!-- Modal Structure for Leave Date Error -->
<div class="modal fade" id="leaveDateModal" tabindex="-1" aria-labelledby="leaveDateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leaveDateModalLabel">Leave Application Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $errors->first('leave_date') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Your Default Modal for Leave Form -->
<div class="modal fade" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Leave</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <form action="{{route('emp/leave-store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="text" value="{{session('employee')->id}}" name="id" hidden>

                    <div class="mb-3">
                        <label for="leaveName" class="form-label">Leave Type</label>
                        <select name="leave" class="form-control" id="leaveType">
                            <option value="">Select Leave</option>
                            @foreach($leavetype as $data) 
                                <option value="{{$data->id}}" data-name="{{$data->name}}" data-description="{{$data->description}}">
                                    {{$data->name}}
                                </option>
                            @endforeach
                        </select>
                        <span id="alert" class="text-danger"></span>
                    </div>     
                    
                    <div class="mb-3" id="total_days" style="display: none">
                        <label for="totalDays" class="form-label">Total Days</label>
                        <input type="number" class="form-control" id="totalDays" name="total_days">
                    </div>

                    <div class="mb-3">
                        <label for="from" class="form-label">From</label>
                        <input type="date" class="form-control" id="from" name="from" required>
                    </div>

                    <div class="mb-3">
                        <label for="to" class="form-label">To</label>
                        <input type="date" class="form-control" id="to" name="to" required>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <input type="text" class="form-control" id="reason" name="reason" placeholder="Reason" required>
                    </div>

                    <div class="mb-3">
                        <label for="other" class="form-label">Other</label>
                        <input type="text" class="form-control" id="other" name="other" placeholder="Other" required>
                    </div>

                    <div class="mb-3" id="reportInput" style="display: none;">
                        <label for="report" class="form-label">Upload Medical Report (PDF)</label>
                        <input type="file" class="form-control" name="report" id="report" accept="application/pdf">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>  
                </form>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        $('#datatables-reponsive').DataTable({
            responsive: true,
            pageLength: 5, // Number of rows per page
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        $('#cancelConfirmationModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var url = button.data('href'); // Extract the leave delete URL from data-href attribute

            // Set the action URL for the cancel leave form
            var form = $(this).find('#cancelLeaveForm');
            form.attr('action', url); // Update the form action with the correct URL
        });
    });


$(document).ready(function () {
    function setMonthRestrictions() {
        let today = new Date();
        let firstDay = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
        let lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0).toISOString().split('T')[0];

        $('#from').attr('min', firstDay);
        $('#from').attr('max', lastDay);
        $('#to').attr('min', firstDay);
        $('#to').attr('max', lastDay);
    }

    function getWeekendDates() {
        let today = new Date();
        let year = today.getFullYear();
        let month = today.getMonth();
        let weekends = [];

        let daysInMonth = new Date(year, month + 1, 0).getDate();

        for (let day = 1; day <= daysInMonth; day++) {
            let date = new Date(year, month, day);
            let dayOfWeek = date.getDay();

            if (dayOfWeek === 0 || dayOfWeek === 6) {
                weekends.push(date.toISOString().split('T')[0]);
            }
        }
        return weekends;
    }

    function hasWeekendInRange(startDate, endDate) {
        let weekends = getWeekendDates();
        let currentDate = new Date(startDate);

        while (currentDate <= new Date(endDate)) {
            let formattedDate = currentDate.toISOString().split('T')[0];
            if (weekends.includes(formattedDate)) {
                return true; // Weekend found in range
            }
            currentDate.setDate(currentDate.getDate() + 1);
        }
        return false;
    }

    setMonthRestrictions();

    $('#leaveType').change(function () {


        var selectedLeave = $('#leaveType option:selected').data('name');

        $('#from').removeAttr('min').removeAttr('max');
        $('#to').removeAttr('min').removeAttr('max').removeAttr('disabled');

        if (selectedLeave === 'Casual Leave') {
            $('#reportInput').hide();
            $('#total_days').show();
            var today = new Date();
            today.setHours(0, 0, 0, 0);

            $('#totalDays').on("input", function () {
                let totalDays = parseInt($(this).val(), 10) || 0;
                if (totalDays > 0) {
                    let startAfterDays = totalDays * 2; // Start leave after (totalDays * 2)
                    $('#alert').text("You can choose date after " + startAfterDays  + " days");
                    let startDate = new Date(today);
                    startDate.setDate(startDate.getDate() + startAfterDays + 1);

                    let startDateStr = startDate.toISOString().split('T')[0];
                    $('#from').attr('min', startDateStr).val(""); // Set 'From' min date
                    
                    let endDate = new Date(startDate);
                    // endDate.setDate(endDate.getDate() + totalDays - 1); // Set 'To' date

                    let endDateStr = endDate.toISOString().split('T')[0];
                    $('#to').attr('min', endDateStr).val(""); // Set 'To' min date
                }
            });

            function disableWeekendLeaveDates(input) {
                $(input).on("input", function () {
                    let selectedDate = new Date($(this).val());
                    let dayOfWeek = selectedDate.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday

                    if (dayOfWeek === 1 || dayOfWeek === 5) { // Monday or Friday
                        $('#leavetype').val("");
                        $('#alert').text("You cannot apply leave on this date. It will be considered as weekend leave.");                          
                        $(this).val(""); // Clear the selected date
                        
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    }
                });
            }

    // Apply restriction on both date pickers
    disableWeekendLeaveDates('#from');
    disableWeekendLeaveDates('#to');

    return;
}

        if (selectedLeave === 'Sick Leave') {
            $('#reportInput').show();
            $('#total_days').hide();
            $('#alert').text('');

            var today = new Date().toISOString().split('T')[0];

            $('#from').attr('min', today);
            $('#to').attr('min', today);

            return;
        }

        if (selectedLeave === 'Half Day') {
            var today = new Date().toISOString().split('T')[0];
            $('#reportInput').hide();
            $('#total_days').hide();
            $('#alert').text('');

            $('#from').attr('min', today);
            $('#to').attr('disabled', true);

            $('#from').change(function () {
                var selectedDate = $(this).val();
                $('#to').val(selectedDate).attr('min', selectedDate).attr('max', selectedDate).removeAttr('disabled');
            });

            return;
        }

        if (selectedLeave === 'Weekend Leave') {
            $('#reportInput').hide();
            $('#total_days').hide();
            $('#alert').text('');
            $('#to').attr('disabled', false);
            let today = new Date();
            let minDate = new Date(today);
            minDate.setDate(minDate.getDate() + 7); // 1 week notice

            $('#from').attr('min', minDate.toISOString().split('T')[0]);
            $('#to').attr('min', minDate.toISOString().split('T')[0]);

            $('#from, #to').change(function () {
                let selectedFrom = $('#from').val();
                let selectedTo = $('#to').val();

                if (selectedFrom && selectedTo) {
                    if (hasWeekendInRange(selectedFrom, selectedTo)) {
                        $('#alert').text('Your selected range includes a weekend. This will be counted as a Weekend Leave, and you must apply at least 7 days in advance.');
                        $('#from').attr('min', minDate.toISOString().split('T')[0]);
                        $('#to').attr('min', minDate.toISOString().split('T')[0]);
                    }
                }
            });

            return;
        }

        setMonthRestrictions();
    });
});
</script>
@endsection