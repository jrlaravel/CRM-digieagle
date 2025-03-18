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
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Rejected CV List</h1> 
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
                                <th>Candidate Name</th>
                                <th>Applied Position</th> 
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $value)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->designation }}</td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-danger" onclick="confirmDelete({{ $value->id }})">Delete</a>
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


<!-- Confirmation Modal -->
<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this CV?</p>
                <input type="hidden" id="deleteId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="deleteCv()">Delete</button>
            </div>
        </div>
    </div>
</div>


<script>
function confirmDelete(id) {
    $("#deleteId").val(id);
    $("#deleteModal").modal("show");
}

function deleteCv() {
    var id = $("#deleteId").val();
    
    $.ajax({
        url: "{{ route('emp/delete-cv') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            id: id
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.message);
            }
        },
        error: function(xhr) {
            alert("Something went wrong!");
        }
    });
}

</script>
@endsection