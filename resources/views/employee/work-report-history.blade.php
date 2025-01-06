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

                    <div class="row mt-4">
                        @foreach ($data as $report)
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="card" style="box-shadow: 0px 0px 3px 0px rgb(83 182 162); !important" >
                                 <div class="card-body">
                                    <h4>
                                        {{ \Carbon\Carbon::parse($report->report_date)->format('l, d M, Y') }}
                                    
                                        <!-- Dropdown Menu -->
                                        <div class="dropdown float-end">
                                            <i class="fa fa-ellipsis-v" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <!-- View Option -->
                                                <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" 
                                                    data-bs-target="#workReportModal" 
                                                    data-report-date="{{ \Carbon\Carbon::parse($report->report_date)->format('Y-m-d') }}" 
                                                    data-companies="{{ $report->company_list }}" 
                                                    data-total-time="{{ $report->total_time }}">
                                                        <i class="fa fa-eye me-2" aria-hidden="true"></i>View
                                                    </a>
                                                </li>
                                                <!-- Edit Option -->
                                                @if(\Carbon\Carbon::parse($report->report_date)->isToday())
                                                    <li>
                                                        <a class="dropdown-item" href="{{route('emp/edit-work-report', $report->report_id)}}">
                                                            <i class="fa fa-pen me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
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
        var link = $(e.relatedTarget); // Get the link that triggered the modal
        var reportDate = link.data('report-date');
        var companies = link.data('companies');
        var totalTime = link.data('total-time');

        // Populate modal fields
        $('#modalReportDate').text('Report Date: ' + reportDate);
        $('#modalTotalTime').text('Total Time: ' + totalTime);

        // Populate company list dynamically
        var companyListHtml = '';
        var companiesArray = companies.split(',');
        companiesArray.forEach(function (company) {
            companyListHtml += '<li>' + company.trim() + '</li>';
        });
        $('#modalCompanyList').html(companyListHtml);

        // Clear previous modal table content
        var modalTableBody = $('#datatables-reponsive tbody');
        modalTableBody.html('');

        // Fetch detailed data for the selected report
        fetch(`/emp/work-report-detail/${reportDate}`)
            .then(response => response.json())
            .then(data => {
                data.details.forEach(row => {
                    var tableRow = `
                        <tr>
                            <td>${row.client_name}</td>
                            <td>${row.task_name}</td>
                            <td>${row.start_time}</td>
                            <td>${row.end_time}</td>
                            <td><span class="badge rounded-pill text-white text-bg-${row.status_class}">${row.status}</span></td>
                        </tr>
                    `;
                    modalTableBody.append(tableRow);
                });
            })
            .catch(error => {
                console.error('Error fetching report data:', error);
            });
    });
});
</script>
@endsection
