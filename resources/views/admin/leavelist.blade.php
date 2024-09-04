@extends('layout/admin-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        <img src="{{asset('storage/profile_photos').'/'.session('user')->profile_photo_path}}" class="avatar img-fluid rounded me-1" alt="Charles Hall" />
    </div>
    <div class="flex-grow-1 ps-2">
        
          <p class="text-white">{{session('user')->first_name}}</p>

    </div>
</div>
@endsection
@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Leave Type List</h1> 
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
                                <th>Employee Name</th>
                                <th>Leave Type</th>
                                <th>From</th>
                                <th>To</th>
                                <th style="width: 40%">Reason</th>
                                <th>status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaves as $key => $leave)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $leave->first_name }} {{$leave->last_name}}</td>
                                <td>{{ $leave->name }}</td>
                                <td>{{ $leave->start_date }}</td>
                                <td>{{ $leave->end_date }}</td>
                                <td>{{ $leave->reason }}</td>
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
                                    <a href="{{ route('admin/leave-delete', $leave->id) }}" class="btn btn-danger">Delete</a>
                                    @if($leave->status == 0)
                                    <a href="{{ route('admin/leave-update', [$leave->id,1]) }}" class="btn btn-success">Approve</a>
                                    @endif
                                    @if($leave->status == 0)
                                    <a href="{{ route('admin/leave-update', [$leave->id,2]) }}" class="btn btn-danger">Reject</a>
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




@endsection