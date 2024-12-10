@extends('layout/admin-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        <img src="{{asset('storage/profile_photos').'/'.session('user')->profile_photo_path}}" class="avatar img-fluid rounded me-1"  />
    </div>
    <div class="flex-grow-1 ps-2">
        
          <p class="text-white">{{session('user')->first_name}}</p>

    </div>
</div>
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid p-0">
    <div class="col-md-12">
        <div class="card">
        <div class="card-header">
            <h5 class="card-title float-start">Work Report</h5>
            <button type="button" class="btn btn-primary float-end" id="download-report">Download</button>
        </div>
        <div class="card-body">
            <form id="work-report-form">  
                @csrf  
                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label class="form-label" for="inputAddress">Select Employee</label>
                        <select name="employee" class="form-control" id="employee" required>
                            <option value="">Select Employee</option>
                            @foreach($data as $employee)
                            <option value="{{$employee->id}}">{{$employee->first_name.' '.$employee->last_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label" for="inputAddress">From date</label>
                        <input type="text" class="form-control" data-inputmask-alias="datetime"
                        data-inputmask-inputformat="dd/mm/yyyy"  name="sdate" required id="fdate">
                        <span class="text-muted">e.g "DD/MM/YYYY"</span>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label" for="inputPassword4">To Date</label>
                        <input type="text" class="form-control" data-inputmask-alias="datetime"
                        data-inputmask-inputformat="dd/mm/yyyy"  name="edate" required id="tdate">
                        <span class="text-muted">e.g "DD/MM/YYYY"</span>
                    </div>
                </div>
                <button type="button" class="btn btn-success float-end" id="submit-report">View Report</button>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row" id="report-results">
                <!-- Cards will be dynamically added here -->
            </div>            
        </div>
    </div>
    </div>
</div>

<div class="modal fade" id="workReportModal" tabindex="-1" aria-labelledby="workReportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="workReportModalLabel">Work Report Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 id="modalReportDate"></h4>
                <hr>
                <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Client Name</th>
                            <th>Task Name</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic content will be inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
   document.getElementById('submit-report').addEventListener('click', function () {
    // Collect form data
    const form = document.getElementById('work-report-form');
    const formData = new FormData(form);

    // AJAX request
    fetch('{{ route("admin/get-work-report") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        const resultsDiv = document.getElementById('report-results');
        resultsDiv.innerHTML = ''; // Clear previous results

        if (data.success) {
            // Loop through the data and generate cards
            data.data.forEach(report => {
                const card = `
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card" 
                            data-bs-toggle="modal" 
                            data-bs-target="#workReportModal" 
                            data-report-date="${new Date(report.report_date).toISOString().split('T')[0]}" 
                            data-companies="${report.company_list}" 
                            data-total-time="${report.total_time}" 
                            data-user-id="${report.user_id}">
                            <div class="card-body">
                                <h4>${new Date(report.report_date).toDateString()}</h4>
                                <hr>
                                <ul>
                                    ${report.company_list
                                        .split(',')
                                        .map(company => `<li>${company.trim()}</li>`)
                                        .join('')}
                                </ul>
                                <hr>
                                <h4>Total Time: ${report.total_time}</h4>
                            </div>
                        </div>
                    </div>
                `;
                resultsDiv.innerHTML += card; // Append the card to the results div
            });

            // Reattach the click event listeners after dynamically adding cards
            attachCardClickEventListeners();
        } else {
            resultsDiv.innerHTML = `<p class="text-danger">${data.message}</p>`;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const resultsDiv = document.getElementById('report-results');
        resultsDiv.innerHTML = `<p class="text-danger">An error occurred while fetching the data.</p>`;
    });
});

// Function to attach click event listeners to dynamically generated cards
function attachCardClickEventListeners() {
    document.querySelectorAll('.card').forEach(function (card) {
        card.addEventListener('click', function () {
            // Get the data attributes
            const rawReportDate = card.getAttribute('data-report-date'); // e.g., "2024-11-27"
            const userId = card.getAttribute('data-user-id'); // Get the user_id attribute

            if (!rawReportDate || !userId) {
                console.error('Missing report date or user ID!');
                return; // Exit early if there's no valid date or userId
            }

            // Format the report date
            const formattedDate = new Date(rawReportDate).toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
            document.getElementById('modalReportDate').textContent = formattedDate;

            // Clear previous modal table content
            const modalTableBody = document.querySelector('#datatables-reponsive tbody');
            modalTableBody.innerHTML = '';

            // Fetch data for the selected date and userId
            fetch(`/admin/work-report-detail/${rawReportDate}/${userId}`)
                .then(response => response.json())
                .then(data => {
                    data.details.forEach(row => {
                        const tableRow = `
                            <tr>
                                <td>${row.client_name}</td>
                                <td>${row.task_name}</td>
                                <td>${row.start_time}</td>
                                <td>${row.end_time}</td>
                                <td><span class="badge rounded-pill text-white text-bg-${row.status_class}">${row.status}</span></td>
                            </tr>
                        `;
                        modalTableBody.insertAdjacentHTML('beforeend', tableRow);
                    });
                })
                .catch(error => {
                    console.error('Error fetching report data:', error);
                });
        });
    });
}

// Initialize the event listeners for cards on page load (if any are already present)
document.addEventListener('DOMContentLoaded', function () {
    attachCardClickEventListeners();
});

</script>

<script>
    
document.getElementById('download-report').addEventListener('click', function () {
    // Collect date and employee data from the form
    const fromDate = document.getElementById('fdate').value;
    const toDate = document.getElementById('tdate').value;
    const employeeId = document.getElementById('employee').value;

    if (!fromDate || !toDate || !employeeId) {
        alert('Please fill in all fields before downloading the report.');
        return;
    }

    // Create a hidden form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin/report-download") }}';

    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
    form.appendChild(csrfInput);

    // Add fromDate input
    const fromDateInput = document.createElement('input');
    fromDateInput.type = 'hidden';
    fromDateInput.name = 'sdate';
    fromDateInput.value = fromDate;
    form.appendChild(fromDateInput);

    // Add toDate input
    const toDateInput = document.createElement('input');
    toDateInput.type = 'hidden';
    toDateInput.name = 'edate';
    toDateInput.value = toDate;
    form.appendChild(toDateInput);

    // Add employee ID input
    const employeeInput = document.createElement('input');
    employeeInput.type = 'hidden';
    employeeInput.name = 'employee';
    employeeInput.value = employeeId;
    form.appendChild(employeeInput);

    // Append the form to the body and submit
    document.body.appendChild(form);
    form.submit();
});

</script>
@endsection