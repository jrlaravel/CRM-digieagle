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
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Website CV List</h1> 
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
                                    <th>Applied Position</th>
                                    <th>Date</th> 
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $candidate)
                                @if(collect($candidate['fields'])->firstWhere('label', 'Position')['value'] != null)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            {{ collect($candidate['fields'])->firstWhere('label', 'Name')['value'] ?? 'N/A' }}
                                        </td>
                                        <td>
                                            {{ collect($candidate['fields'])->firstWhere('label', 'Position')['value'] ?? 'N/A' }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($candidate['submission_date'])->format('d-m-Y H-m-s') }}</td>
                                        <td>
                                            @php
                                                $cvUrl = collect($candidate['fields'])->firstWhere('label', 'Upload Your CV (pdf, jpeg, doc upto 2 MB)')['value'] ?? null;
                                            @endphp
                                            
                                            @if($cvUrl)
                                                <a href="{{ $cvUrl }}" download target="_blank" class="btn btn-primary">Get CV</a>
                                            @else
                                                <span class="text-muted">No CV Available</span>
                                            @endif
                                            <button class="btn btn-info approve-btn"
                                            data-name="{{ collect($candidate['fields'])->firstWhere('label', 'Name')['value'] ?? '' }}"
                                            data-phone="{{ collect($candidate['fields'])->firstWhere('label', 'Phone')['value'] ?? '' }}"
                                            data-designation="{{ collect($candidate['fields'])->firstWhere('label', 'Position')['value'] ?? '' }}"
                                            data-notice-period="{{ collect($candidate['fields'])->firstWhere('label', 'Notice Period')['value'] ?? '' }}"
                                            data-experience="{{ collect($candidate['fields'])->firstWhere('label', 'Experience')['value'] ?? '' }}"
                                            data-current-ctc="{{ collect($candidate['fields'])->firstWhere('label', 'Current CTC')['value'] ?? '' }}"
                                            data-expected-ctc="{{ collect($candidate['fields'])->firstWhere('label', 'Expected CTC')['value'] ?? '' }}"
                                            data-cv-url="{{ $cvUrl }}">
                                                Approve
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                                @endforeach
                            </tbody>                        
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>

<!-- Modal -->
<div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="responseModalLabel">CV Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="modalMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <!-- Optionally add a confirm button here if you want to do something else -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
        // Datatables Responsive
        $("#datatables-reponsive").DataTable({
            searching: true,
            responsive: true
        });
    });

    
    $(document).ready(function() {
        $(".btn-danger").click(function() {
            $(this).closest("tr").fadeOut(300); // Hide the row smoothly
        });
    });

    $(document).on('click', '.approve-btn', function() {
        let button = $(this);
        let candidateData = {
            name: button.data('name'),
            phone: button.data('phone'),
            designation: button.data('designation'),
            notice_period: button.data('notice-period'),
            experience: button.data('experience'),
            current_ctc: button.data('current-ctc'),
            expected_ctc: button.data('expected-ctc'),
            cv_url: button.data('cv-url'),
            source: 'website',
            _token: "{{ csrf_token() }}"  // CSRF Token for security
        };

        console.log(candidateData);
    
        $.ajax({
            url: "{{ route('emp/add-cv') }}",  // Ensure this route exists
            type: "POST",
            data: candidateData,
            success: function(response) {
                // Check the response status
                if (response.status === 'success') {
                    // Set success message
                    $('#modalMessage').text(response.message);
                    window.location.href = response.url;
                } else {
                    // Set error message
                    $('#modalMessage').text(response.message);
                }

                // Show the modal
                $('#responseModal').modal('show');
            },
            error: function(xhr) {
                // Handle validation errors (and other errors if needed)
                var errorMessage = "Something went wrong. Please try again.";

                // Check if there are validation errors
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Get the first error message from the response
                    errorMessage = Object.values(xhr.responseJSON.errors)[0][0]; // Get the first error message (e.g., from 'name')
                }

                // Set the error message in the modal
                $('#modalMessage').text(errorMessage);

                // Show the modal
                $('#responseModal').modal('show');
            }
        });
    });
</script>

@endsection