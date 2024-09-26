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
                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Employee Name</th>
                                <th>Leave Type</th>
                                <th>From</th>
                                <th>To</th>
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
                                    <a href="{{ route('admin/leave-update', [$leave->id, 1]) }}" class="btn btn-success">Approve</a>
                                    <a href="{{ route('admin/leave-update', [$leave->id, 2]) }}" class="btn btn-danger">Reject</a>&nbsp;&nbsp;
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
@endsection
