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
<style>
    .flip-card {
    perspective: 1000px; /* Adds perspective for the 3D effect */
    }

    .card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        transition: transform 0.6s;
        transform-style: preserve-3d;
    }

    .card-inner.flipped {
        transform: rotateY(180deg); /* Flip the card */
    }

    .card-front,
    .card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
    }

    .card-front {
        background: #fff; /* Front of the card */
    }

    .card-back {
        background: #f8f9fa; 
        transform: rotateY(180deg); 
    }
    .active-card {
    background-color: #f1f1f1;
    border: 2px solid #007bff; 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
}

</style>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="row">
    @foreach($count as $statusData)
        <div class="col-sm-3 col-xl-2" style="cursor: pointer">
            <!-- Make the entire card clickable -->
            <div class="card status-filter" data-status="{{ $statusData->status }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                          <h5 class="card-title">
                              {{ $statusData->status }}
                          </h5>
                         </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{ $statusData->total }}</h1>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">CV List</h1> 
        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#defaultModalSuccess">
            Add CV
        </button>    
    </div>
    <div class="row d-flex justify-content-start align-items-center mb-3">
        @foreach($cvs as $data)
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4 cv-card" data-status="{{ $data->status }}">
            <div class="card h-100 flip-card">
                <div class="card-inner">
                        <!-- Front Side -->
                        <div class="card-front">
                            @if($data->cv_path)
                                @php
                                    $cvPath = filter_var($data->cv_path, FILTER_VALIDATE_URL) ? $data->cv_path : asset('storage/' . $data->cv_path);
                                    $isPdf = Str::endsWith($cvPath, ['.pdf']);
                                @endphp
                                
                                @if($isPdf)
                                    <iframe class="card-img-top" src="{{ $cvPath }}" style="height: 200px; width: 100%;" frameborder="0"></iframe>
                                @else
                                    <img class="card-img-top" src="{{ $cvPath }}" alt="CV Image" style="height: 200px; width: 100%;">
                                @endif
                                @else
                                    <img class="card-img-top" src="path/to/default-image.jpg" alt="No CV available">
                                @endif
        
                            <div class="card-body">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-1">{{ $data->name }}</h5>
                                            <p class="card-text mb-1">Applied for: {{ $data->designation }}</p>
                                            <p class="card-text mb-0">
                                                <strong>Status:</strong>
                                                <span class="badge 
                                                    @if($data->status == 'Selection') bg-primary
                                                    @elseif($data->status == 'Phone Interview') bg-secondary
                                                    @elseif($data->status == 'Technical Interview') bg-info
                                                    @elseif($data->status == 'Practical Interview') bg-warning
                                                    @elseif($data->status == 'Background Verification') bg-dark
                                                    @elseif($data->status == 'Finalisation') bg-success
                                                    @endif">
                                                    {{ $data->status }}
                                                </span>
                                            </p>
                                        </div>
                                
                                        <!-- Action Icons -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ $cvPath }}" target="_blank" class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="View CV">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ $cvPath }}" download target="_blank" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Download CV">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>                                
                                <!-- Align icons to the right -->
                                <div class="card-body">
                                    <a href="javascript:void(0);" class="card-link float-start" data-id="{{ $data->id }}" id="interviewScheduleBtn{{ $data->id }}">+ Interview Schedule</a>
                                    <a href="javascript:void(0);" class="card-link flip-card-btn float-end" data-id="{{ $data->id }}" style="color: black">View Follow Up <i class="fa fa-arrow-right"></i> </a>
                                </div>
                            </div>  
                        </div>
                        
                        <!-- Back Side (Follow Up Information) -->
                        <div class="card-back">
                            <h5 class="card-title">Follow Up Details</h5>
                            <a href="javascript:void(0);" class="card-link flip-back-card-btn" style="color: black"><i class="fa fa-arrow-left"></i> Back</a>
                            <!-- Display follow-up content dynamically for the specific candidate -->
                            @foreach($followup as $followupdata)
                                @if($followupdata->candidate_id == $data->id)
                                    <p>
                                        <strong>Message:</strong> {{ $followupdata->follow_up }}<br>
                                        <strong>Date:</strong> {{ $followupdata->created_at->format('Y-m-d') }}<br>
                                    </p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>        
        @endforeach
    </div>    
</div>

{{-- Modal --}}
<div class="modal fade" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Candidate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <form id="cvForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter name" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" placeholder="Enter phone no." maxlength="10" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Designation</label>
                            <select name="designation" class="form-control">
                                <option value="">Select Designation</option>
                                <option value="Front Developer">Front Developer</option>
                                <option value="UI/UX Designer">UI/UX Designer</option>
                                <option value="Laravel Developer">Laravel Developer</option>
                                <option value="Graphic Designer">Graphic Designer</option>
                                <option value="Motion Designer">Motion Designer</option>    
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Notice Period</label>
                            <select class="form-control" name="notice_period">
                                <option value="">Select Period</option>
                                <option value="0-1 Month">0-1 Month</option>
                                <option value="1-2 Months">1-2 Months</option>
                                <option value="Above 2 Months">Above 2 Months</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Experience</label>
                            <input type="number" class="form-control" name="experience" placeholder="Enter experience in years" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="" class="form-label">Source</label>
                            <input type="text" class="form-control" name="source" placeholder="Enter source" required>
                        </div>
                    </div>
                    <div class="row">    
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Current CTC</label>
                            <input type="number" class="form-control" name="current_ctc" placeholder="Enter current CTC">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Expected CTC</label>
                            <input type="number" class="form-control" name="expected_ctc" placeholder="Enter expected CTC">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">CV (PDF only)</label>
                        <input type="file" class="form-control" name="cv" required>
                        <small class="form-text text-muted">Please upload your CV file in PDF format.</small>
                    </div>    
                    
                    <!-- Success/Error Messages -->
                    <div id="responseMessage" class="mt-3"></div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="submitBtn">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Interview Schedule -->
<div class="modal fade" id="interviewScheduleModal" tabindex="-1" aria-labelledby="interviewScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="interviewScheduleModalLabel">Interview Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Interview Schedule Form -->
                <form id="interviewScheduleForm">
                    @csrf
                    <input type="hidden" id="candidateId" value="">

                    <div class="mb-3">
                        <label for="interviewType" class="form-label">Interview Type</label>
                        <select id="interviewType" class="form-select" required>
                            <option value="">Select Interview Type</option>
                            <option value="HR">HR</option>
                            <option value="Mock round">Mock round</option>
                            <option value="Technical">Technical</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="interviewDate" class="form-label">Interview Date</label>
                        <input type="date" class="form-control" id="interviewDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="interviewTime" class="form-label">Interview Time</label>
                        <input type="time" class="form-control" id="interviewTime" required>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Schedule Interview</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#cvForm").submit(function(event) {
            event.preventDefault(); // Prevent default form submission
            
            let formData = new FormData(this);
            
            $.ajax({
                url: "{{ route('emp/add-cv') }}", 
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $("#responseMessage").html('<div class="alert alert-success">' + response.message + '</div>');
                    $("#cvForm")[0].reset(); 
                    $("#defaultModalSuccess").modal('hide');
                    location.reload();   
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = '<div class="alert alert-danger"><ul>';
                    $.each(errors, function(key, value) {
                        errorMessage += '<li>' + value + '</li>';
                    });
                    errorMessage += '</ul></div>';
                    $("#responseMessage").html(errorMessage);
                }
            });
        });
    });

    // Open the modal when the Interview Schedule button is clicked
$(document).ready(function() {
    // Open the modal when Interview Schedule button is clicked
    $('a[id^="interviewScheduleBtn"]').on('click', function() {
        // Get the candidate ID from the data-id attribute
        var candidateId = $(this).data('id');
        
        // Set the candidateId input value in the modal
        $('#candidateId').val(candidateId);

        // Show the modal
        $('#interviewScheduleModal').modal('show');
    });

    // Handle form submission (for scheduling the interview)
    $('#interviewScheduleForm').on('submit', function(e) {
            event.preventDefault(); // Prevent the default form submission

            // Get the form data
            var candidateId = $('#candidateId').val();
            var interviewType = $('#interviewType').val();
            var interviewDate = $('#interviewDate').val();
            var interviewTime = $('#interviewTime').val();

            // Send the data via AJAX
            $.ajax({
                url: "{{ route('emp/interview-schedule') }}", // Adjust the route if necessary
                type: "POST",
                data: {
                    candidate_id: candidateId, 
                    interview_type: interviewType,
                    interview_date: interviewDate,
                    interview_time: interviewTime,
                    _token: $('meta[name="csrf-token"]').attr('content')  // CSRF token for security
                },
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);  // You can replace this with a modal message
                    $('#interviewScheduleModal').modal('hide');
                } else {
                    alert(response.message);  // You can replace this with a modal error message
                }
            },
            error: function(xhr) {
                alert('Something went wrong. Please try again.');
            }
        });
    });
});

$(document).ready(function() {
    // Flip the card when "View Follow Up" is clicked
    $('.flip-card-btn').on('click', function() {
        // Find the card that contains the clicked link
        var card = $(this).closest('.card-inner');
        
        // Toggle the flip effect
        card.toggleClass('flipped');
    });
    $('.flip-back-card-btn').on('click', function() {
        // Find the card that contains the clicked link
        var card = $(this).closest('.card-inner');
        
        // Toggle the flip effect
        card.toggleClass('flipped');
    });
});

$(document).ready(function () {
    // Click event for status card (filter)
    $(".status-filter").click(function () {
        var status = $(this).data('status');  // Get the status from the clicked card

        // Remove the active class from all cards and then add it to the clicked one
        $(".status-filter").removeClass("active-card");
        $(this).addClass("active-card");

        // Filter the CV cards based on the clicked status
        $(".cv-card").each(function () {
            var cvStatus = $(this).data('status');  // Get the status of each CV card

            if (cvStatus === status) {
                $(this).show();  // Show matching status cards
            } else {
                $(this).hide();  // Hide others
            }
        });
    });
});


</script>
@endsection