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
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Service List</h1>
    </div>
    <div class="row">
        <!-- Main Services Table (Left Column) -->
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Department List  </h5>
                    <div class="table-responsive">
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
                                        <a href="javascript:void(0)" class="btn btn-light" style="border: 1px solid #000" onclick="loadStatusList({{ $data->id }})">Edit Status</a>
                                        <a href="javascript:void(0)" class="btn btn-light" style="border: 1px solid #000" onclick="loadSubServices({{ $data->id }})">Edit Service</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Sub Services Table (Right Column) -->
        <div id="subServicesSection" class="col-12 col-md-6 d-none">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title float-start">Services List</h5>
                        <!-- Pass `departmentId` to the function -->
                        <a href="javascript:void(0)" 
                            class="btn btn-success float-end" 
                            onclick="openAddSubServiceModal({{ $data->id }})">
                            Add Service
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table id="subServicesTable" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Service Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="subServicesTableBody">
                                <!-- Sub-services will be dynamically added here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    
        <!-- Status Table (Right Column) -->
        <div id="statusSection" class="col-12 col-md-6 d-none">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Status List</h5>
                        <a href="javascript:void(0)" class="btn btn-success float-end" onclick="openAddStatusModal({{ $data->id }})">Add Status</a>
                    </div>
                    <div class="table-responsive">
                        <table id="statusTable" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Status Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Statuses will be dynamically added here -->
                            </tbody>
                        </table>
                    </div>
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
                    <input type="hidden" id="selectedDepartmentId" name="department_id" value="">
                    
                    <div id="subServiceContainer">
                        <div class="mb-3 sub-service-field">
                            <label for="subServiceName" class="form-label">Service Name</label>
                            <input type="text" class="form-control" name="services[]" required>
                        </div>
                    </div>

                    <button type="button" class="btn btn-success float-start" id="addSubServiceBtn">+ Add</button>
                    <button type="submit" class="btn btn-primary float-end">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Modal to Add Status -->
<div class="modal fade" id="addStatusModal" tabindex="-1" aria-labelledby="addStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStatusModalLabel">Add Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addStatusForm" action="{{ route('admin/add-status') }}" method="POST">
                    @csrf
                    <input type="hidden" id="DepartmentId" name="department_id" value="">
                    
                    <div id="statusContainer">
                        <div class="mb-3 status-field">
                            <label for="statusName" class="form-label">Status Name</label>
                            <input type="text" class="form-control" name="status[]" required>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-success float-start" id="addStatusBtn">+ Add</button>
                    <button type="submit" class="btn btn-primary float-end">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

@if(Session::has('success'))
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ Session::get('success') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        confirmationModal.show();

        // Automatically hide the modal after 2 seconds
        setTimeout(() => {
            confirmationModal.hide();
        }, 2000);
    });
</script>
@endif

@endsection

@section('scripts')
<script>
    // Define globally to share across functions
    let selectedDepartmentId = null;

function setDeleteRoute(route) {
    document.getElementById('deleteConfirmButton').setAttribute('href', route);
}

document.addEventListener("DOMContentLoaded", function () {
    $("#datatables-reponsive").DataTable({
        responsive: true
    });
});

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

    selectedDepartmentId = departmentId; // Update the global variable
    
    // Show the Sub Services section and hide the Status section
    $('#subServicesSection').removeClass('d-none');
    $('#statusSection').addClass('d-none');

        // Fetch sub-services for the selected department
        $.ajax({
            url: `/admin/services/${selectedDepartmentId}`, // Adjust the URL if needed
            method: 'GET',
            success: function(data) {
                const subServicesTable = $('#subServicesTable tbody');
                subServicesTable.empty(); // Clear the existing table content

                if (data.length > 0) {
                    data.forEach((service, index) => {
                        const row = `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${service.service_name}</td>
                                <td>
                                    <a href="#" 
                                    onclick="setDeleteRoute('/admin/delete-service/${service.id}')" 
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

function openAddSubServiceModal() {
    if (!selectedDepartmentId) {
        console.error('No department ID set for modal.');
        return;
    }

    console.log('Opening modal for Department ID:', selectedDepartmentId);

    // Set the hidden input value
    document.getElementById('selectedDepartmentId').value = selectedDepartmentId;

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

// let selectedDepartmentId = null;


function loadStatusList(departmentId) {
    // let selectedDepartmentId = null; // Define a global variable to hold the selected department ID
    selectedDepartmentId = departmentId; // Update the global variable
    console.log('Loaded Department ID:', selectedDepartmentId);

    // Show the Status section and hide the Sub Services section
    $('#statusSection').removeClass('d-none');
    $('#subServicesSection').addClass('d-none');

    // Fetch statuses for the selected department
    $.ajax({
        url: `/admin/status/${selectedDepartmentId}`, // Adjust the URL if needed
        method: 'GET',
        success: function (data) {
            const statusTable = $('#statusTable tbody');
            statusTable.empty(); // Clear existing table content

            if (data.length > 0) {
                data.forEach((status, index) => {
                    const row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${status.name}</td>
                            <td>
                                <a href="#" 
                                   onclick="setDeleteRoute('/admin/delete-status/${status.id}')" 
                                   data-bs-toggle="modal" 
                                   data-bs-target="#confirmDeleteModal" 
                                   class="btn btn-danger">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    `;
                    statusTable.append(row); // Append rows dynamically
                });
            } else {
                const row = `
                    <tr>
                        <td colspan="3" class="text-center">No statuses available for this department.</td>
                    </tr>
                `;
                statusTable.append(row); // Display a message if no data
            }
        },
        error: function (error) {
            console.error('Error fetching statuses:', error);
        }
    });
}

function openAddStatusModal() {
    if (!selectedDepartmentId) {
        console.error('No department ID set for modal.');
        return;
    }

    console.log('Opening modal for Department ID:', selectedDepartmentId);

    // Set the hidden input value
    document.getElementById('DepartmentId').value = selectedDepartmentId;

    // Clear existing input fields before showing the modal
    document.getElementById('statusContainer').innerHTML = `
        <div class="mb-3 status-field">
            <label for="statusName" class="form-label">Status Name</label>
            <input type="text" class="form-control" name="status[]" required>
        </div>`;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('addStatusModal'));
    modal.show();
}

// Add new input field for another status
document.getElementById('addStatusBtn').addEventListener('click', function () {
    const statusContainer = document.getElementById('statusContainer');
    const newField = document.createElement('div');
    newField.classList.add('mb-3', 'status-field');
    newField.innerHTML = `
        <label for="statusName" class="form-label">Status Name</label>
        <input type="text" class="form-control" name="status[]" required>
    `;
    statusContainer.appendChild(newField);
});



</script>
@endsection
