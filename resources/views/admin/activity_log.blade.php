@extends('layout/admin-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        @if(session('user') && session('user')->profile_photo_path)
            <img src="{{ asset('storage/profile_photos') . '/' . session('user')->profile_photo_path }}" class="avatar img-fluid rounded" />
        @else
            <img src="{{ asset('storage/profile_photos/default.png') }}" class="avatar img-fluid rounded" />
        @endif	
    </div>
        <div class="flex-grow-1 ps-2">
            <p class="text-white">{{session('user')->first_name}}</p>
            {{-- <div class="sidebar-user-subtitle">Designer</div> --}}
    </div>
</div>
@endsection
@section('content')
<div class="container-fluid p-0">
    <div class="mb-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <h1 class="h3 d-inline align-middle float-left">Activity log</h1>   
        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#defaultModalSuccess">Download Report</button>    
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
                                    <th>User id</th>
                                    <th>Description</th>
                                    <th>IP address</th>
                                    <th>Name</th>
                                    <th>Action Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forEach($data as $key => $log)
                                <tr>
                                     <td>{{ $key + 1 }}</td>
                                    <td>{{ $log->user_id }}</td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ $log->ip_address  }}</td>  
                                    <td>{{ $log->throttle_key}}</td>
                                     <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:m:s') }}</td>
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


{{-- Modal --}}
<div class="modal fade" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Download Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <form method="POST" action="{{ route('admin/activity_log/download') }}" id="downloadReportForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="fdate">From Date</label>
                        <input type="date" class="form-control" name="fdate" id="fdate" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="tdate">To Date</label>
                        <input type="date" class="form-control" name="tdate" id="tdate" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="submitBtn">Get Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
@section('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

<script>

    $(document).ready(function () {
        $('#datatables-reponsive').DataTable({
            responsive: true,
            pageLength: 15, // Number of rows per page
        });
    });

    document.getElementById('downloadReportForm').addEventListener('submit', function (event) {
        // Close the modal immediately before the form submission
        const modal = bootstrap.Modal.getInstance(document.getElementById('defaultModalSuccess'));
        modal.hide();

        // Allow the form to proceed with the submission
    });
    
</script>
@endsection