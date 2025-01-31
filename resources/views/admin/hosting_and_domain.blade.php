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

    </div>
</div>
@endsection

@section('content')
<div class="container-fluid p-0">

    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Client's Hosting and Domain List</h1>   
        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addClientModal">Add</button> 
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
                                    <th>Client Logo</th>
                                    <th>Client Name</th>
                                    <th>Domain Expire Date</th>
                                    <th>Hosting Expire Date</th>
                                    {{-- <th>Domain Name</th>
                                    <th>Domain Purchase From</th>
                                    <th>Domain Purchase Date</th> 
                                    <th>Domain Amount</th>
                                    <th>Hosting Purchase From</th>
                                    <th>Hosting Purchase Date</th>
                                    <th>Hosting Amount</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $value)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if($value->logo)
                                        <img src="{{ asset('storage') . '/' . $value->logo }}" class="img-fluid" style="max-width: 50px; max-height: 50px;">
                                        @endif
                                    </td>
                                    <td>{{ $value->client_name }}</td>
                                    <td class="text-danger">{{ $value->domain_expire_date }}</td>
                                    <td class="text-danger">{{ $value->hosting_expire_date }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info view-btn" data-id="{{ $value->id }}"
                                            data-client_name="{{ $value->client_name }}"
                                            data-domain_name="{{ $value->domain_name }}"
                                            data-domain_purchase_from="{{ $value->domain_purchase_from }}"
                                            data-domain_purchase_date="{{ $value->domain_purchase_date }}"
                                            data-domain_expire_date="{{ $value->domain_expire_date }}"
                                            data-domain_amount="{{ $value->domain_amount }}"
                                            data-domain_email="{{ $value->domain_email }}"
                                            data-domain_id="{{ $value->domain_id }}"
                                            data-domain_password="{{ $value->domain_password }}"
                                            data-hosting_purchase_from="{{ $value->hosting_purchase_from }}"
                                            data-hosting_link="{{ $value->hosting_link }}"
                                            data-hosting_amount="{{ $value->hosting_amount }}"
                                            data-hosting_purchase_date="{{ $value->hosting_purchase_date }}"
                                            data-hosting_expire_date="{{ $value->hosting_expire_date }}"
                                            data-hosting_email="{{ $value->hosting_email }}"
                                            data-hosting_id="{{ $value->hosting_id }}"
                                            data-hosting_password="{{ $value->hosting_password }}">
                                            View
                                        </button>
                                        <button type="button" class="btn btn-primary edit-btn" 
                                                data-id="{{ $value->id }}"
                                                data-client_name="{{ $value->client_name }}"
                                                data-domain_name="{{ $value->domain_name }}"
                                                data-domain_purchase_from="{{ $value->domain_purchase_from }}"
                                                data-domain_purchase_date="{{ $value->domain_purchase_date }}"
                                                data-domain_expire_date="{{ $value->domain_expire_date }}"
                                                data-domain_amount="{{ $value->domain_amount }}"
                                                data-domain_email="{{ $value->domain_email }}"
                                                data-domain_id="{{ $value->domain_id }}"
                                                data-domain_password="{{ $value->domain_password }}"
                                                data-hosting_purchase_from="{{ $value->hosting_purchase_from }}"
                                                data-hosting_link="{{ $value->hosting_link }}"
                                                data-hosting_amount="{{ $value->hosting_amount }}"
                                                data-hosting_purchase_date="{{ $value->hosting_purchase_date }}"
                                                data-hosting_expire_date="{{ $value->hosting_expire_date }}"
                                                data-hosting_email="{{ $value->hosting_email }}"
                                                data-hosting_id="{{ $value->hosting_id }}"
                                                data-hosting_password="{{ $value->hosting_password }}" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal">
                                            Edit
                                        </button>
                                        <a href="#" data-id="{{ $value->id }}" class="btn btn-danger delete-btn" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">Delete</a>
                                    </td>
                                </tr>
                            
                                <!-- Hidden row for showing client details -->
                                <tr class="client-details" id="details-{{ $value->id }}" style="display: none;">
                                    <td colspan="6">
                                        <div class="card shadow-sm rounded" style="background-color: #f9f9f9;">
                                            <div class="card-body">
                                                <h5 class="card-title text-primary">Client Details</h5>
                                                <hr class="my-3">
                            
                                                <!-- Domain Information Section -->
                                                <div class="row">
                                                    <!-- Domain Information Section -->
                                                    <div class="col-md-6">
                                                        <h5>Domain Information</h5>
                                                        <p>Domain Name: <span id="detail-domain_name-{{ $value->id }}" class="text-dark"></span></p>
                                                        <p>Domain Purchased From: <span id="detail-domain_purchase_from-{{ $value->id }}" class="text-dark"></span></p>
                                                        <p>Domain Purchase Date: <span id="detail-domain_purchase_date-{{ $value->id }}" class="text-dark"></span></p>
                                                        <p>Domain Expiry Date: <span id="detail-domain_expire_date-{{ $value->id }}" class="text-dark"></span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h5>Domain Credentials</h5>
                                                        <p>Domain Amount: <span id="detail-domain_amount-{{ $value->id }}" class="text-dark"></span></p>
                                                        <p>Domain Email: <span id="detail-domain_email-{{ $value->id }}" class="text-dark"></span></p>
                                                        <p>Domain ID: <span id="detail-domain_id-{{ $value->id }}" class="text-dark"></span></p>
                                                        <p>Domain Password: <span id="detail-domain_password-{{ $value->id }}" class="text-dark"></span></p>
                                                    </div>
                                                </div>
                                                
                                                <hr class="my-3">
                                                
                                                <!-- Hosting Information Section -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h5>Hosting Information</h5>
                                                        <p>Hosting Link: <span id="detail-hosting_link-{{ $value->id }}" class="text-dark"></span></p>
                                                        <p>Hosting Purchase From: <span id="detail-hosting_purchase_from-{{ $value->id }}" class="text-dark"></span></p>
                                                        <p>Hosting Purchase Date: <span id="detail-hosting_purchase_date-{{ $value->id }}" class="text-dark"></span></p>
                                                        <p>Hosting Expiry Date: <span id="detail-hosting_expire_date-{{ $value->id }}" class="text-dark"></span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h5>Hosting Credentials</h5>
                                                        <p>Hosting Amount: <span id="detail-hosting_amount-{{ $value->id }}" class="text-dark"></span></p>
                                                        <p>Hosting Email: <span id="detail-hosting_email-{{ $value->id }}" class="text-dark"></span></p>
                                                        <p>Hosting ID: <span id="detail-hosting_id-{{ $value->id }}" class="text-dark"></span></p>
                                                        <p>Hosting Password: <span id="detail-hosting_password-{{ $value->id }}" class="text-dark"></span></p>
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

{{-- modal for data insert --}}
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="defaultModalSuccessLabel">Add Client Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addClientForm" action="{{ route('admin/add-hosting-data') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Client Information -->
                        <div class="col-md-6">
                            <label for="clientName" class="form-label">Client Name</label>
                            <input type="text" class="form-control" id="clientName" name="client_name" placeholder="Enter client name" value="{{ old('client_name') }}" required>
                            @error('client_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="clientLogo" class="form-label">Client Logo</label>
                            <input type="file" class="form-control" id="clientLogo" name="client_logo" accept="image/*" required>
                            @error('client_logo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <hr>
                        <!-- Domain Information -->
                        <h5 class="mt-3">Domain Information</h5>
                        <div class="col-md-6">
                            <label for="domainName" class="form-label">Domain Link</label>
                            <input type="text" class="form-control" id="domainName" name="domain_name" placeholder="Enter domain link" value="{{ old('domain_name') }}" required>
                            @error('domain_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="domainPurchaseFrom" class="form-label">Domain Purchase From</label>
                            <input type="text" class="form-control" id="domainPurchaseFrom" name="domain_purchase_from" placeholder="Enter purchase source" value="{{ old('domain_purchase_from') }}" required>
                            @error('domain_purchase_from')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="domainPurchaseDate" class="form-label">Domain Purchase Date</label>
                            <input type="date" class="form-control" id="domainPurchaseDate" name="domain_purchase_date" value="{{ old('domain_purchase_date') }}" required>
                            @error('domain_purchase_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="domainExpireDate" class="form-label">Domain Expire Date</label>
                            <input type="date" class="form-control" id="domainExpireDate" name="domain_expire_date" value="{{ old('domain_expire_date') }}" required>
                            @error('domain_expire_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="domainAmount" class="form-label">Domain Amount</label>
                            <input type="number" class="form-control" id="domainAmount" name="domain_amount" placeholder="Enter domain amount" value="{{ old('domain_amount') }}" required>
                            @error('domain_amount')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="domainEmail" class="form-label">Domain Email</label>
                            <input type="email" class="form-control" id="domainEmail" name="domain_email" placeholder="Enter domain email" value="{{ old('domain_email') }}">
                            @error('domain_email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="domainIP" class="form-label">Domain ID</label>
                            <input type="text" class="form-control" id="domainID" name="domain_id" placeholder="Enter domain ID" value="{{ old('domain_id') }}">
                            @error('domain_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="domainPassword" class="form-label">Domain Password</label>
                            <input type="password" class="form-control" id="domainPassword" name="domain_password" placeholder="Enter domain password" value="{{ old('domain_password') }}" required>
                            @error('domain_password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
            
                        <hr>
                        <!-- Hosting Information -->
                        <h5 class="mt-3">Hosting Information</h5>
                        <div class="col-md-6">
                            <label for="hostingPurchaseFrom" class="form-label">Hosting Purchase From</label>
                            <input type="text" class="form-control" id="hostingPurchaseFrom" name="hosting_purchase_from" placeholder="Enter hosting source" value="{{ old('hosting_purchase_from') }}" required>
                            @error('hosting_purchase_from')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="hostingLink" class="form-label">Hosting Link</label>
                            <input type="text" class="form-control" id="hostingLink" name="hosting_link" placeholder="Enter hosting link" value="{{ old('hosting_link') }}" required>
                            @error('hosting_link')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="hostingPurchaseDate" class="form-label">Hosting Purchase Date</label>
                            <input type="date" class="form-control" id="hostingPurchaseDate" name="hosting_purchase_date" value="{{ old('hosting_purchase_date') }}" required>
                            @error('hosting_purchase_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="hostingExpireDate" class="form-label">Hosting Expire Date</label>
                            <input type="date" class="form-control" id="hostingExpireDate" name="hosting_expire_date" value="{{ old('hosting_expire_date') }}" required>
                            @error('hosting_expire_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="hostingAmount" class="form-label">Hosting Amount</label>
                            <input type="number" class="form-control" id="hostingAmount" name="hosting_amount" placeholder="Enter hosting amount" value="{{ old('hosting_amount') }}" required>
                            @error('hosting_amount')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="hostingEmail" class="form-label">Hosting Email</label>
                            <input type="email" class="form-control" id="hostingEmail" name="hosting_email" placeholder="Enter hosting email" value="{{ old('hosting_email') }}">
                            @error('hosting_email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="hostingIP" class="form-label">Hosting ID</label>
                            <input type="text" class="form-control" id="hostingID" name="hosting_id" placeholder="Enter hosting ID" value="{{ old('hosting_id') }}">
                            @error('hosting_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="hostingPassword" class="form-label">Hosting Password</label>
                            <input type="password" class="form-control" id="hostingPassword" name="hosting_password" placeholder="Enter hosting password" value="{{ old('hosting_password') }}" required>
                            @error('hosting_password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
            
            
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this client? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin/delete-hosting-data') }}" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="client_id" id="client_id">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Hosting Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editForm" method="POST" action="{{ route('admin/update-hosting-data') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Hosting and Domain Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">

                    <!-- Client Information -->
                    <div class="mb-3">
                        <label for="edit-client-name" class="form-label">Client Name</label>
                        <input type="text" class="form-control" id="edit-client-name" name="client_name" required>
                    </div>

                    <!-- Domain Information -->
                    <h6 class="text-primary mt-4">Domain Information</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit-domain-name" class="form-label">Domain Name</label>
                            <input type="text" class="form-control" id="edit-domain-name" name="domain_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-domain-purchase-from" class="form-label">Purchased From</label>
                            <input type="text" class="form-control" id="edit-domain-purchase-from" name="domain_purchase_from">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-domain-purchase-date" class="form-label">Purchase Date</label>
                            <input type="date" class="form-control" id="edit-domain-purchase-date" name="domain_purchase_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-domain-expire-date" class="form-label">Expire Date</label>
                            <input type="date" class="form-control" id="edit-domain-expire-date" name="domain_expire_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-domain-amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="edit-domain-amount" name="domain_amount">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-domain-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit-domain-email" name="domain_email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="domainIP" class="form-label">Domain ID</label>
                            <input type="text" class="form-control" id="domainID" name="domain_id" placeholder="Enter domain ID">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-domain-password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="edit-domain-password" name="domain_password" placeholder="Enter domain password">
                        </div>
                    </div>

                    <!-- Hosting Information -->
                    <h6 class="text-primary mt-4">Hosting Information</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit-hosting-purchase-from" class="form-label">Purchased From</label>
                            <input type="text" class="form-control" id="edit-hosting-purchase-from" name="hosting_purchase_from">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-hosting-link" class="form-label">Hosting Link</label>
                            <input type="url" class="form-control" id="edit-hosting-link" name="hosting_link">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-hosting-amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="edit-hosting-amount" name="hosting_amount">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-hosting-purchase-date" class="form-label">Purchase Date</label>
                            <input type="date" class="form-control" id="edit-hosting-purchase-date" name="hosting_purchase_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-hosting-expire-date" class="form-label">Expire Date</label>
                            <input type="date" class="form-control" id="edit-hosting-expire-date" name="hosting_expire_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-hosting-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit-hosting-email" name="hosting_email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-hosting-id" class="form-label">Hosting ID</label>
                            <input type="text" class="form-control" id="edit-hosting-id" name="hosting_id" placeholder="Enter hosting ID">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-hosting-password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="edit-hosting-password" name="hosting_password" placeholder="Enter hosting password">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>

    document.addEventListener('DOMContentLoaded', function () {
        @if ($errors->any())
            var addClientModal = new bootstrap.Modal(document.getElementById('addClientModal'));
            addClientModal.show();
        @endif
    });

    $(document).ready(function() {
    $('.view-btn').on('click', function() {
        var clientId = $(this).data('id');
        
        // Populate client details in the hidden row
        $('#detail-client_name-' + clientId).text($(this).data('client_name'));
        $('#detail-domain_name-' + clientId).text($(this).data('domain_name'));
        $('#detail-domain_purchase_from-' + clientId).text($(this).data('domain_purchase_from'));
        $('#detail-domain_purchase_date-' + clientId).text($(this).data('domain_purchase_date'));
        $('#detail-domain_expire_date-' + clientId).text($(this).data('domain_expire_date'));
        $('#detail-domain_amount-' + clientId).text($(this).data('domain_amount'));
        $('#detail-domain_email-' + clientId).text($(this).data('domain_email'));
        $('#detail-domain_id-' + clientId).text($(this).data('domain_id'));
        $('#detail-domain_password-' + clientId).text($(this).data('domain_password'));
        
        // Hosting details
        $('#detail-hosting_purchase_from-' + clientId).text($(this).data('hosting_purchase_from'));
        $('#detail-hosting_link-' + clientId).text($(this).data('hosting_link'));
        $('#detail-hosting_amount-' + clientId).text($(this).data('hosting_amount'));
        $('#detail-hosting_purchase_date-' + clientId).text($(this).data('hosting_purchase_date'));
        $('#detail-hosting_expire_date-' + clientId).text($(this).data('hosting_expire_date'));
        $('#detail-hosting_email-' + clientId).text($(this).data('hosting_email'));
        $('#detail-hosting_id-' + clientId).text($(this).data('hosting_id'));
        $('#detail-hosting_password-' + clientId).text($(this).data('hosting_password'));

        // Toggle the visibility of the client details row
        $('#details-' + clientId).toggle();
    });

});

$(document).ready(function() {
    $('.delete-btn').on('click', function() {
        var clientId = $(this).data('id'); // Get the client ID from the button's data-id attribute
        $('#client_id').val(clientId); // Set the client ID in the hidden input field inside the form
    });
});


$(document).on('click', '.edit-btn', function () {
    // Populate the modal using data-* attributes
    $('#edit-id').val($(this).data('id'));
    $('#edit-client-name').val($(this).data('client_name'));
    $('#edit-domain-name').val($(this).data('domain_name'));
    $('#edit-domain-purchase-from').val($(this).data('domain_purchase_from'));
    $('#edit-domain-purchase-date').val($(this).data('domain_purchase_date'));
    $('#edit-domain-expire-date').val($(this).data('domain_expire_date'));
    $('#edit-domain-amount').val($(this).data('domain_amount'));
    $('#edit-domain-email').val($(this).data('domain_email'));
    $('#edit-domain-id').val($(this).data('domain_id'));
    $('#edit-domain-password').val($(this).data('domain_password'));
    $('#edit-hosting-purchase-from').val($(this).data('hosting_purchase_from'));
    $('#edit-hosting-link').val($(this).data('hosting_link'));
    $('#edit-hosting-amount').val($(this).data('hosting_amount'));
    $('#edit-hosting-purchase-date').val($(this).data('hosting_purchase_date'));
    $('#edit-hosting-expire-date').val($(this).data('hosting_expire_date'));
    $('#edit-hosting-email').val($(this).data('hosting_email'));
    $('#edit-hosting-id').val($(this).data('hosting_id'));
    $('#edit-hosting-password').val($(this).data('hosting_password'));

    // Show the modal
    $('#editModal').modal('show');
});



</script>

@endsection
