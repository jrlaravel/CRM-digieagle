@extends('layout/admin-sidebar')

@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        @if(session('user') && session('user')->profile_photo_path)
            <img src="{{ asset('storage/profile_photos') . '/' . session('user')->profile_photo_path }}" class="avatar img-fluid rounded" />
        @else
            <img src="{{ asset('storage/profile_photos/default.png') }}" class="avatar img-fluid rounded" />
        @endif	
    </div>
    <div class="flex-grow-1 ps-2">
        <p class="text-white">{{ session('user')->first_name }}</p>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Pending Task</h5>
                <h1 class="mt-1 mb-3">2</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Complete Tasks</h5>
                <h1 class="mt-1 mb-3">4</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Employee</h5>
                <h1 class="mt-1 mb-3">{{ $totalUsers }}</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Absent Days</h5>
                <h1 class="mt-1 mb-3">4</h1>
            </div>
        </div>
    </div>
</div>

<!-- DataTable -->
<div class="row">
    <!-- First Call Reminder List -->
    <div class="col-md-6 col-12">
        <div class="card mt-4 shadow-lg rounded">
            <div class="card-body">
                <h5 class="card-title text-primary fw-bold">Meeting List</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="">
                            <tr>
                                <th>No.</th>
                                <th>Client Name</th>
                                <th>Date</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($meetings as $item)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $item->first_name . ' ' . $item->last_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->meeting_date)->format('d-m-Y') }}</td>
                                <td>{{ $item->start_time }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Call Reminder List -->
    <div class="col-md-6 col-12">
        <div class="card mt-4 shadow-lg rounded">
            <div class="card-body">
                <h5 class="card-title text-primary fw-bold">Interview Reminder List</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="">
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Interview Type</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($interviewdata as $item)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->interview_type }}</td>
                                <td>{{ $item->interview_date }}</td>
                                <td>{{ $item->interview_time }}</td>
                                <td>
                                    <a href="#" class="btn btn-info" data-id="{{$item->candidate_id}}" data-interview_id="{{$item->id}}" data-bs-toggle="modal" data-bs-target="#addFollowUpModal">
                                        Add Follow Up
                                    </a>
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
    $(document).ready(function() {
        $('#exampleModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var leadId = button.data('lead-id'); // Extract lead_id from data-* attributes  
            // Update the modal's content
            var modal = $(this);
            modal.find('#lead_Id').val(leadId); // Set the lead_id
        });
    });

    // follow up
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


            console.log("Follow Up Data:", { candidateId, notes, interviewId });

            // Send data via AJAX to Laravel backend
            fetch("{{route('admin/add-candidate-followup')}}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ candidate_id: candidateId, notes: notes, interview_id: interviewId})
            })
            .then(response => response.json())
            .then(data => {
            console.log("Response:", data);

            // Close add follow-up modal
            let addFollowUpModal = bootstrap.Modal.getInstance(document.getElementById('addFollowUpModal'));
            addFollowUpModal.hide();

            // Reset form
            document.getElementById("followUpForm").reset();

            // Open success modal
            let successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            location.reload();
            })
            .catch(error => console.error("Error:", error));
        });
    });
</script>


@endsection