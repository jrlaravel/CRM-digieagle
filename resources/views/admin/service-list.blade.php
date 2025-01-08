@extends('layout/admin-sidebar')

@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        <img src="{{ asset('storage/profile_photos') . '/' . session('user')->profile_photo_path }}" class="avatar img-fluid rounded me-1" />
    </div>
    <div class="flex-grow-1 ps-2">
        <p class="text-white">{{ session('user')->first_name }}</p>
    </div>
</div>
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Service List</h1>
        {{-- <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#defaultModalSuccess">Add New Service</button> --}}
    </div>
    <div class="row">
        <!-- Main Services Table (Left Column) -->
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive"> <!-- Added table-responsive class -->
                        <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Department Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($department as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-primary" onclick="loadSubServices({{ $data->id }})">Edit</a>       
                                        <a href="javascript:void(0)" class="btn btn-success" onclick="openAddSubServiceModal({{ $data->id }})">Add</a>
                                    </td>
                                </tr>                                           
                                @endforeach
                            </tbody>
                        </table>
                    </div> <!-- End table-responsive -->
                </div>
            </div>
        </div>
    
        <!-- Sub Services Table (Right Column) -->
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive"> <!-- Added table-responsive class -->
                        <table id="subServicesTable" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Service Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Sub-services will be dynamically added here -->
                            </tbody>
                        </table>
                    </div> <!-- End table-responsive -->
                </div>
            </div>
        </div>
    </div>
    

    <!-- Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this service? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="deleteConfirmButton" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal to Add Service -->
<div class="modal fade" id="addSubServiceModal" tabindex="-1" aria-labelledby="addSubServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubServiceModalLabel">Add Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addSubServiceForm" action="{{ route('admin/add-service') }}" method="POST">
                    @csrf
                    <input type="hidden" id="departmentId" name="department">
                    
                    <div id="subServiceContainer">
                        <div class="mb-3 sub-service-field">
                            <label for="subServiceName" class="form-label">Service Name</label>
                            <input type="text" class="form-control" name="services[]" required>
                        </div>
                    </div>

                    <button type="button" class="btn btn-success float-start" id="addSubServiceBtn">+ Add</button>
                    <button type="submit" class="btn btn-primary float-end">Add Service</button>
                </form>
            </div>
        </div>
    </div>
</div>


  

@endsection

@section('scripts')
<script>
function setDeleteRoute(route) {
    document.getElementById('deleteConfirmButton').setAttribute('href', route);
}

document.addEventListener("DOMContentLoaded", function () {
    $("#datatables-reponsive").DataTable({
        responsive: true
    });
});

  // Function to open modal and set department ID
function openAddSubServiceModal(departmentId) {
    document.getElementById('departmentId').value = departmentId;  // Set department ID in hidden input field
    
    // Clear existing input fields before showing the modal
    document.getElementById('subServiceContainer').innerHTML = `
        <div class="mb-3 sub-service-field">
            <label for="subServiceName" class="form-label">Service Name</label>
            <input type="text" class="form-control" name="services[]" required>
        </div>`;
    
    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('addSubServiceModal'));
    modal.show();
}

// Add new input field for another service
document.getElementById('addSubServiceBtn').addEventListener('click', function () {
    const subServiceContainer = document.getElementById('subServiceContainer');
    const newField = document.createElement('div');
    newField.classList.add('mb-3', 'sub-service-field');
    newField.innerHTML = `
        <label for="subServiceName" class="form-label">Service Name</label>
        <input type="text" class="form-control" name="services[]" required>
    `;
    subServiceContainer.appendChild(newField);
});

function loadSubServices(departmentId) {
    // Send AJAX request to fetch sub-services based on the main service id using jQuery
    $.ajax({
        url: `/admin/services/${departmentId}`, // Adjust the URL if needed
        method: 'GET',
        success: function(data) {
            const subServicesTable = $('#subServicesTable tbody');
            subServicesTable.empty();
            if (data.length > 0) {
                data.forEach((services, index) => {
                    const row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${services.service_name}</td>
                            <td>
                                <a href="#" 
                                onclick="setDeleteRoute('/admin/delete-service/${services.id}')" 
                                data-bs-toggle="modal" 
                                data-bs-target="#confirmDeleteModal" 
                                class="btn btn-danger">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    `;
                    subServicesTable.append(row);
                });
            } else {
                const row = `
                    <tr>
                        <td colspan="3" class="text-center">No services available for this department.</td>
                    </tr>
                `;
                subServicesTable.append(row);
            }
        },
        error: function(error) {
            console.error('Error fetching sub-services:', error);
        }
    });
}
</script>
@endsection
