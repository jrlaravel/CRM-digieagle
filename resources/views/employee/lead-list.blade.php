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
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid p-0">
    <div class="mb-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <h1 class="h3 d-inline align-middle mb-2 mb-md-0">Lead List</h1>
        <div class="d-flex flex-column flex-md-row align-items-md-center">
            <button id="download-excel" class="btn btn-success mb-2 mb-md-0 me-md-2">Download Excel</button>
            <input type="file" id="excel-file" name="excel_file" class="form-control d-none" accept=".xlsx, .xls">
            <button id="upload-excel" class="btn btn-success mb-2 mb-md-0 me-md-2">Upload Excel</button>
            {{-- <input type="text" id="searchInput" placeholder="Search" class="form-control me-md-2 mb-2 mb-md-0 w-100 w-md-auto" onkeyup="filterTable()"> --}}
            <select id="status-filter" class="form-select w-100 w-md-auto">
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

    <div class="row">
        <div class="col-12">    
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Company name</th>
                                    <th>Description</th>
                                    <th>Source</th>
                                    <th>Email</th>
                                    <th>Phone No.</th>
                                    <th>Whatsapp No.</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Address</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>                                
                                @foreach ($data as $key => $lead)
                                <tr data-status="{{ $lead->status }}">
                                    <td>{{ ++$key }}</td>
                                    <td><a href="{{route('emp/lead-datail',$lead->id)}}">{{ $lead->first_name }}</a></td>
                                    <td>{{ $lead->company_name }}</td>
                                    <td>{{ $lead->description }}</td>
                                    <td>{{ $lead->lead_source}}</td>
                                    <td>{{ $lead->email }}</td>
                                    <td><a target="_blank" href="tel:{{$lead->phone}}">{{ $lead->phone }}</a></td>
                                    <td><a target="_blank" href="https://wa.me/{{$lead->whatsappno}}">{{ $lead->whatsappno }}</a></td>
                                    <td>{{ $lead->city }}</td>
                                    <td>{{ $lead->state }}</td>
                                    <td>{{ $lead->address }}</td>
                                    <td>{{ \Carbon\Carbon::parse($lead->created_at)->format('d-m-Y H:i') }}</td>
                                    <td>
                                        @if($lead->status == 'Prospect')
                                            <span class="badge bg-warning">Prospect</span>
                                        @elseif($lead->status == 'Lead')
                                            <span class="badge bg-info">Lead</span>
                                        @elseif($lead->status == 'hot lead')
                                            <span class="badge bg-primary">Hot Lead</span>
                                            @elseif($lead->status == 'Client')
                                            <span class="badge bg-success">Client</span>
                                            @elseif($lead->status == 'No Response')
                                            <span class="badge bg-secondary">No Response</span>
                                            @else
                                            <span class="badge bg-danger">Not interested</span>

                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-primary edit-lead" 
                                                data-id="{{ $lead->id }}"
                                                data-first_name="{{ $lead->first_name }}"
                                                data-company_name="{{ $lead->company_name }}"
                                                data-lead_source = "{{ $lead->lead_source }}"
                                                data-description="{{ $lead->description }}"
                                                data-email="{{ $lead->email }}"
                                                data-phone="{{ $lead->phone }}"
                                                data-whatsappno="{{ $lead->whatsappno }}"
                                                data-city="{{ $lead->city }}"
                                                data-state="{{ $lead->state }}"
                                                data-address="{{ $lead->address }}"
                                                data-status="{{ $lead->status }}">
                                            Edit
                                        </button>
                                       <!-- Delete Button -->
                                        <a href="javascript:void(0);" 
                                        class="btn btn-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteConfirmModal" 
                                        onclick="setDeleteUrl('{{ route('emp/lead-delete', $lead->id) }}')">Delete</a>

                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this lead?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <a id="confirmDeleteBtn" href="#" class="btn btn-danger">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                        </div>

                                        <!-- JavaScript -->
                                        <script>
                                        function setDeleteUrl(url) {
                                            document.getElementById('confirmDeleteBtn').setAttribute('href', url);
                                        }
                                        </script>

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


<div class="modal fade" id="editLeadModal" tabindex="-1" aria-labelledby="editLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLeadModalLabel">Edit Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editLeadForm" action="{{route('emp/lead-update')}}" method="post">
                    @csrf
                    <input type="hidden" name="id" id="lead-id">

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="first_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="company_name" name="company_name">
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="description" name="description">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="description" class="form-label">Lead Source</label>
                            <input type="text" class="form-control" id="lead_source" name="lead_source">
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
        
                        <div class="mb-3 col-md-4">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="phone" class="form-label">Whatsapp Number</label>
                            <input type="text" class="form-control" id="whatsappno" name="whatsappno">
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="email" class="form-label">Instagram</label>
                            <input type="text" class="form-control" id="instagram" name="instagram">
                        </div>
        
                        <div class="mb-3 col-md-4">
                            <label for="phone" class="form-label">Facebook</label>
                            <input type="text" class="form-control" id="facebook" name="facebook">
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="phone" class="form-label">Website</label>
                            <input type="text" class="form-control" id="website" name="website">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city">
                        </div>
        
                        <div class="mb-3 col-md-6">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" required class="form-select">
                            <option value="">&#11044; All Status</option>
                            <option value="No Response" class="text-secondary">&#11044; No Response</option>
                            <option value="Not interested" class="text-danger"> &#11044; Not interested</option>
                            <option value="Prospect" class="text-warning"> &#11044; Prospect</option>
                            <option value="Lead" class="text-info"> &#11044; Lead</option>
                            <option value="Hot Lead" class="text-primary"> &#11044; Hot Lead</option>
                            <option value="Client" class="text-success"> &#11044; Client</option>
                        </select>   
                    </div>

                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="successModalLabel">Success</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Your file has been successfully uploaded!
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.2/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/exceljs@latest/dist/exceljs.min.js"></script>


<!-- jQuery Script to handle modal and populate values -->
<script>
    $(document).ready(function () {
        $('#datatables-reponsive').DataTable({
            responsive: true,
            pageLength: 5, // Number of rows per page
        });
    });

    $(document).on('click', '.edit-lead', function() {
        let leadId = $(this).data('id');
        let firstName = $(this).data('first_name');
        let companyName = $(this).data('company_name');
        let lead_source = $(this).data('lead_source');
        let description = $(this).data('description');
        let email = $(this).data('email');
        let phone = $(this).data('phone');
        let whatsappno = $(this).data('whatsappno'); // Whatsapp number
        let city = $(this).data('city');
        let state = $(this).data('state');
        let address = $(this).data('address');
        let status = $(this).data('status');
        let insLink = $(this).data('inslink'); // Instagram link
        let facebookLink = $(this).data('facebooklink'); // Facebook link
        let webLink = $(this).data('weblink'); // Website link
    
            console.log(lead_source);

        // Set modal input values
        $('#lead-id').val(leadId);
        $('#first_name').val(firstName);
        $('#company_name').val(companyName);
        $('#lead_source').val(lead_source); 
        $('#description').val(description);
        $('#email').val(email);
        $('#phone').val(phone);
        $('#whatsappno').val(whatsappno); // Set Whatsapp number
        $('#city').val(city);
        $('#state').val(state);
        $('#address').val(address);
        $('#status').val(status);
        $('#instagram').val(insLink); // Set Instagram link
        $('#facebook').val(facebookLink); // Set Facebook link
        $('#website').val(webLink); // Set Website link
    
        // Show the modal
        $('#editLeadModal').modal('show');
    });

    $('#download-excel').on('click', function() {
        // Trigger the download by redirecting to the route
        window.location.href = '{{ route('emp/downloadexcel') }}';
    });

    document.getElementById('status-filter').addEventListener('change', function() {
        var selectedStatus = this.value.toLowerCase();
        var rows = document.querySelectorAll('#datatables-reponsive tbody tr');

        rows.forEach(function(row) {
            var rowStatus = row.getAttribute('data-status').toLowerCase();

            // Show "Not interested" leads only if selected
            if (selectedStatus === "" || rowStatus === selectedStatus) {
                row.style.display = "";
            } else {
                row.style.display = "none";      
            }
        });
    });

    // Hide "Not interested" rows on page load
    window.addEventListener('DOMContentLoaded', function() {
        var rows = document.querySelectorAll('#datatables-reponsive tbody tr');
        rows.forEach(function(row) {
            if (row.getAttribute('data-status').toLowerCase() === 'not interested') {
                row.style.display = 'none'; // Hide by default
            }
        });
    });

    $(document).ready(function() {  

var csrfToken = $('meta[name="csrf-token"]').attr('content');

// Handle the button click to trigger file input
$('#upload-excel').on('click', function() {
    $('#excel-file').trigger('click'); // Use trigger to open the file dialog
});

// Handle the file selection
$('#excel-file').on('change', function() {
    if (this.files && this.files[0]) {
        var fileData = new FormData();
        var file = this.files[0];
        fileData.append('excel_file', file);

        $.ajax({
            url: '{{ route('emp/uploadexcel') }}', 
            type: 'POST',
            data: fileData,
            headers: {
                'X-CSRF-TOKEN': csrfToken  
            },  
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
              
                $('#successModal').modal('show');
                
                setTimeout(function() {
                    location.reload();
                }, 2000);
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessage = '<div class="alert alert-danger">';
                for (var key in errors) {
                    errorMessage += errors[key][0] + '<br>';
                }
                errorMessage += '</div>';
                $('#message').html(errorMessage);
            }
        });
    }
});

$('#successModal').on('hidden.bs.modal', function () {
    location.reload(); 
});
});

function setDeleteUrl(url) {
    document.getElementById('confirmDeleteBtn').setAttribute('href', url);
}
</script>

@endsection