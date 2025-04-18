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
        <p class="text-white">{{session('employee')->first_name}}</p>
    </div>
</div>
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<main class="">
        <div class="container-fluid p-0">
            <div class="row">
                <!-- Card for Table Section -->
                <div class="col-lg-8 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title float-start">Submitted Work Reports</h5>
                            <button class="btn btn-outline-primary float-end" id="addnewtaskbutton">+ Add New Task</button>
                        </div>
                        @if(Session::has('error'))
                        <div class="alert alert-danger">{{Session::get('error')}}</div>
                        @endif
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="workReportTable">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Company</th>
                                            <th>Service</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Status</th>
                                            <th>Note</th>
                                            <th>Total Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $key => $report) 
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $report->cname }}</td>
                                            <td>{{ $report->sname }}</td>
                                            <td>{{ $report->start_time }}</td>
                                            <td>{{ $report->end_time }}</td>
                                            <td>{{ $report->status }}</td>  
                                             <td>{{ $report->note }}</td>
                                            <td>{{ $report->total_time }}</td>
                                            <td>
                                                <button 
                                                    class="btn btn-primary edit-report-btn"
                                                    id="addServiceBtn"
                                                    data-date="{{ $report->date }}"
                                                    data-company-id="{{ $report->cid }}" 
                                                    data-service-id="{{ $report->sid }}" 
                                                    data-start-time="{{ $report->start_time }}" 
                                                    data-end-time="{{ $report->end_time }}" 
                                                    data-status="{{ $report->status }}"
                                                    data-note="{{ $report->note }}"
                                                    data-total-time="{{ $report->total_time }}"
                                                    data-wrdid="{{ $report->wrdid}}"
                                                >
                                                    Edit
                                                </button>
                                                <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" onclick="setDeleteRoute('{{route('emp/delete-report-task',$report->wrdid)}}')" href="#">Remove</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card for Form Section -->
                <div class="col-lg-4 col-md-12 mb-4">
                    <div class="card" id="editask">
                        <div class="card-header">
                            <h5 class="card-title">Edit Work Report</h5>
                        </div>
                        <div class="card-body">
                            
                            <!-- Work report form -->
                            <form id="workReportEditForm" action="{{ route('emp/update-work-report') }}" method="post">
                                @csrf
                                <div id="work-report-container">
                                    <div class="mb-3 work-report-item">
                                        <div class="mb-3">
                                            <label class="form-label">Select Date</label>
                                            <input type="date" class="form-control" id="report-date" value="" name="report_date" placeholder="Select date.." />
                                        </div>
                            
                                        <input type="text" name="wrdid" id="wrdid" hidden>
                            
                                        <label class="form-label">Company Name</label>
                                        <select class="form-control choices-single" name="company_name[]" required id="company-dropdown">
                                            <option value="">Select a Company</option>
                                            @foreach($companydata as $data)
                                                <option value="{{ $data->company_id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                            
                                        <label class="form-label">Service</label>
                                        <select name="service[]" class="form-control" id="service" required>
                                            <option value="">Select Service</option>
                                        </select>
                            
                                        <label class="form-label">Start Time</label>
                                        <input type="time" name="start_time[]" class="form-control start-time" id="start-time" required />
                            
                                        <label class="form-label">End Time</label>
                                        <input type="time" name="end_time[]" class="form-control end-time" id="end-time" required />
                            
                                        <label for="form-label">Status</label>
                                        <select name="status[]" class="form-control" required id="status">
                                            <option value="">Select Status</option>
                                            @foreach($status as $value)
                                             <option value="{{$value->name}}">{{$value->name}}</option>
                                             @endforeach
                                        </select>
                            
                                        <label class="form-label">Note</label>
                                        <textarea class="form-control" id="note" name="note" rows="3" placeholder="Enter note" required></textarea>

                                        <label class="form-label">Total Time Taken (hours)</label>
                                        <input type="time" name="total_time[]" class="form-control total-time" id="total-time" readonly />
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success mt-3">Save Changes</button>
                            </form>              
                        </div>
                    </div>
                    <div class="card" id="addtask">
                        <div class="card-header">
                            <h5 class="card-title">Add Work Report</h5>
                        </div>
                        <div class="card-body">
                            @if(Session::has('success'))
                            <div class="alert alert-success">{{Session::get('success')}}</div>
                            @endif
                            <!-- Add work report form -->
                            <form id="workReportAddForm" action="{{route('emp/add-task-report')}}" method="post">
                                @csrf
                                <div id="work-report-container-add">
                                    <div class="mb-3 work-report-item">

                                        <input type="hidden" value="{{session('employee')->id}}" name="user_id">

                                        <div class="mb-3">
                                            <label class="form-label">Select Date</label>
                                            <input type="text" class="form-control" id="report-date" value="date()" name="report_date" placeholder="Select date.." />
                                        </div>
                                        
                    
                                        <label class="form-label">Company Name</label>
                                        <select class="form-control choices-single" name="company_name" required id="add-company-dropdown">
                                            <option value="">Select a Company</option>
                                            @foreach($companydata as $data)
                                                <option value="{{ $data->company_id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                    
                                        <label class="form-label">Service</label>
                                        <select name="service" class="form-control" id="add-service" required>
                                            <option value="">Select Service</option>
                                        </select>
                    
                                        <label class="form-label">Start Time</label>
                                        <input type="time" name="start_time" class="form-control start-time" id="add-start-time" required />
                    
                                        <label class="form-label">End Time</label>
                                        <input type="time" name="end_time" class="form-control end-time" id="add-end-time" required />
                    
                                        <label for="form-label">Status</label>
                                        <select name="status" class="form-control" required id="status">
                                            <option value="">Select Status</option>
                                            @foreach($status as $value)
                                             <option value="{{$value->name}}">{{$value->name}}</option>
                                             @endforeach
                                        </select>
                                        
                                        <label class="form-label">Note</label>
                                        <textarea class="form-control" id="add-note" name="note" placeholder="Enter note" rows="3" required></textarea>


                                        <label class="form-label">Total Time Taken (hours)</label>
                                        <input type="time" name="total_time" class="form-control total-time" id="add-total-time" readonly />
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success mt-3">Add Task</button>
                            </form>
                        </div>
                    </div>
                    
                </div>  
            </div>
        </div>
</main>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this Task? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="deleteConfirmButton" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('scripts')
<script>

     // Get today's date in the format YYYY-MM-DD
    const today = new Date().toISOString().split('T')[0];

    // Set the value of the input to today's date
    document.getElementById('report-date').value = today;

    // Disable all dates before today
    document.getElementById('report-date').setAttribute('min', today);
    document.getElementById('report-date').setAttribute('max', today);

    // confirmation message
    function setDeleteRoute(route) {
        document.getElementById('deleteConfirmButton').setAttribute('href', route);
    }


    $(function() {
    function fetchServices(companyDropdownId, serviceDropdownId) {
        var companyId = $(companyDropdownId).val(); // Get selected company ID
        $(serviceDropdownId).html('<option value="">Select Service</option>'); // Reset service dropdown

        if (companyId) {
            $(serviceDropdownId).prop('disabled', false); // Enable dropdown

            // Fetch services based on the selected company
            $.ajax({
                url: '/emp/get-services/' + companyId,
                type: 'GET',
                success: function(data) {
                    if (data.services && data.services.length > 0) {
                        $(serviceDropdownId).html('<option value="">Select Service</option>');
                        $.each(data.services, function(key, value) {
                            $(serviceDropdownId).append('<option value="' + value.serviceid + '">' + value.service_name + '</option>');
                        });
                    } else {
                        $(serviceDropdownId).html('<option value="">No services available</option>');
                        $(serviceDropdownId).prop('disabled', true);
                    }
                },
                error: function() {
                    console.error('Error fetching services');
                    $(serviceDropdownId).html('<option value="">Select Service</option>');
                    $(serviceDropdownId).prop('disabled', true);
                }
            });
        } else {
            $(serviceDropdownId).prop('disabled', true).html('<option value="">Select Service</option>');
        }
    }

    // Event listeners for company dropdowns
    $('#add-company-dropdown').on('change', function() {
        fetchServices('#add-company-dropdown', '#add-service');
    });

    $('#company-dropdown').on('change', function() {
        fetchServices('#company-dropdown', '#service');
    });
});


    // Save changes button click handler
    $('#addServiceBtn').on('click', function() {
        const formData = $('#workReportForm').serialize(); // Serialize form data
        console.log('Form Data:', formData); // Debugging

        // Add form submission logic here (e.g., AJAX request to save the work report)
    });



    document.addEventListener('DOMContentLoaded', () => {
        const editButtons = document.querySelectorAll('.edit-report-btn');

        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Retrieve data attributes from the clicked button
                const companyId = button.getAttribute('data-company-id');
                const serviceId = button.getAttribute('data-service-id');
                const startTime = button.getAttribute('data-start-time');
                const endTime = button.getAttribute('data-end-time');
                const totalTime = button.getAttribute('data-total-time');
                const status = button.getAttribute('data-status');
                const note = button.getAttribute('data-note');
                const reportDate = button.getAttribute('data-date');
                const wrdid = button.getAttribute('data-wrdid');

                // Populate the Company Name dropdown and trigger change to fetch services
                const companyDropdown = document.getElementById('company-dropdown');
                companyDropdown.value = companyId;
                $('#company-dropdown').trigger('change'); // Trigger change event to fetch services

                // Wait for services to load, then set the service dropdown value
                setTimeout(() => {
                    const serviceDropdown = document.getElementById('service');
                    serviceDropdown.value = serviceId;
                }, 500); // Adjust timeout based on your AJAX response time

                // Populate the other inputs
                document.getElementById('start-time').value = startTime;
                document.getElementById('end-time').value = endTime;
                document.getElementById('status').value = status;
                document.getElementById('note').value = note;
                document.getElementById('report-date').value = reportDate;
                document.getElementById('total-time').value = totalTime;
                document.getElementById('wrdid').value = wrdid; 

                // Optionally scroll to the form
                document.getElementById('workReportEditForm').scrollIntoView({ behavior: 'smooth' });
            });
        });
    });

    $(document).ready(function () {
    // By default, show the #editask div and hide the #addtask div
    $('#editask').hide();
    $('#addtask').show();

    // When the "Add New Task" button is clicked
    $('#addnewtaskbutton').click(function () {
        // Hide the #editask div and show the #addtask div
        $('#editask').hide();
        $('#addtask').show();
    });

    // When the "Edit Task" button is clicked
    $('#edittaskbutton').click(function () {
        // Hide the #addtask div and show the #editask div
        $('#addtask').hide();
        $('#editask').show();
    });

    // Optional: If you want to handle saving changes
    $('#addServiceBtn').click(function () {
        // Handle form submission logic here

        // After saving, switch back to edit view
        $('#editask').show();
        $('#addtask').hide();
    });
});

    
</script>

<script>
    // Function to calculate the time difference in hours and minutes
    function calculateTotalTime() {
        var startTime = document.getElementById('start-time').value;
        var endTime = document.getElementById('end-time').value;

        if (startTime && endTime) {
            var start = new Date('1970-01-01T' + startTime + 'Z'); // create Date object for start time
            var end = new Date('1970-01-01T' + endTime + 'Z'); // create Date object for end time

            if (end < start) {
                alert('End time must be later than start time');
                return; // if end time is earlier than start time, do nothing
            }

            // Calculate the time difference in minutes
            var diffInMs = end - start;
            var totalMinutes = diffInMs / 1000 / 60; // Convert from milliseconds to minutes

            // Calculate total hours and minutes
            var hours = Math.floor(totalMinutes / 60);
            var minutes = totalMinutes % 60;

            // Format total time as HH:mm
            var totalTime = formatTime(hours, minutes);

            // Update the total time field
            document.getElementById('total-time').value = totalTime;
        }
    }

    // Helper function to format time as HH:mm
    function formatTime(hours, minutes) {
        // Pad single digit hours and minutes with leading zeros
        var formattedHours = hours.toString().padStart(2, '0');
        var formattedMinutes = minutes.toString().padStart(2, '0');
        return formattedHours + ':' + formattedMinutes;
    }

    // Add event listeners to recalculate total time whenever start or end time changes
    document.getElementById('start-time').addEventListener('input', calculateTotalTime);
    document.getElementById('end-time').addEventListener('input', calculateTotalTime);
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Function to calculate the total time
        function calculateTotalTime() {
            // Get the values of start time and end time
            const startTime = document.getElementById('add-start-time').value;
            const endTime = document.getElementById('add-end-time').value;

            // Proceed only if both start time and end time are set
            if (startTime && endTime) {
                const start = new Date(`1970-01-01T${startTime}Z`);
                const end = new Date(`1970-01-01T${endTime}Z`);

                // Ensure end time is not earlier than start time
                if (end < start) {
                    alert('End time must be later than start time.');
                    document.getElementById('add-total-time').value = '';
                    return;
                }

                // Calculate the difference in minutes
                const diffInMinutes = (end - start) / 1000 / 60;

                // Convert the difference into hours and minutes
                const hours = Math.floor(diffInMinutes / 60);
                const minutes = diffInMinutes % 60;

                // Format as HH:mm
                const formattedTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;

                // Display the total time
                document.getElementById('add-total-time').value = formattedTime;
            }
        }

        // Add event listeners for dynamic calculation
        document.getElementById('add-start-time').addEventListener('input', calculateTotalTime);
        document.getElementById('add-end-time').addEventListener('input', calculateTotalTime);
    });
</script>

<script>
    // Initialize flatpickr if needed
    flatpickr("#report-date", {
            defaultDate: "today",
            minDate: "today",
            maxDate: "today",
            dateFormat: "Y-m-d",
            disableMobile: true
        });

        // Flatpickr initialization
        flatpickr(".flatpickr-minimum");

</script>



@endsection
