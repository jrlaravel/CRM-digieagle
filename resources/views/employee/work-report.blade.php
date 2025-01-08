@extends('layout/employee-sidebar')

@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        <img src="{{asset('storage/profile_photos').'/'.session('employee')->profile_photo_path}}" class="avatar img-fluid rounded me-1"  />
    </div>
    <div class="flex-grow-1 ps-2">
        <p class="text-white">{{session('employee')->first_name}}</p>
    </div>
</div>
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row">
                <!-- Card for Form Section -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Add Work Report</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Select Date</label>
                                <input type="text" class="form-control" id="report-date" value="date()" placeholder="Select date.." />
                            </div>
                                                
            
                            <!-- Work report form -->
                            <form id="workReportForm">
                                <div id="work-report-container">
                                    <div class="mb-3 work-report-item">
                                        <label class="form-label">Client Name</label>
                                        <select class="form-control choices-single" name="company_name[]" required id="company-dropdown">
                                            <option value="">Select a Company</option>
                                            @foreach($companydata as $data)
                                                <option value="{{ $data->company_id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
            
                                        <label class="form-label">Client Service list</label>
                                        <select name="service[]" class="form-control" id="service"  required>
                                            <option value="">Select Service</option>
                                        </select>
            
                                        <label class="form-label">Start Time</label>
                                        <input type="time" name="start_time[]" class="form-control start-time" id="start-time"  required />
            
                                        <label class="form-label">End Time</label>
                                        <input type="time" name="end_time[]" class="form-control end-time" id="end-time"  required />
            
                                        <label for="form-label">Status</label>
                                        <select name="status[]" class="form-control" required  id="status">
                                            <option value="">Select Status</option>
                                            <option value="Completed">Completed</option>
                                            <option value="Ongoing">Ongoing</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Onhold">On-hold</option>
                                        </select>
            
                                        <label class="form-label">Note</label>
                                        <textarea class="form-control" id="note" name="note[]" rows="3" placeholder="Enter note"></textarea>

                                        <label class="form-label">Total Time Taken (hours)</label>
                                        <input type="time" name="total_time[]" class="form-control total-time" id="total-time"  readonly />
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success mt-3" id="addServiceBtn" >Add More Services</button>
                            </form>
                        </div>
                    </div>
                </div>
            
                <!-- Card for Table Section -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Submitted Work Reports</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <thead>
                                    <table class="table table-bordered" id="workReportTable">
                                        <tr>
                                            <th>Client name</th>
                                            <th>Client service</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Status</th>
                                            <th>Note</th>
                                            <th>Total Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Dynamically added rows will appear here -->
                                    </tbody>
                                </table>
                                
                                <button type="button" id="submitReportBtn" class="btn btn-primary mt-3 float-end">Submit Report</button>
                                <!-- Table to display the work reports -->
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </main>

    <!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Report have been added successfully!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
@endsection
    


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Flatpickr JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
let tableData = [];

document.addEventListener("DOMContentLoaded", function () {
    flatpickr("#report-date", {
        defaultDate: "today",
        minDate: "today",
        maxDate: "today",
        dateFormat: "Y-m-d",
        disableMobile: true
    });

    flatpickr(".flatpickr-minimum");

    function calculateTotalTime() {
        const startTimes = document.querySelectorAll(".start-time");
        const endTimes = document.querySelectorAll(".end-time");
        const totalTimes = document.querySelectorAll(".total-time");

        startTimes.forEach((startTime, index) => {
            const endTime = endTimes[index];
            const totalTime = totalTimes[index];

            if (startTime.value && endTime.value) {
                const [startHours, startMinutes] = startTime.value.split(":").map(Number);
                const [endHours, endMinutes] = endTime.value.split(":").map(Number);

                const startTotalMinutes = startHours * 60 + startMinutes;
                const endTotalMinutes = endHours * 60 + endMinutes;

                let diffMinutes = endTotalMinutes - startTotalMinutes;

                if (diffMinutes < 0) {
                    diffMinutes += 24 * 60;
                }

                const hours = Math.floor(diffMinutes / 60);
                const minutes = diffMinutes % 60;

                totalTime.value = `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}`;
            } else {
                totalTime.value = ''; // Clear the field if inputs are incomplete
            }
        });
    }

    document.addEventListener("input", function (event) {
        if (event.target.classList.contains("start-time") || event.target.classList.contains("end-time")) {
            calculateTotalTime();
        }
    });

    function validateFields() {
        const companies = document.getElementsByName("company_name[]");
        const services = document.getElementsByName("service[]");
        const startTimes = document.getElementsByName("start_time[]");
        const endTimes = document.getElementsByName("end_time[]");
        const status = document.getElementsByName("status[]");
        const totalTimes = document.getElementsByName("total_time[]");

        for (let i = 0; i < companies.length; i++) {
            if (!companies[i].value || !services[i].value || !startTimes[i].value || !endTimes[i].value || !status[i].value || !totalTimes[i].value) {
                return false;
            }
        }
        return true;
    }

    document.getElementById("addServiceBtn").addEventListener("click", function () {
        const companies = document.getElementsByName("company_name[]");
        const services = document.getElementsByName("service[]");
        const startTimes = document.getElementsByName("start_time[]");
        const endTimes = document.getElementsByName("end_time[]");
        const status = document.getElementsByName("status[]");
        const notes = document.getElementsByName("note[]");
        const totalTimes = document.getElementsByName("total_time[]");

        const companyName = companies[companies.length - 1].options[companies[companies.length - 1].selectedIndex]?.text || '';
        const serviceName = services[services.length - 1].options[services[services.length - 1].selectedIndex]?.text || '';
        const startTime = startTimes[startTimes.length - 1].value || '';
        const endTime = endTimes[endTimes.length - 1].value || '';
        const statusValue = status[status.length - 1].value || '';
        const noteValue = notes[notes.length - 1]?.value || ''; // Allow empty or null note
        const totalTime = totalTimes[totalTimes.length - 1].value || '';

        if (!companyName || !serviceName || !startTime || !endTime || !statusValue || !totalTime) {
            alert("Please fill in all required fields before adding the service.");
            return;
        }

        const rowData = {
            companyName,
            serviceName,
            startTime,
            endTime,
            status: statusValue,
            note: noteValue, // Include note even if empty
            totalTime
        };
        tableData.push(rowData);

        const tableBody = document.getElementById("workReportTable").getElementsByTagName('tbody')[0];
        const row = tableBody.insertRow();

        row.insertCell(0).innerText = companyName;
        row.insertCell(1).innerText = serviceName;
        row.insertCell(2).innerText = startTime;
        row.insertCell(3).innerText = endTime;
        row.insertCell(4).innerText = statusValue;
        row.insertCell(5).innerText = noteValue || 'N/A'; // Show 'N/A' if note is empty
        row.insertCell(6).innerText = totalTime;

        const removeBtnCell = row.insertCell(7);
        const removeBtn = document.createElement("button");
        removeBtn.innerText = "Remove";
        removeBtn.classList.add("remove-btn", "btn", "btn-danger");
        removeBtn.onclick = function () {
            row.remove();
            tableData = tableData.filter(data => 
                !(data.companyName === companyName &&
                    data.serviceName === serviceName &&
                    data.startTime === startTime &&
                    data.endTime === endTime &&
                    data.status === statusValue &&
                    data.note === noteValue &&
                    data.totalTime === totalTime)
            );
        };
        removeBtnCell.appendChild(removeBtn);

        companies[companies.length - 1].value = '';
        services[services.length - 1].value = '';
        startTimes[startTimes.length - 1].value = '';
        endTimes[endTimes.length - 1].value = '';
        status[status.length - 1].value = '';
        notes[notes.length - 1].value = ''; // Reset note field
        totalTimes[totalTimes.length - 1].value = '';
    });

    document.getElementById("submitReportBtn").addEventListener("click", function () {
        if (tableData.length === 0) {
            alert("No data to submit.");
            return;
        }

        const reportDate = document.getElementById("report-date").value || new Date().toISOString().split('T')[0];

        sendToController(tableData, reportDate);
    });

    function sendToController(data, reportDate) {
        fetch('/emp/add-work-report', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                report_data: data,
                report_date: reportDate,
                user_id: {{ session('employee')->id }}
            })
        })
        .then(response => response.json())
        .then(responseData => {
            if (responseData.success) {
                // Open the modal when the report is added successfully
                const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                confirmationModal.show();

                // Automatically close the modal after 3 seconds
                setTimeout(() => {
                    confirmationModal.hide();
                    window.location.href = "{{ route('emp/work-report-history') }}";
                }, 3000);
            } else {
                alert('Failed to submit the report: ' + responseData.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting the report.');
        });
    }
});

$(function() {
    let serviceChoices;
    $('#company-dropdown').on('change', function() {
        var companyId = this.value; // Get selected company ID
        $('#service').html(''); // Clear the service dropdown

        if (serviceChoices) {
            serviceChoices.destroy(); // Destroy previous Choices instance
        }

        if (companyId) {
            $('#service').prop('disabled', false); // Enable dropdown

            $.ajax({
                url: '/emp/get-services/' + companyId, // Replace with your actual route
                type: 'GET',
                success: function(data) {
                    if (data.services && data.services.length > 0) {
                        $('#service').html('<option value="">Select Service</option>'); // Reset options
                        $.each(data.services, function(key, value) {
                            $('#service').append('<option value="' + value.serviceid + '">' + value.service_name + '</option>');
                        });

                        // Initialize Choices.js on the service dropdown
                        serviceChoices = new Choices('#service', {
                            searchEnabled: true,
                            placeholderValue: 'Search service...',
                            removeItemButton: false, // Adjust as needed
                        });
                    } else {
                        $('#service').html('<option value="">No services available</option>');
                        $('#service').prop('disabled', true);
                    }
                },
                error: function() {
                    console.error('Error fetching services');
                    $('#service').prop('disabled', true);
                    $('#service').html('<option value="">Select Service</option>');
                }
            });
        } else {
            $('#service').prop('disabled', true);
            $('#service').html('<option value="">Select Service</option>');
        }
    });
});
   
document.addEventListener('DOMContentLoaded', function () {
    new Choices('#company-dropdown', {
        searchEnabled: true, // Enables search func tionality
        placeholderValue: 'Search Company...',
    });
});


</script>
@endsection
