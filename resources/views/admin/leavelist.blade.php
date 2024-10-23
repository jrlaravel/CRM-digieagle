@extends('layout/admin-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        <img src="{{ asset('storage/profile_photos') . '/' . session('user')->profile_photo_path }}" class="avatar img-fluid rounded me-1" alt="Charles Hall" />
    </div>
    <div class="flex-grow-1 ps-2">
        <p class="text-white">{{ session('user')->first_name }}</p>
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
                                    <th>total_days</th>
                                    <th style="width: 40%">Reason</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaves as $key => $leave)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $leave->first_name }} {{ $leave->last_name }}</td>
                                    <td>{{ $leave->name }}</td>
                                    <td>{{ $leave->start_date }}</td>
                                    <td>{{ $leave->end_date }}</td>
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
                                            <form action="{{ route('admin/leave-update', $leave->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="1"> <!-- Approve -->
                                                <button type="submit" class="btn btn-success">Approve</button>
                                            </form>
                                    
                                            <!-- Reject Button to Open Modal -->
                                            <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $leave->id }}">Reject</a>
                                    
                                            <!-- Modal -->
                                            <div class="modal fade" id="rejectModal{{ $leave->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $leave->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="rejectModalLabel{{ $leave->id }}">Reject Leave</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('admin/leave-update', $leave->id) }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="status" value="2"> <!-- Reject -->
                                                                <div class="mb-3">
                                                                    <label for="rejection_reason" class="form-label">Enter Rejection Reason</label>
                                                                    <input type="text" name="rejection_reason" class="form-control" id="rejection_reason" required>
                                                                </div>
                                                                <button type="submit" class="btn btn-danger">Reject Leave</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    
                                            &nbsp;&nbsp;
                                        @endif
                                        <a href="{{ route('admin/leave-delete', $leave->id) }}"><i class="fa fa-trash" aria-hidden="true" style="color: red;"></i></a>
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
        var rejectionReasonModal = document.getElementById('rejectionReasonModal');

        rejectionReasonModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var leaveId = button.getAttribute('data-id'); // Extract the leave ID

            // Update the form action to include the leave ID
            var actionUrl = "{{ url('leave-update') }}"; // Update this to match your route
            document.getElementById('rejectionReasonForm').setAttribute('action', actionUrl + '/' + leaveId + '/2');
            document.getElementById('leave_id').value = leaveId; // Optionally store leave ID in a hidden input
        });
    });

console.log(typeof jQuery);
</script>

@endsection
