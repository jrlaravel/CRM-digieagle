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
        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#defaultModalSuccess">Add New Main Service</button>
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
                                    <th>Main Service Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Services as $service)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $service->main_service }}</td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-primary" onclick="loadSubServices({{ $service->id }})">Edit</a>       
                                        <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" onclick="setDeleteRoute('{{ route('admin/delete-service', $service->id) }}')">
                                            Delete
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-success" onclick="openAddSubServiceModal({{ $service->id }})">Add</a>
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
                                    <th>Sub Service Name</th>
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
    
    
    <!-- Modal for Adding New Service -->
    <div class="modal fade" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-3">
                    <form method="POST" action="{{ route('admin/add-service') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="mainService">Main Service</label>
                            <input type="text" class="form-control" name="main_service" id="mainService" required placeholder="Enter Main Service">
                        </div>

                        <div class="sub-services">
                            <div class="mb-3">
                                <label class="form-label" for="subService">Sub Service</label>
                                <input type="text" class="form-control" name="sub_services[]" required placeholder="Enter Sub Service">
                            </div>
                        </div>
                        <button type="button" class="btn btn-link" id="addSubServiceBtn">Add Another Sub Service</button>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Add</button>
                        </div>
                    </form>
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


<!-- Modal to Add Sub Service -->
<div class="modal fade" id="addSubServiceModal" tabindex="-1" aria-labelledby="addSubServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubServiceModalLabel">Add Sub Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addSubServiceForm" action="{{ route('admin/add-sub-service') }}" method="POST">
                    @csrf
                    <input type="hidden" id="mainServiceId" name="main_service_id">
                    <div class="mb-3">
                        <label for="subServiceName" class="form-label">Sub Service Name</label>
                        <input type="text" class="form-control" id="subServiceName" name="sub_service" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Sub Service</button>
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

    function openAddSubServiceModal(serviceId) {
    // Set the service ID to the hidden input field
    document.getElementById('mainServiceId').value = serviceId;

    // Clear the sub-service name input field (optional)
    document.getElementById('subServiceName').value = '';

    // Show the modal
    new bootstrap.Modal(document.getElementById('addSubServiceModal')).show();
}

  document.addEventListener("DOMContentLoaded", function () {
        $("#datatables-reponsive").DataTable({
            responsive: true
        });
    });

    document.getElementById('addSubServiceBtn').addEventListener('click', function () {
        const subServiceDiv = document.createElement('div');
        subServiceDiv.classList.add('mb-3');
        subServiceDiv.innerHTML = `
            <label class="form-label" for="subService">Sub Service</label>
            <input type="text" class="form-control" name="sub_services[]" required placeholder="Enter Sub Service">
        `;
        document.querySelector('.sub-services').appendChild(subServiceDiv);
    });
</script>

<script>
    function loadSubServices(mainServiceId) {
        // Send AJAX request to fetch sub-services based on the main service id using jQuery
        $.ajax({
            url: `/admin/services/${mainServiceId}/sub-services`, // Adjust the URL if needed
            method: 'GET',
            success: function(data) {
                // Clear the previous sub-services from the table
                const subServicesTable = $('#subServicesTable tbody');
                subServicesTable.empty(); // Clears the existing rows
                
                // Append new sub-services to the table
                if (data.length > 0) {
                        data.forEach((subService, index) => {
                            const row = `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${subService.sub_service}</td>
                                    <td>
                                        <a href="#" 
                                        onclick="setDeleteRoute('/admin/delete-sub-service/${subService.id}')" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#confirmDeleteModal" 
                                        class="btn btn-danger">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            `;
                            subServicesTable.append(row); // Append the new row to the table
                        });
                    }
                    else {
                    // If no sub-services are found, show a message in the table
                    const row = `
                        <tr>
                            <td colspan="3" class="text-center">No sub-services available for this main service.</td>
                        </tr>
                    `;
                    subServicesTable.append(row); // Append the no data message
                }
            },
            error: function(error) {
                console.error('Error fetching sub-services:', error);
            }
        });
    }
    
    // Add event listener to the Add Sub-Service button
    $('#addSubServiceModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var serviceId = button.data('service-id'); // Extract service ID from data attribute
        var modal = $(this);
        modal.find('#main_service_id').val(serviceId); // Set the service ID in the hidden input
    });

    $('#addSubServiceForm').submit(function (e) {
    e.preventDefault();

    var formData = $(this).serialize();

    $.ajax({
        url: "{{ route('admin/add-sub-service') }}", // Add route for handling sub-service addition
        type: 'POST',
        data: formData,
        success: function (response) {
            if (response.success) {
                // You can update the page dynamically by appending the new sub-service
                // Example: Append the new sub-service to the table or refresh the list
                $('#addSubServiceModal').modal('hide'); // Hide the modal
                alert('Sub-Service added successfully!');
                location.reload(); // Optionally, reload the page to reflect changes
            }
        }
    });
});

</script>
@endsection
