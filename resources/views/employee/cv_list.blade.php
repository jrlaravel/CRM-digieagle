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
        .active-card {
        background-color: #f1f1f1;
        border: 2px solid #007bff; 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
    }

    .remove-card {
    background-color: rgb(255, 255, 255) !important;
    padding: 5px;
    border-radius: 20%;
    opacity: 1;
    filter: invert(1); /* Makes the close icon visible */
}


    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="row">
        @foreach($count as $statusData)
            <div class="col-lg-2 col-md-2 col-sm-4 col-6 mb-3" style="cursor: pointer;">
                <!-- Make the entire card clickable -->
                <div class="card status-filter text-center" data-status="{{ $statusData->status }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $statusData->status }}</h5>
                        <h1 class="mt-1 mb-3">{{ $statusData->total }}</h1>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="container-fluid p-0">
        <div class="mb-3">
            <h1 class="h3 d-inline align-middle">CV List</h1> 
            <!-- Add Designation Filter Dropdown -->
            <div class="d-flex justify-content-between float-end" >
                <select class="form-control w-auto" style="margin-right: 1cm" id="designation" name="designation">
                    <option value="" selected> Select Designation <i class="fa fa-arrow-down" aria-hidden="true"></i> </option>
                    <option value="Front End Developer">Front Developer</option>
                    <option value="UI/UX Designer">UI/UX Designer</option>
                    <option value="Laravel Developer">Laravel Developer</option>
                    <option value="Graphic Designer">Graphic Designer</option>
                    <option value="Motion Designer">Motion Designer</option>
                </select>
        
                <!-- Add CV Button -->
                <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#defaultModalSuccess">
                    Add CV
                </button>
            </div>
        </div>
        
        <div class="row d-flex justify-content-start align-items-center mb-3">
            @foreach($cvs as $data)
            @if($data->status != "Rejected")
            <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4 cv-card" data-status="{{ $data->status }}">
                <div class="card cv-details h-100 position-relative">
                    <!-- Close Icon -->
                    <button class="btn-close remove-card position-absolute top-0 end-0" aria-label="Close"
                    data-bs-toggle="modal" data-bs-target="#confirmRejectModal"
                    data-id="{{ $data->id }}" data-name="{{ $data->name }}"></button>
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
            
                            <div class="card-body" style="background-color: #fff">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title mb-1">{{ $data->name }}</h5>
                                        <p class="card-text mb-1">Applied for: {{ $data->designation }}</p>
                                        <span class="designation" style="display: none;">{{ $data->designation }}</span> <!-- Hidden designation for filtering -->
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
                                        <a href="{{ $cvPath }}" download target="_blank" class="btn btn-sm btn-outline-success me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Download CV">
                                            <i class="fa fa-download"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-id="{{$data->id}}" data-bs-target="#addFollowUpModal"  title="Add Followup">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>                                
                            <div class="card-body" style="background-color: #fff; padding-bottom: 35px">
                                <a href="javascript:void(0);" class="card-link float-start view-followup-btn" data-id="{{ $data->id }}" data-name="{{ $data->name }}" style="color: black">
                                    View Follow Up <i class="fa fa-arrow-right"></i>
                                </a>                                    
                                <a href="javascript:void(0);" class="card-link float-end" data-id="{{ $data->id }}" id="interviewScheduleBtn{{ $data->id }}">+ Interview Schedule</a>
                            </div>
                        </div>
                    </div>
                </div>        
            </div>
            @endif
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

    <!-- Add Followup Modal -->
    <div class="modal fade" id="addFollowUpModal" tabindex="-1" aria-labelledby="addFollowUpModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFollowUpModalLabel">Add Follow Up</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="followUpForm">
                        @csrf
                        <input type="hidden" id="candidate_id" name="candidate_id">
                        <div class="mb-3">
                            <label for="followUpNotes" class="form-label">Notes</label>
                            <textarea class="form-control" id="followUpNotes" rows="3" placeholder="Add notes..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save Follow Up</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- success modal --}}
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Follow-up has been added successfully!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Follow-ups Modal -->
    <div class="modal fade" id="viewFollowUpModal" tabindex="-1" aria-labelledby="viewFollowUpModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="header"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Follow-up Timeline -->
                    <ul class="timeline mt-2 mb-0" id="followUpList">
                        <li class="text-muted">No follow-ups available.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- reject CV confirm --}}
    <div class="modal fade" id="confirmRejectModal" tabindex="-1" aria-labelledby="confirmRejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmRejectModalLabel">Confirm Rejection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to reject <strong id="cvName"></strong>'s CV?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmRejectBtn">Reject CV</button>
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

    // JavaScript to handle the designation filter
    document.getElementById('designation').addEventListener('change', function() {
        const selectedDesignation = this.value;
        
        console.log(selectedDesignation);
        // Filter the CVs based on the selected designation
        const allCards = document.querySelectorAll('.cv-details'); // Assuming each CV is inside a .card element
        console.log(allCards);
        allCards.forEach(card => {
            const cardDesignation = card.querySelector('.designation'); // Assuming each CV card has the .designation class
            console.log(cardDesignation);
            if (selectedDesignation === "" || cardDesignation.textContent === selectedDesignation) {
                card.style.display = 'block'; // Show the card
            } else {
                card.style.display = 'none'; // Hide the card
            }
        });
    });

    // add follow up
    document.addEventListener("DOMContentLoaded", function () {
            // Get all "Add Follow Up" buttons
                document.querySelectorAll('.btn-outline-info').forEach(button => {
                    button.addEventListener("click", function () {
                    let candidateId = this.getAttribute("data-id"); // Get candidate_id from button
                    document.getElementById("candidate_id").value = candidateId; // Set in hidden input
                });
            });

            // Handle form submission
            document.getElementById("followUpForm").addEventListener("submit", function (event) {
                event.preventDefault(); // Prevent page reload
                
                // Get form data
                let candidateId = document.getElementById("candidate_id").value;
                let notes = document.getElementById("followUpNotes").value;

                // Send data via AJAX to Laravel backend
                fetch("{{route('emp/add-candidate-followup')}}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ candidate_id: candidateId, notes: notes})
                })
                .then(response => response.json())
                .then(data => {
                    // Close add follow-up modal
                    let addFollowUpModal = bootstrap.Modal.getInstance(document.getElementById('addFollowUpModal'));
                    addFollowUpModal.hide();

                    // Reset form
                    document.getElementById("followUpForm").reset();

                    // Open success modal
                    let successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();

                    // Wait for the success modal to be fully shown before reloading
                    successModal._element.addEventListener('hidden.bs.modal', function () {
                        location.reload(); // Refresh the page after success modal is closed
                    });
                })

                .catch(error => console.error("Error:", error));
            });
    });

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.view-followup-btn').forEach(button => {
            button.addEventListener("click", function () {
                let candidateId = this.getAttribute("data-id");
                let name = this.getAttribute("data-name");

                document.getElementById("header").textContent = `Follow-ups for ${name}`;
                // Get all follow-ups from Laravel
                let followups = @json($followup); // Convert Laravel data to JavaScript

                // Filter follow-ups for the clicked candidate
                let filteredFollowups = followups
                    .filter(f => f.candidate_id == candidateId)
                    .sort((a, b) => new Date(b.created_at) - new Date(a.created_at)); // Sort in DESC order

                let followUpList = document.getElementById("followUpList");

                followUpList.innerHTML = ""; // Clear previous entries

                if (filteredFollowups.length === 0) {
                    followUpList.innerHTML = "<li class='text-muted'>No follow-ups available.</li>";
                } else {

                    filteredFollowups.forEach(followup => {
                        let formattedDate = new Date(followup.created_at).toLocaleString('en-US', {
                            year: 'numeric', month: 'short', day: 'numeric',
                            hour: '2-digit', minute: '2-digit', hour12: true
                        });

                        let followUpItem = `
                            <li class="timeline-item">
                                <strong>Follow_up</strong>
                                <span class="float-end text-muted text-sm">${formattedDate}</span>
                                <p>${followup.follow_up}</p>
                            </li>`;
                        followUpList.innerHTML += followUpItem;
                    });
                }

                // Show the modal
                let viewFollowUpModal = new bootstrap.Modal(document.getElementById('viewFollowUpModal'));
                viewFollowUpModal.show();
            });
        });
    });

    $(document).ready(function() {
    let cvId;

    // Open modal with data
    $('.remove-card').click(function() {
        cvId = $(this).data('id');
        let cvName = $(this).data('name');
        $('#cvName').text(cvName);
    });

    // Handle CV rejection
    $('#confirmRejectBtn').click(function() {
        $.ajax({
            url: "{{ route('emp/reject-cv') }}", // Adjust the endpoint
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: cvId,
            },
            success: function(response) {
                if (response.success) {
                    $('#confirmRejectModal').modal('hide');
                    $('[data-id="' + cvId + '"]').closest('.cv-card').remove(); // Remove from UI
                } else {
                    alert('Error rejecting CV.');
                }
            },
            error: function() {
                alert('Server error.');
            }
        });
    });
});

</script>
@endsection