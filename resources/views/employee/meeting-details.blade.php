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
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-fluid p-0">
        <div class="d-flex justify-content-between align-items-center mb-3 mb-3">
            <h1 class="h3 d-inline align-middle">Meeting List</h1>    
            <a class="btn btn-primary float-end" href="#" data-bs-toggle="modal" data-bs-target="#clientMeetingModal">
                Schedule Meeting
            </a>
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
                                        <th>Lead Name</th>
                                        <th>description</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($meetings as $key => $data)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$data->first_name.' '.$data->last_name}}</td>
                                        <td>{{$data->description}}</td>
                                        <td>{{ $data->meeting_date }}</td>
                                        <td>{{ $data->start_time }}</td>
                                        <td>
                                            <button class="btn btn-primary edit-btn" 
                                                data-id="{{ $data->id }}"
                                                data-lead_id="{{ $data->lead_id }}"
                                                data-description="{{ $data->description }}"
                                                data-meeting_date="{{ $data->meeting_date }}"
                                                data-start_time="{{ $data->start_time }}">
                                                Edit
                                            </button>
                                            <button class="btn btn-danger delete-btn" data-id="{{ $data->id }}">Delete</button>
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

    <div class="modal fade" id="clientMeetingModal" tabindex="-1" aria-labelledby="clientMeetingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clientMeetingModalLabel">Schedule Client Meeting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="clientMeetingForm">
                        <div class="mb-3">
                            <label for="lead_id" class="form-label">Client Name</label>
                            <select name="lead_id" class="form-control" required id="lead_id">
                            <option value="">Select Client</option>
                            @foreach($lead as $value)
                            <option value="{{ $value->id }}">{{ $value->first_name .' '. $value->last_name }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="meeting_date" class="form-label">Meeting Date</label>
                            <input type="date" class="form-control" id="meeting_date" name="meeting_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" required>
                        </div>
                        <button type="submit" class="btn btn-success">Save Meeting</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"> 
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                </div>
                <div class="modal-body text-center">
                    <p id="successMessage">Meeting added successfully!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this meeting?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
    </div>

    {{-- edit meeting modal --}}
    <div class="modal fade" id="editMeetingModal" tabindex="-1" aria-labelledby="editMeetingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMeetingModalLabel">Edit Client Meeting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editMeetingForm">
                        <input type="hidden" id="edit_meeting_id" name="meeting_id">
    
                        <div class="mb-3">
                            <label for="edit_lead_id" class="form-label">Client Name</label>
                            <select name="lead_id" class="form-control" required id="edit_lead_id">
                                <option value="">Select Client</option>
                                @foreach($lead as $value)
                                    <option value="{{ $value->id }}">{{ $value->first_name .' '. $value->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_meeting_date" class="form-label">Meeting Date</label>
                            <input type="date" class="form-control" id="edit_meeting_date" name="meeting_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_start_time" class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="edit_start_time" name="start_time" required>
                        </div>
                        <button type="submit" class="btn btn-success">Update Meeting</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    

@endsection

@section('scripts')
    <script>
        document.getElementById('clientMeetingForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            let formData = new FormData(this);

            fetch("{{ route('emp/client-meeting-store') }}", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    // Get modal instances
                    let meetingModalEl = document.getElementById('clientMeetingModal');
                    let successModalEl = document.getElementById('successModal');

                    let meetingModal = bootstrap.Modal.getInstance(meetingModalEl); // Hide meeting modal
                    let successModal = new bootstrap.Modal(successModalEl); // Show success modal

                    if (meetingModal) {
                        meetingModal.hide();
                    }

                    successModal.show();

                    // Hide success modal after 3 seconds
                    setTimeout(() => {
                        successModal.hide();
                        location.reload(); // Reload page
                    }, 3000);
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => console.error("Error:", error));
        });


        document.addEventListener('DOMContentLoaded', function () {
            let deleteId = null; // Store the ID of the meeting to delete

            // When delete button is clicked, show the confirmation modal
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function () {
                    deleteId = this.getAttribute('data-id'); // Get meeting ID
                    let deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
                    deleteModal.show();
                });
            });

            // When user confirms deletion
            document.getElementById('confirmDelete').addEventListener('click', function () {
                if (deleteId) {
                    fetch(`/emp/client-meeting-delete/${deleteId}`, {
                        method: "GET",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            let deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
                            deleteModal.hide(); // Hide confirmation modal

                            document.getElementById('successMessage').innerText = "Meeting deleted successfully!";
                            let successModal = new bootstrap.Modal(document.getElementById('successModal'));
                            successModal.show(); // Show success modal

                            // Hide success modal after 3 seconds and reload page
                            setTimeout(() => {
                                successModal.hide();
                                location.reload();
                            }, 3000);
                        } else {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
                }
            });
        });

   
        document.addEventListener('DOMContentLoaded', function () {
            let editMeetingModal = new bootstrap.Modal(document.getElementById('editMeetingModal'));
    
            // Open Edit Modal and Fill Data
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function () {
                    // Get data from the clicked button
                    let meetingId = this.getAttribute('data-id');
                    let leadId = this.getAttribute('data-lead_id');
                    let description = this.getAttribute('data-description');
                    let meetingDate = this.getAttribute('data-meeting_date');
                    let startTime = this.getAttribute('data-start_time');
    
                    // Fill the modal with the values from the button
                    document.getElementById('edit_meeting_id').value = meetingId;
                    document.getElementById('edit_lead_id').value = leadId;
                    document.getElementById('edit_description').value = description;
                    document.getElementById('edit_meeting_date').value = meetingDate;
                    document.getElementById('edit_start_time').value = startTime;
    
                    // Show the modal
                    editMeetingModal.show();
                });
            });
    
            // Handle Form Submission
            document.getElementById('editMeetingForm').addEventListener('submit', function (event) {
                event.preventDefault();
    
                let formData = new FormData(this);
    
                fetch("{{ route('emp/client-meeting-update') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        editMeetingModal.hide();
    
                        // Show success modal
                        document.getElementById('successMessage').innerText = "Meeting updated successfully!";
                        let successModal = new bootstrap.Modal(document.getElementById('successModal'));
                        successModal.show();
    
                        // Hide success modal after 3 seconds and reload
                        setTimeout(() => {
                            successModal.hide();
                            location.reload();
                        }, 3000);
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => console.error("Error:", error));
            });
        });
    </script>
      
@endsection