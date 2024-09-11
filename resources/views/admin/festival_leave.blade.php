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
        <h1 class="h3 d-inline align-middle">Festival List</h1> 
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
                                <th>Festival Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach($festivalleaves as $key => $data)
                           <tr>
                            <td>{{++$key}}</td>
                            <td>{{$data->name}}</td>
                            <td>{{$data->start_date}}</td>
                            <td>{{$data->end_date}}</td>
                            <td>
                                <a href="#" class="btn btn-primary edit-button" 
                                data-id={{$data->id}}
                                data-name={{$data->name}} 
                                data-startdate={{$data->start_date}}
                                data-enddate={{$data->end_date}}>Edit</a>

                                <a href="{{route('admin/festival-leave-delete',$data->id)}}" class="btn btn-danger">Delete</a>  <!-- Delete functionality --> 
                            </td>
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
                <h5 class="modal-title" id="modalTitle">Add Festival leave</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <form method="POST" action="{{route('admin/festival-leave-create')}}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" >Festival Name</label>
                        <input type="text" class="form-control" name="name" id="cardName" required placeholder="Enter name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" >Start Date</label>
                        <input type="date" class="form-control" name="startdate" id="startdate" required >
                    </div>
                    <div class="mb-3">
                        <label class="form-label" >End Date</label>
                        <input type="date" class="form-control" name="enddate" id="enddate" required >
                    </div>
                   
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="submitBtn">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Update Festival Leave</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <form method="post" id="festivalForm" action="{{route('admin/festival-leave-update')}}">
                    @csrf
                    <!-- Include hidden field for the ID -->
                    <input type="hidden" name="id" id="festivalId">
                    
                    <div class="mb-3">
                        <label class="form-label">Festival Name</label>
                        <input type="text" class="form-control" name="name" id="festivalName" required placeholder="Enter name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" name="startdate" id="start-date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" name="enddate" id="end-date" required>
                    </div>
                   
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="submitBtn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const editButtons = document.querySelectorAll('.edit-button');

    editButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            // Get data from the clicked button
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const startDate = new Date(this.getAttribute('data-startdate'));  // Convert to Date object
            const endDate = new Date(this.getAttribute('data-enddate'));      // Convert to Date object

            // Format the date as 'YYYY-MM-DD' for the input
            const formattedStartDate = startDate.toISOString().split('T')[0];
            const formattedEndDate = endDate.toISOString().split('T')[0];

            console.log(id, name, formattedStartDate, formattedEndDate);

            // Set the modal form values
            document.getElementById('festivalId').value = id;
            document.getElementById('festivalName').value = name;
            document.getElementById('start-date').value = formattedStartDate;
            document.getElementById('end-date').value = formattedEndDate;

            // Show the modal
            var modal = new bootstrap.Modal(document.getElementById('updateModalSuccess'));
            modal.show();
        });
    });
});

</script>

@endsection