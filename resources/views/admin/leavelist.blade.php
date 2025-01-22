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
        <p class="text-white">{{ session('user')->first_name }}</p>
    </div>
</div>
@endsection

@section('content')
<style>
    .reason-cell {
        max-width: 170px; /* Set a maximum width */
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
        max-width: 170px; /* Set max width for tooltip */
        word-wrap: break-word; /* Break long words */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        left: 0; /* Align tooltip */
        top: 100%; /* Position below the cell */
    }
</style>

<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Leave Type List</h1> 
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
                                    <th>Employee Name</th>
                                    <th>Leave Type</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Total days</th>
                                    <th style="width: 35%">Reason</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    <th>Download Report</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaves as $key => $leave)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $leave->first_name }} {{ $leave->last_name }}</td>
                                    <td>{{ $leave->name }}</td>
                                    <td>{{ $leave->start_date ? \Carbon\Carbon::parse($leave->start_date)->format('d-m-Y') : 'N/A' }}</td>
                                    <td>{{ $leave->end_date ? \Carbon\Carbon::parse($leave->end_date)->format('d-m-Y') : 'N/A' }}</td>
                                    <td>{{ $leave->total_days }}</td>
                                    <td class="reason-cell" data-reason="{{ $leave->reason }}">{{ $leave->reason }}</td>
                                    <td>
                                        @if($leave->status == 0)
                                        <span class="badge bg-warning">Pending</span>
                                        @elseif($leave->status == 1)
                                        <span class="badge bg-success">Approved</span>
                                        @else
                                        <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($leave->status == 0)
                                            <div class="dropdown mt-4">
                                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li>
                                                        <a href="#" 
                                                           class="dropdown-item text-success" 
                                                           data-bs-toggle="modal" 
                                                           data-bs-target="#leaveModal" 
                                                           data-id="{{ $leave->id }}" 
                                                           data-status="1" 
                                                           data-title="Approve Leave"
                                                           data-button="Approve Leave"
                                                           data-label="Enter Approval Reason">Approve</a>
                                                    </li>
                                                    <li>
                                                        <a href="#" 
                                                           class="dropdown-item text-danger" 
                                                           data-bs-toggle="modal" 
                                                           data-bs-target="#leaveModal" 
                                                           data-id="{{ $leave->id }}" 
                                                           data-status="2" 
                                                           data-title="Reject Leave"
                                                           data-button="Reject Leave"
                                                           data-label="Enter Rejection Reason">Reject</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin/leave-delete', $leave->id) }}" class="dropdown-item text-danger">Delete</a>
                                                    </li>
                                                </ul>
                                                
                                            </div>

                                            <div class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="leaveModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="leaveModalLabel">Leave Action</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="leaveActionForm" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="status" id="action_status">
                                                                <input type="hidden" name="leave_id" id="leave_id">
                                                                <div class="mb-3">
                                                                    <label for="action_reason" class="form-label" id="modalLabel">Enter Reason</label>
                                                                    <input type="text" name="action_reason" class="form-control" id="action_reason" required>
                                                                </div>
                                                                <button type="submit" class="btn btn-primary" id="modalSubmitButton">Submit</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        
                                            &nbsp;&nbsp;
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Delete Button -->
                            
                                        <!-- Check if report exists and show download link -->
                                        @if($leave->report)
                                            <div class="mt-2">
                                                <a href="{{ asset('storage/' . $leave->report) }}" class="btn btn-info" download>Download Report</a>
                                            </div>
                                        @else
                                            <p>No Report Available</p>
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

<script>
  
  document.addEventListener('DOMContentLoaded', function () {
    var leaveModal = document.getElementById('leaveModal');
    leaveModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Button that triggered the modal
        var leaveId = button.getAttribute('data-id'); // Leave ID
        var status = button.getAttribute('data-status'); // Action status (1 for approve, 2 for reject)
        var title = button.getAttribute('data-title'); // Modal title
        var buttonText = button.getAttribute('data-button'); // Submit button text
        var label = button.getAttribute('data-label'); // Label for input field

        // Update modal title, label, and button text
        document.getElementById('leaveModalLabel').textContent = title;
        document.getElementById('modalLabel').textContent = label;
        document.getElementById('modalSubmitButton').textContent = buttonText;

        // Update form action and hidden inputs
        var actionUrl = "{{ route('admin/leave-update', ':id') }}".replace(':id', leaveId);
        document.getElementById('leaveActionForm').setAttribute('action', actionUrl);
        document.getElementById('action_status').value = status;
        document.getElementById('leave_id').value = leaveId;
    });
});

console.log(typeof jQuery);
</script>

@endsection
