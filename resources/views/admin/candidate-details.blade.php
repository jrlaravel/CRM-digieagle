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
       
           <p class="text-white">{{session('user')->first_name}}</p>
        {{-- <div class="sidebar-user-subtitle">Designer</div> --}}
    </div>
</div>
@endsection
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Candidate Details</h1> 
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
                                    <th>Candidate Name</th>
                                    <th>Email</th>  
                                    <th>Phone no.</th>
                                    <th>Position to apply</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $value)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$value['name']}}</td>
                                    <td>{{$value['email']}}</td>
                                    <td>{{$value['phone']}}</td>
                                    <td>{{$value['designation']}}</td>
                                    <td>
                                        <!-- View Button with data for the candidate -->
                                        @if($value['assign_to'] == '0')
                                        <a href="#" class="btn btn-primary assign-btn" data-bs-toggle="modal" data-id="{{ $value['id'] }}" data-bs-target="#assignCandidateModal">
                                            Assign Candidate
                                        </a>                           
                                        @else
                                        <span class="badge bg-warning">Assigned</span>
                                        @endif  
                                        <button type="button" class="btn btn-info view-btn" data-id="{{ $value['id'] }}" data-name="{{ $value['name'] }}" data-email="{{ $value['email'] }}" data-phone="{{ $value['phone'] }}" data-address="{{ $value['address'] }}" data-designation="{{ $value['designation'] }}" data-experience="{{ $value['experience'] }}" data-reference_name="{{ $value['reference_name'] }}" data-reference_phone="{{ $value['reference_phone'] }}" data-organization_name="{{ $value['organization_name'] }}" data-position_name="{{ $value['position_name'] }}" data-notice_period="{{ $value['notice_period'] }}" data-expected_date="{{ $value['expected_date'] }}" data-current_ctc="{{ $value['current_ctc'] }}" data-expected_ctc="{{ $value['expected_ctc'] }}" data-strengths="{{ $value['strengths'] }}" data-weaknesses="{{ $value['weaknesses'] }}" data-career_goal="{{ $value['career_goal'] }}" data-position_responsibilities="{{ $value['position_responsibilities'] }}" data-areas_of_expertise="{{ $value['areas_of_expertise'] }}" data-improve_your_knowledge="{{ $value['improve_your_knowledge'] }}" data-service_are_we_providing="{{ $value['service_are_we_providing'] }}" data-reason_for_leaving="{{ $value['reason_for_leaving'] }}" data-reason_for_applying="{{ $value['reason_for_applying'] }}">
                                            View
                                        </button>

                                        <a href="#" data-id="{{ $value['id'] }}" class="btn btn-danger delete-btn" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">Delete</a>
                                    </td>
                                </tr>
                            
                                <!-- Hidden row for showing candidate details -->
                                <tr class="candidate-details" id="details-{{ $value['id'] }}" style="display: none;">
                                    <td colspan="6">
                                        <div class="card shadow-sm rounded" style="background-color: #f9f9f9;">
                                            <div class="card-body">
                                                <h5 class="card-title text-primary">Candidate Details</h5>
                                                <hr class="my-3">
                                
                                                <!-- Personal Information Section -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p>Name: <span id="detail-name-{{ $value['id'] }}" class="text-dark"></span></p>
                                                        <p>Email: <span id="detail-email-{{ $value['id'] }}" class="text-dark"></span></p>
                                                        <p>Phone: <span id="detail-phone-{{ $value['id'] }}" class="text-dark"></span></p>
                                                        <p>Address: <span id="detail-address-{{ $value['id'] }}" class="text-dark"></span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p>Designation: <span id="detail-designation-{{ $value['id'] }}" class="text-dark"></span></p>
                                                        <p>Experience: <span id="detail-experience-{{ $value['id'] }}" class="text-dark"></span></p>
                                                        <p>Reference Name: <span id="detail-reference_name-{{ $value['id'] }}" class="text-dark"></span></p>
                                                        <p>Reference Phone: <span id="detail-reference_phone-{{ $value['id'] }}" class="text-dark"></span></p>
                                                    </div>
                                                </div>
                                
                                                <hr class="my-3">
                                
                                                <!-- Professional Information Section -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p>Organization Name: <span id="detail-organization_name-{{ $value['id'] }}" class="text-dark"></span></p>
                                                        <p>Position Name: <span id="detail-position_name-{{ $value['id'] }}" class="text-dark"></span></p>
                                                        <p>Notice Period: <span id="detail-notice_period-{{ $value['id'] }}" class="text-dark"></span></p>
                                                        <p>Expected Date: <span id="detail-expected_date-{{ $value['id'] }}" class="text-dark"></span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p>Current CTC (LPA): <span id="detail-current_ctc-{{ $value['id'] }}" class="text-dark"></span></p>
                                                        <p>Expected CTC (LPA): <span id="detail-expected_ctc-{{ $value['id'] }}" class="text-dark"></span></p>
                                                        <p>Strengths: <span id="detail-strengths-{{ $value['id'] }}" class="text-dark"></span></p>
                                                        <p>Weaknesses: <span id="detail-weaknesses-{{ $value['id'] }}" class="text-dark"></span></p>
                                                    </div>
                                                </div>
                                
                                                <hr class="my-3">
                                
                                                <!-- Career and Position Information Section -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p>Career Goal: <span id="detail-career_goal-{{ $value['id'] }}" class="text-dark"></span></p>
                                                        <p>Position Responsibilities: <span id="detail-position_responsibilities-{{ $value['id'] }}" class="text-dark"></span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p>Areas of Expertise: <span id="detail-areas_of_expertise-{{ $value['id'] }}" class="text-dark"></span></p>
                                                        <p>Improve Your Knowledge: <span id="detail-improve_your_knowledge-{{ $value['id'] }}" class="text-dark"></span></p>
                                                    </div>
                                                </div>
                                
                                                <hr class="my-3">
                                
                                                <!-- Service Information Section -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p>Service We Are Providing: <span id="detail-service_are_we_providing-{{ $value['id'] }}" class="text-dark"></span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p>Reason for Leaving: <span id="detail-reason_for_leaving-{{ $value['id'] }}" class="text-dark"></span></p>
                                                        <p>Reason for Applying: <span id="detail-reason_for_applying-{{ $value['id'] }}" class="text-dark"></span></p>
                                                    </div>
                                                </div>
                                
                                            </div>
                                        </div>
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

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this candidate's details? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a id="confirmDeleteBtn" href="#" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<!-- Assign Candidate Modal -->
<div class="modal fade" id="assignCandidateModal" tabindex="-1" aria-labelledby="assignCandidateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignCandidateModalLabel">Assign Candidate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="assignCandidateForm">
                    @csrf

                    <input type="hidden" name="candidate_id" id="candidate_id">
                    
                    <div class="mb-3">
                        <label for="assignTo" class="form-label">Assign To</label>
                        <select name="assign_to" id="assignTo" class="form-select">
                            <option value="">Select User</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->first_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="button" id="assignCandidateBtn" class="btn btn-success">Assign</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>

document.addEventListener("DOMContentLoaded", function() {
        // Datatables Responsive
        $("#datatables-reponsive").DataTable({
            responsive: true
        });
    });
    
    document.querySelectorAll('.view-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            
            // Get all the data attributes
            const name = this.getAttribute('data-name');
            const email = this.getAttribute('data-email');
            const phone = this.getAttribute('data-phone');
            const address = this.getAttribute('data-address');
            const designation = this.getAttribute('data-designation');
            const experience = this.getAttribute('data-experience');
            const referenceName = this.getAttribute('data-reference_name');
            const referencePhone = this.getAttribute('data-reference_phone');
            const organizationName = this.getAttribute('data-organization_name');
            const positionName = this.getAttribute('data-position_name');
            const noticePeriod = this.getAttribute('data-notice_period');
            const expectedDate = this.getAttribute('data-expected_date');
            const currentCTC = this.getAttribute('data-current_ctc');
            const expectedCTC = this.getAttribute('data-expected_ctc');
            const strengths = this.getAttribute('data-strengths');
            const weaknesses = this.getAttribute('data-weaknesses');
            const careerGoal = this.getAttribute('data-career_goal');
            const positionResponsibilities = this.getAttribute('data-position_responsibilities');
            const areasOfExpertise = this.getAttribute('data-areas_of_expertise');
            const improveKnowledge = this.getAttribute('data-improve_your_knowledge');
            const serviceProviding = this.getAttribute('data-service_are_we_providing');
            const reasonForLeaving = this.getAttribute('data-reason_for_leaving');
            const reasonForApplying = this.getAttribute('data-reason_for_applying');
            
            // Fill the details into the corresponding fields
            document.getElementById(`detail-name-${id}`).textContent = name;
            document.getElementById(`detail-email-${id}`).textContent = email;
            document.getElementById(`detail-phone-${id}`).textContent = phone;
            document.getElementById(`detail-address-${id}`).textContent = address;
            document.getElementById(`detail-designation-${id}`).textContent = designation;
            document.getElementById(`detail-experience-${id}`).textContent = experience;
            document.getElementById(`detail-reference_name-${id}`).textContent = referenceName;
            document.getElementById(`detail-reference_phone-${id}`).textContent = referencePhone;
            document.getElementById(`detail-organization_name-${id}`).textContent = organizationName;
            document.getElementById(`detail-position_name-${id}`).textContent = positionName;
            document.getElementById(`detail-notice_period-${id}`).textContent = noticePeriod;
            document.getElementById(`detail-expected_date-${id}`).textContent = expectedDate;
            document.getElementById(`detail-current_ctc-${id}`).textContent = currentCTC;
            document.getElementById(`detail-expected_ctc-${id}`).textContent = expectedCTC;
            document.getElementById(`detail-strengths-${id}`).textContent = strengths;
            document.getElementById(`detail-weaknesses-${id}`).textContent = weaknesses;
            document.getElementById(`detail-career_goal-${id}`).textContent = careerGoal;
            document.getElementById(`detail-position_responsibilities-${id}`).textContent = positionResponsibilities;
            document.getElementById(`detail-areas_of_expertise-${id}`).textContent = areasOfExpertise;
            document.getElementById(`detail-improve_your_knowledge-${id}`).textContent = improveKnowledge;
            document.getElementById(`detail-service_are_we_providing-${id}`).textContent = serviceProviding;
            document.getElementById(`detail-reason_for_leaving-${id}`).textContent = reasonForLeaving;
            document.getElementById(`detail-reason_for_applying-${id}`).textContent = reasonForApplying;
            
            // Toggle visibility of the details row
            const detailsRow = document.getElementById(`details-${id}`);
            detailsRow.style.display = (detailsRow.style.display === 'none' || detailsRow.style.display === '') ? 'table-row' : 'none';
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Listen for clicks on the delete button
        const deleteButtons = document.querySelectorAll('.delete-btn');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent the default navigation
                
                // Get the candidate ID from the data-id attribute
                const candidateId = this.getAttribute('data-id');
                
                // Define the URL for deletion
                const deleteUrl = '{{ route("admin/delete-candidate-details", ":id") }}'.replace(':id', candidateId);
                
                // Update the confirmation button's href with the delete URL
                document.getElementById('confirmDeleteBtn').setAttribute('href', deleteUrl);
            });
        });
    });

    $(document).ready(function () {
    // Open modal and set candidate ID
    $(document).on("click", ".assign-btn", function () {
        let candidateId = $(this).data("id");
        console.log("Candidate ID:", candidateId); // Debugging step
        $("#candidate_id").val(candidateId);
    });

    // AJAX request on button click
    $("#assignCandidateBtn").click(function () {
        let candidateId = $("#candidate_id").val();
        let assignTo = $("#assignTo").val();
        let token = $('meta[name="csrf-token"]').attr('content');

        if (!candidateId) {
            alert("Candidate ID not found!");
            return;
        }

        $.ajax({
            url: "{{ route('admin.assign-candidate-details') }}",
            type: "POST",
            data: {
                _token: token,
                id: candidateId,
                assign_to: assignTo
            },
            success: function (response) {
                // alert("Candidate assigned successfully!");
                location.reload();
            },
            error: function (xhr) {
                alert("Something went wrong!");
            }
        });
    });
});
</script>


@endsection