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
        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#defaultModalSuccess">Add</button>    
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Leave type</th>
                                <th>From</th>
                                <th>To</th>
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
                                <td>{{$data->start_date}}</td>
                                <td>{{$data->end_date}}</td>
                                <td>{{$data->reason}}</td>
                                <td>
                                    @if($data->status == 0)
                                    <a href="{{route('emp/leave-delete',$data->id)}}" class="btn btn-danger">Cancel</a>
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

<div class="modal fade" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Leave</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
             <form action="{{route('emp/leave-store')}}" method="post">
                @csrf
                <input type="text" value="{{session('employee')->id}}" name="id" hidden>
                <div class="mb-3">
                    <label for="leaveName" class="form-label">Leave Type</label>
                    <select name="leave" class="form-control" id="leaveType">
                        <option value="">Select Leave</option>
                        @foreach($leavetype as $data) 
                        <option value="{{$data->id}}" data-name="{{$data->name}}">{{$data->name}}</option>
                        @endforeach
                    </select>
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
                    <textarea class="form-control" id="reason" name="reason" placeholder="Reason" required>
                        </textarea>
                </div>
                <div class="mb-3">
                    <label for="reason" class="form-label">Other</label>
                    <textarea class="form-control" id="other" name="other" placeholder="Other" required>
                        </textarea>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Get today's date in YYYY-MM-DD format
        var today = new Date().toISOString().split('T')[0];

        // Set the min attribute for the date input fields
        document.getElementById('to').setAttribute('min', today);
        document.getElementById('from').setAttribute('min', today);
    });

    $(document).ready(function() {
    $('#leaveType').change(function() {
        var selectedLeave = $('#leaveType option:selected').data('name');
        // If Casual Leave is selected
        if (selectedLeave === 'Casual Leave') {
            var today = new Date();
            today.setHours(0, 0, 0, 0); // Set time to 00:00:00

            // Add 2 days to the current date
            var minDate = new Date(today);
            minDate.setDate(minDate.getDate() + 4);

            // Disable previous dates in the 'from' date input
            $('#from').attr('min', minDate.toISOString().split('T')[0]);
            $('#to').attr('min', minDate.toISOString().split('T')[0]);
        }
    });
});

</script>

@endsection