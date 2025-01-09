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
<style>
    .modal-dialog.modal-dialog-zoom {
        animation: zoomIn 0.3s ease-in-out;
    }

    @keyframes zoomIn {
        0% {
            transform: scale(0.7);
            opacity: 0;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
</style>
<div class="container-fluid p-0">

    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Client List</h1>   
        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#defaultModalSuccess">Add</button> 
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
                                    <th>Client Name</th>
                                    <th>Client Industry</th>
                                    <th>Client Description</th>
                                    <th>Department working with this client</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach($details as $item)
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>{{ $item->company_name }}</td>
                                        <td>{{ $item->company_industry }}</td>
                                        <td>{{ $item->company_description }}</td>
                                        <td>{{ $item->departments_provided }}</td>
                                        <td>
                                            <button class="btn btn-primary edit-lead" 
                                                    data-id="{{ $item->company_id }}"
                                                    data-name="{{ $item->company_name }}"
                                                    data-description="{{ $item->company_description }}"
                                                    data-department="{{ $item->departments_provided }}"
                                                    data-industry="{{ $item->company_industry }}"
                                                    data-note="{{ $item->company_notes }}">
                                                Edit
                                            </button>
                                            <a href="javascript:void(0)" class="btn btn-danger" 
                                               onclick="showConfirmationDeleteModal('{{ route('admin/delete-company-service', $item->company_id) }}')">
                                               Delete
                                            </a>
                                        </td>
                                    </tr>   
                                    @php $counter++; @endphp
                                @endforeach
                            </tbody>
                        </table>                        
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Client Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="{{ route('admin/update-company-service') }}">
                    @csrf
                    <input type="hidden" id="company_id" name="company_id">

                    <div class="mb-3">
                        <label for="company_name" class="form-label">Client Name</label>
                        <input type="text" class="form-control" id="company_name" name="company_name">
                    </div>

                    <div class="mb-3">
                        <label for="company_industry" class="form-label">Client Industry</label>
                        <input type="text" class="form-control" id="company_industry" name="company_industry">
                    </div>

                    <div class="mb-3">
                        <label for="company_description" class="form-label">Client Description</label>
                        <textarea class="form-control" id="company_description" name="company_description" rows="4"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="inputAddress">Department working with this client</label>
                        @foreach($department as $data)
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                value="{{$data->id}}" 
                                id="department-{{$data->id}}" 
                                name="departments[]">
                            <label class="form-check-label" for="department-{{$data->id}}">
                                {{ $data->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <div class="mb-3">
                        <label for="company_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="company_notes" name="company_notes" rows="4"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <form method="POST" action="{{route('admin/add-company-service')}}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="inputAddress">Client Name</label>
                        <input type="text" class="form-control"  name="name" id="name" required placeholder="Enter Name">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="inputAddress">Client Industry</label>
                        <input type="text" class="form-control"  name="industry" id="industry" required placeholder="Enter Industry">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="inputAddress">Client Description</label>
                        <input type="text" class="form-control"  name="description" id="description" required placeholder="Enter Description">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="inputAddress">Services Provided by Us</label>
                        @foreach($department as $data)
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                value="{{$data->id}}" 
                                id="department-{{$data->id}}" 
                                name="departments[]">
                            <label class="form-check-label" for="department-{{$data->id}}">
                                {{ $data->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="inputAddress">Notes</label>
                        <input type="text" class="form-control"  name="note" id="note" required placeholder="Enter Note">
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Client details have been added successfully!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationDeleteModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-zoom">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this Client service? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
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
            pageLength: 5, // Number of rows per page
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        // Datatables Responsive
        $("#datatables-reponsive").DataTable({
            responsive: true
        });
    });

    
    // Function to populate the modal with data
    $(document).on('click', '.edit-lead', function() {
    let Id = $(this).data('id');
    let name = $(this).data('name');
    let industry = $(this).data('industry');
    let department = $(this).data('department'); // Assuming this is a comma-separated string like "Facebook Story,Instagram Story"
    let description = $(this).data('description');
    let notes = $(this).data('note');

    // Log to ensure services data is correct
    console.log('departments:', department);

    // Set modal fields
    $('#company_id').val(Id);
    $('#company_name').val(name);
    $('#company_industry').val(industry);
    $('#company_description').val(description);
    $('#company_notes').val(notes);

    // Clear all checkboxes first
    $('.form-check-input').prop('checked', false);

    // Check the appropriate checkboxes
    if (department) {
        // Split services into an array
        let DepartmentArray = department.split(',').map(item => item.trim()); // Trim whitespace

        // Iterate through the array and check the corresponding checkboxes
        DepartmentArray.forEach(function(departmentName) {
            $('.form-check-input').each(function() {
                let checkboxLabel = $(this).siblings('label').text().trim(); // Get label text
                if (checkboxLabel === departmentName) {
                    $(this).prop('checked', true);
                }
            });
        });
    }

    $('#editModal').modal('show');
});



    document.addEventListener("DOMContentLoaded", function() {
        // Datatables Responsive
        $("#datatables-reponsive").DataTable({
            responsive: true
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            confirmationModal.show();

            // Automatically close the modal after 3 seconds
            setTimeout(function () {
                confirmationModal.hide();
            }, 3000);
        @endif
    });

    function showConfirmationDeleteModal(deleteUrl) {
        // Set the URL for the delete action
        document.getElementById('confirmDeleteBtn').setAttribute('href', deleteUrl);
        
        // Get the modal element
        var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationDeleteModal'), {
            backdrop: 'static', // Disable closing by clicking outside
            keyboard: false // Disable closing with escape key
        });

        // Show the modal
        confirmationModal.show();
    }
</script>

@endsection