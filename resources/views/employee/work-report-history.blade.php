@extends('layout/employee-sidebar')

@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        <img src="{{asset('storage/profile_photos').'/'.session('employee')->profile_photo_path}}" class="avatar img-fluid rounded me-1" />
    </div>
    <div class="flex-grow-1 ps-2">
        <p class="text-white">{{session('employee')->first_name}}</p>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mb-0">Work Reports</h4>
                            <p class="text-muted mb-0">View and manage your work history.</p>
                        </div>
                    </div>

                    <div class="row">
                        @foreach ($data as $report)
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="card" 
                                 data-bs-toggle="modal" 
                                 data-bs-target="#workReportModal" 
                                 data-report-date="{{ \Carbon\Carbon::parse($report->report_date)->format('Y-m-d') }}" 
                                 data-companies="{{ $report->company_list }}" 
                                 data-total-time="{{ $report->total_time }}">
                                 <div class="card-body">
                                    <h4>
                                        {{ \Carbon\Carbon::parse($report->report_date)->format('l, d M, Y') }}
                                        
                                        <!-- Show Edit Button Only for Today's Report -->
                                        @if(\Carbon\Carbon::parse($report->report_date)->isToday())
                                            <a href="{{route('emp/edit-work-report', $report->report_id)}}" class="float-end">
                                                <i class="fa-sharp fa-solid fa-pen fa-sm" style="color: #000000;"></i>
                                            </a>
                                        @endif
                                    </h4>
                                    <hr>
                                    <ul>
                                        @foreach (explode(',', $report->company_list) as $company)
                                            <li>{{ trim($company) }}</li>
                                        @endforeach
                                    </ul>
                                    <hr>
                                    <h4>Total Time: {{ $report->total_time }}</h4>
                                </div>                                                      
                            </div>
                        </div>
                    @endforeach                    
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
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
document.addEventListener('DOMContentLoaded', function () {
    // Listen for the modal show event
    $('#workReportModal').on('show.bs.modal', function (e) {
        var card = $(e.relatedTarget); // Get the card that triggered the modal
        var reportDate = card.data('report-date');
        var companies = card.data('companies');
        var totalTime = card.data('total-time');

        // Populate modal fields
        $('#modalReportDate').text(reportDate);
        $('#modalTotalTime').text('Total Time: ' + totalTime);

        // Populate company list dynamically
        var companyListHtml = '';
        var companiesArray = companies.split(','); // Split the companies by comma
        companiesArray.forEach(function (company) {
            companyListHtml += '<li>' + company.trim() + '</li>';
        });
        $('#modalCompanyList').html(companyListHtml);
    });
});

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.card').forEach(function (card) {
        card.addEventListener('click', function () {
            // Get the data-report-date attribute (assumed to be in Y-m-d format)
            const rawReportDate = card.getAttribute('data-report-date'); // e.g., "2024-11-27"

            if (!rawReportDate) {
                console.error('Missing report date!');
                return; // Exit early if there's no valid date
            }

            // Update the modal date (display the raw date in a readable format, e.g., "28 Nov, 2024")
            const formattedDate = new Date(rawReportDate).toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
            document.getElementById('modalReportDate').textContent = formattedDate;

            // Clear previous modal table content
            const modalTableBody = document.querySelector('#datatables-reponsive tbody');
            modalTableBody.innerHTML = '';

            // Fetch data for the selected date
            fetch(`/emp/work-report-detail/${rawReportDate}`)
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
});


</script>
@endsection
