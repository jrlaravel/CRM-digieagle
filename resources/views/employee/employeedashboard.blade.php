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
   .color-checkbox {
    display: inline-block;
    position: relative;
    margin-right: 15px;
}

.color-checkbox input[type="checkbox"] {
    display: none; /* Hide the default checkbox */
}

.color-checkbox label {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    cursor: pointer;
    transition: background-color 0.3s ease, border-color 0.3s ease;
    border: 1px solid #000000; /* Default border color */
}

/* Custom checkbox styling */
.color-checkbox label::before {
    content: '';
    display: inline-block;
    width: 15px;
    height: 15px;
    border-radius: 4px;
    border: 1px solid #000000; /* Default border color */
    margin-right: 10px;
    vertical-align: middle;
    background-color: white;
    transition: background-color 0.3s ease, border-color 0.3s ease;
}

/* Red checkbox */
#red-checkbox + label {
    border-color: red; /* Default border color for red */
    
}
#red-checkbox {
    border-color: red;
    background-color: red;
}
#red-checkbox:checked + label::before {
    background-color: red;
    border-color: red;
}

/* Green checkbox */
#green-checkbox + label {
    border-color: green; /* Default border color for green */
}
#green-checkbox:checked + label {
    border-color: green;
}
#green-checkbox:checked + label::before {
    background-color: green;
    border-color: green;
}

/* Black checkbox */
#black-checkbox + label {
    border-color: black; /* Default border color for black */
}
#black-checkbox:checked + label {
    border-color: black;
}
#black-checkbox:checked + label::before {
    background-color: black;
    border-color: black;
}

/* Yellow checkbox */
#yellow-checkbox + label {
    border-color: #ffcc00; /* Default border color for yellow */
}
#yellow-checkbox:checked + label {
    border-color: #ffcc00;
}
#ffcc00-checkbox:checked + label::before {
    background-color: #ffcc00;
    border-color: #ffcc00;
}

/* Golden checkbox */
#golden-checkbox + label {
    border-color: goldenrod; /* Default border color for golden */
}
#golden-checkbox:checked + label {
    border-color: goldenrod;
}
#golden-checkbox:checked + label::before {
    background-color: goldenrod;
    border-color: goldenrod;
}


</style>
<div class="row">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title">Pending Task</h5>
                    </div>
                    {{-- <div class="col-auto">
                        <div class="stat text-primary">
                            <i class="align-middle" data-feather="dollar-sign"></i>
                        </div>
                    </div> --}}
                </div>
                <h1 class="mt-1 mb-3">2</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title">Complete Tasks</h5>
                    </div>
                </div>
                <h1 class="mt-1 mb-3">4</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title">Present Days (Month)</h5>
                    </div>
                </div>
                <h1 class="mt-1 mb-3">{{$presentDaysCount}}</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title">Remaining Days (Month)</h5>
                    </div>
                </div>
                <h1 class="mt-1 mb-3">{{$absentDaysCount}}</h1>
            </div>
        </div>
    </div>
</div>

{{-- bde features --}}
@if(session('has_bde_features'))
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Call Reminder List</h5>
            <table id="employee-table" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Company Name</th>
                        <th>Status</th>
                        <th>Phone No.</th>
                        <th>Call Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($follow_ups as $item)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $item->first_name . ' ' . $item->last_name }}</td>
                        <td>{{ $item->company_name }}</td>
                        <td>{{ $item->status }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->call_date)->format('d-m-Y') }}</td>
                        <td>
                            <button type="button" class="btn btn-primary edit-followup" data-lead-id="{{ $item->lead_id }}" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Update
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin/add-followup') }}" method="post">
                    @csrf

                    <input type="hidden" id="lead_Id" name="lead_id">
                
                    <input type="text" class="form-control" value="call reminder update" hidden name="title" id="inputTitle">
                    
                    <input type="date" name="date" id="dateInput" class="form-control" hidden value="<?php echo date('Y-m-d'); ?>">
                
                    <div class="mb-3">
                        <label class="form-label" for="message">Update Message</label>
                        <input type="text" name="message" class="form-control"  id="message">
                    </div>

                    
                    <div class="mb-3">
                        <label for="status">Status</label>
                        <div class="select-container">
                            <select id="status-filter" name="status" class="form-select">
                                <option value="">&#11044; All Status</option>
                                <option value="No Response" class="text-secondary">&#11044; No Response</option>
                                <option value="Not interested" class="text-danger"> &#11044; Not interested</option>
                                <option value="Prospect" class="text-warning"> &#11044; Prospect</option>
                                <option value="lead" class="text-info"> &#11044; Lead</option>
                                <option value="hot lead" class="text-primary"> &#11044; Hot Lead</option>
                                <option value="client" class="text-success"> &#11044; Client</option>
                            </select>   
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#exampleModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var leadId = button.data('lead-id'); // Extract lead_id from data-* attributes  
                // Update the modal's content
                var modal = $(this);
                modal.find('#lead_Id').val(leadId); // Set the lead_id
            });
        });

    </script>
@endif

{{-- HR feature --}}
@if(session('has_hr_features'))
    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Interview Reminder List</h5>
                <a  href="{{route('emp/candidate-cv-list')}}" class="btn btn-primary ms-auto">Schedule Interview</a>
            </div>        
            <table id="employee-table" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Interview type</th>
                        <th>Date and time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($interviewdata as $item)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->interview_type }}</td>
                        <td>{{ $item->interview_date }} {{ $item->interview_time }}</td>
                        <td>             
                            <a href="javascript:void(0)" class="btn btn-primary edit-interview-btn"
                                data-id="{{ $item->id }}"
                                data-type="{{ $item->interview_type }}"
                                data-date="{{ $item->interview_date }}"
                                data-time="{{ $item->interview_time }}"
                                data-bs-toggle="modal"
                                data-bs-target="#editInterviewModal">
                                Edit
                            </a>
                        
                            <a href="#" class="addfollow btn btn-info" data-id="{{$item->candidate_id}}" data-interview_id="{{$item->id}}" data-bs-toggle="modal" data-bs-target="#addFollowUpModal">
                                Add Follow Up
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Follow Up Modal -->
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
                        <input type="hidden" name="interview_id" id="interview_id">
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
        
    <!-- Edit Interview Modal -->
    <div class="modal fade" id="editInterviewModal" tabindex="-1" aria-labelledby="editInterviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editInterviewModalLabel">Edit Interview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editInterviewForm">
                        @csrf
                        <input type="hidden" id="edit_interview_id" name="interview_id">

                        <div class="mb-3">
                            <label for="edit_interview_type" class="form-label">Interview Type</label>
                            <select id="edit_interview_type" name="interview_type" class="form-select" required>
                                <option value="">Select Interview Type</option>
                                <option value="HR">HR</option>
                                <option value="Mock round">Mock round</option>
                                <option value="Technical">Technical</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_interview_date" class="form-label">Interview Date</label>
                            <input type="date" class="form-control" id="edit_interview_date" name="interview_date" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_interview_time" class="form-label">Interview Time</label>
                            <input type="time" class="form-control" id="edit_interview_time" name="interview_time" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-20">Update Interview</button>
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

    <script>
        // Wait for the document to fully load
        document.addEventListener("DOMContentLoaded", function () {
            // Add event listeners for the edit buttons dynamically
            document.querySelectorAll(".edit-interview-btn").forEach(button => {
                button.addEventListener("click", function () {
                    // Get data attributes from the clicked button
                    let interviewId = this.getAttribute("data-id");
                    let interviewType = this.getAttribute("data-type");
                    let interviewDate = this.getAttribute("data-date");
                    let interviewTime = this.getAttribute("data-time");

                    // Fill the modal form fields with the retrieved data
                    document.getElementById("edit_interview_id").value = interviewId;
                    document.getElementById("edit_interview_type").value = interviewType;
                    document.getElementById("edit_interview_date").value = interviewDate;
                    document.getElementById("edit_interview_time").value = interviewTime;

                    // Show the modal using Bootstrap's modal instance
                    let editModal = new bootstrap.Modal(document.getElementById('editInterviewModal'));
                    editModal.show();
                });
            });

            // Handle form submission via AJAX
            document.getElementById("editInterviewForm").addEventListener("submit", function (event) {
                event.preventDefault();  // Prevent page reload

                // Get form data
                let interviewId = document.getElementById("edit_interview_id").value;
                let interviewType = document.getElementById("edit_interview_type").value;
                let interviewDate = document.getElementById("edit_interview_date").value;
                let interviewTime = document.getElementById("edit_interview_time").value;

                console.log("Updating Interview:", { interviewId, interviewType, interviewDate, interviewTime });

                // Send data to Laravel backend via AJAX
                $.ajax({
                    url: "{{ route('emp/edit-interview-schedule') }}",  // Laravel route
                    method: "POST",  // HTTP method
                    data: {
                        _token: "{{ csrf_token() }}",  // CSRF token for security
                        id: interviewId,
                        interview_type: interviewType,
                        interview_date: interviewDate,
                        interview_time: interviewTime
                    },
                    success: function (data) {
                        console.log("Response:", data);

                        // Close modal after success (using Bootstrap's modal instance)
                        let editModal = new bootstrap.Modal(document.getElementById('editInterviewModal'));
                        editModal.hide();

                        // Reload page or update row in table dynamically
                        location.reload();
                    },
                    error: function (error) {
                        console.error("Error:", error);
                    }
                });
            });
        });

        // add follow up
        document.addEventListener("DOMContentLoaded", function () {
            // Get all "Add Follow Up" buttons
                document.querySelectorAll('.btn-info').forEach(button => {
                    button.addEventListener("click", function () {
                    let candidateId = this.getAttribute("data-id"); // Get candidate_id from button
                    let interviewId = this.getAttribute("data-interview_id"); // Get interview_id from button
                    document.getElementById("candidate_id").value = candidateId; // Set in hidden input
                    document.getElementById("interview_id").value = interviewId; // Set in hidden input
                });
            });

            // Handle form submission
            document.getElementById("followUpForm").addEventListener("submit", function (event) {
                event.preventDefault(); // Prevent page reload
                
                // Get form data
                let candidateId = document.getElementById("candidate_id").value;
                let interviewId = document.getElementById("interview_id").value;   
                let notes = document.getElementById("followUpNotes").value;

                // Send data via AJAX to Laravel backend
                fetch("{{route('emp/add-candidate-followup')}}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ candidate_id: candidateId, notes: notes, interview_id: interviewId})
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
    </script>
@endif

{{-- cards section --}}
<div class="row mb-2 mb-xl-3"> 
    <div class="col-auto d-none d-sm-block">
        <h3>Cards</h3>
       <div class="color-checkbox">
            <input type="checkbox" name="red card" id="red-checkbox">
            <label for="red-checkbox" class="red">Red (<span class="count">0</span>)</label>
        </div>
    
        <div class="color-checkbox">
            <input type="checkbox" name="green card" id="green-checkbox">
            <label for="green-checkbox" class="green">Green (<span class="count">0</span>)</label>
        </div>
      
        <div class="color-checkbox">
            <input type="checkbox" name="black card" id="black-checkbox">
            <label for="black-checkbox" class="black">Black (<span class="count">0</span>)</label>
        </div>
    
        <div class="color-checkbox">
            <input type="checkbox" name="yellow card" id="yellow-checkbox">
            <label for="yellow-checkbox" class="yellow">Yellow (<span class="count">0</span>)</label>
        </div>
    
        <div class="color-checkbox">
            <input type="checkbox" name="golden card" id="golden-checkbox">
            <label for="golden-checkbox" class="golden">Golden (<span class="count">0</span>)</label>
        </div> 
      <div id="cards-container" class="row mt-3">
            @foreach($cards as $card)
            <div class="col card-item" data-color="{{ strtolower($card->name) }}" style="display: inline-block;">
                <div class="card" style="width: 16rem;">
                    <img src="{{ asset('storage/cards/' . $card->image) }}" class="card-img-top" alt="{{ $card->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $card->name }}</h5>
                        <p class="card-text">{{ $card->message }}</p>
                        <p class="card-text">{{ \Carbon\Carbon::parse($card->date)->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@section('script')
<!-- Ensure jQuery and Bootstrap JS are loaded before this script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const cards = document.querySelectorAll('.card-item');
    const counts = {}; // Object to store counts for each color

    // Initialize counts object
    checkboxes.forEach(checkbox => {
        counts[checkbox.name] = 0;
    });

    // Function to update counts for each checkbox
    function updateCounts() {
        // Reset counts
        Object.keys(counts).forEach(color => counts[color] = 0);

        // Count cards for each color
        cards.forEach(card => {
            const cardColor = card.getAttribute('data-color').toLowerCase();
            if (counts.hasOwnProperty(cardColor)) {
                counts[cardColor]++;
            }
        });

        // Update counts in labels
        checkboxes.forEach(checkbox => {
            const color = checkbox.name;
            const countSpan = document.querySelector(`label[for="${checkbox.id}"] .count`);
            countSpan.textContent = counts[color] || 0;
        });
    }

    // Filter cards based on checkbox selection
    function filterCards() {
        const checkedColors = Array.from(checkboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.name);

        let visibleCardCount = 0;

        cards.forEach(card => {
            const cardColor = card.getAttribute('data-color').toLowerCase();

            if (checkedColors.length === 0) {
                // Show first 4 cards if no checkboxes are checked
                if (visibleCardCount < 4) {
                    card.style.display = 'inline-block';
                    visibleCardCount++;
                } else {
                    card.style.display = 'none';
                }
            } else if (checkedColors.includes(cardColor)) {
                // Show card if its color is included in checkedColors
                card.style.display = 'inline-block';
                visibleCardCount++;
            } else {
                // Hide card if its color is not included in checkedColors
                card.style.display = 'none';
            }
        });

        // Hide extra cards if more than 4 are visible
        if (visibleCardCount > 4) {
            cards.forEach(card => {
                if (card.style.display === 'inline-block' && visibleCardCount > 4) {
                    visibleCardCount--;
                    if (visibleCardCount <= 4) {
                        card.style.display = 'inline-block';
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
        }
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            filterCards();
            updateCounts();
        });
    });

    // Initial update of counts and cards display
    updateCounts();
    setTimeout(() => {
        filterCards();
    }, 1);
});



</script>
@endsection