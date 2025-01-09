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
                                <th>Leave Name</th>
                                <th style="width: 50%">Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                       <tbody>
                        
                            @foreach($leaveTypes as $key => $leavetype)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $leavetype->name }}</td>
                                <td>{{ $leavetype->description }}</td>
                                <td>
                                    <a href="#" class="btn btn-primary edit-leave-type-btn" 
                                        data-id="{{ $leavetype->id }}" 
                                        data-name="{{ $leavetype->name }}" 
                                        data-description="{{ $leavetype->description }}">
                                        Edit
                                    </a>
                                    <a href="{{route('admin/delete-leave-type', $leavetype->id)}}" class="btn btn-danger">Delete</a>
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
                <h5 class="modal-title" id="modalTitle">Add Leave Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <form method="POST" action="{{ route('admin/add-leavetype') }}" id="cardForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="cardName">Leave Name</label>
                        <input type="text" class="form-control" name="name" id="leaveTypeName" required placeholder="Enter name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="cardDescription">Leave Description</label>
                        <input type="text" class="form-control" name="description" id="leaveTypeDescription" required placeholder="Enter Description">
                    </div>
                    <input type="hidden" name="leavetype_id" id="leaveTypeId">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="submitBtn">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
   $(document).ready(function() {
    $('.edit-leave-type-btn').click(function() {
        var leaveTypeId = $(this).data('id');
        var leaveTypeName = $(this).data('name');
        var leaveTypeDescription = $(this).data('description');

        // Update the modal fields
        $('#leaveTypeId').val(leaveTypeId);
        $('#leaveTypeName').val(leaveTypeName);
        $('#leaveTypeDescription').val(leaveTypeDescription);

        // Change the form action to update if editing
        $('#leaveTypeForm').attr('action', '{{ route("admin/edit-leave-type") }}');

        // Change the modal title and button text
        $('#modalTitle').text('Update Leave Type');
        $('#submitBtn').text('Update');

        // Open the modal
        $('#defaultModalSuccess').modal('show');
    });

    $('#defaultModalSuccess').on('hidden.bs.modal', function () {
        // Reset the modal to default state for adding a new leave type
        $('#leaveTypeForm').attr('action', '{{ route("admin/add-leavetype") }}');
        $('#modalTitle').text('Add Leave Type');
        $('#submitBtn').text('Add');
        $('#leaveTypeForm')[0].reset();  // Clear the form
        $('#leaveTypeId').val('');  // Clear the hidden input
    });
})
</script>
@endsection