@extends('layout/employee-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        <img src="{{asset('storage/profile_photos').'/'.session('employee')->profile_photo_path}}" class="avatar img-fluid rounded me-1" alt="" />
    </div>
    <div class="flex-grow-1 ps-2">
           <h4 class="text-white">{{session('employee')->first_name}}</h4>
    </div>
</div>
@endsection
@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Lead List</h1>
        <button id="download-excel" class="btn btn-success float-end">Download Excel</button>
        <div class="float-end me-2">
            <select id="status-filter" class="form-select">
                <option value="">&#11044; All Status</option>
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
                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Company name</th>
                                <th>Description</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $lead)
                            <tr data-status="{{ $lead->status }}">
                                <td>{{ ++$key }}</td>
                                <td><a href="{{route('emp/lead-datail',$lead->id)}}">{{ $lead->first_name }} {{ $lead->last_name }}</a></td>
                                <td>{{ $lead->company_name }}</td>
                                <td>{{ $lead->description }}</td>
                                <td>{{ $lead->email }}</td>
                                <td>{{ $lead->phone }}</td>
                                <td>{{ $lead->city }}</td>
                                <td>{{ $lead->state }}</td>
                                <td>{{ $lead->address }}</td>
                                <td>
                                    @if($lead->status == 'Prospect')
                                        <span class="badge bg-warning">Prospect</span>
                                    @elseif($lead->status == 'lead')
                                        <span class="badge bg-info">Lead</span>
                                    @elseif($lead->status == 'hot lead')
                                        <span class="badge bg-primary">Hot Lead</span>
                                        @elseif($lead->status == 'Client')
                                        <span class="badge bg-success">Client</span>
                                        @else
                                        <span class="badge bg-danger">Not interested</span>

                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-primary edit-lead" 
                                            data-id="{{ $lead->id }}"
                                            data-first_name="{{ $lead->first_name }}"
                                            data-last_name="{{ $lead->last_name }}"
                                            data-company_name="{{ $lead->company_name }}"
                                            data-description="{{ $lead->description }}"
                                            data-email="{{ $lead->email }}"
                                            data-phone="{{ $lead->phone }}"
                                            data-city="{{ $lead->city }}"
                                            data-state="{{ $lead->state }}"
                                            data-address="{{ $lead->address }}"
                                            data-status="{{ $lead->status }}">
                                        Edit
                                    </button>
                                    <a href="{{route('emp/lead-delete', $lead->id)}}" class="btn btn-danger">Delete</a>
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
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name">
                    </div>
    
                    <div class="mb-3 col-md-6">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="company_name" class="form-label">Company Name</label>
                    <input type="text" class="form-control" id="company_name" name="company_name">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" class="form-control" id="description" name="description">
                </div>

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
    
                    <div class="mb-3 col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone">
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
                    <select id="status-filter" name="status" class="form-select">
                        <option value="">&#11044; All Status</option>
                        <option value="Not interested" class="text-danger"> &#11044; Not interested</option>
                        <option value="Prospect" class="text-warning"> &#11044; Prospect</option>
                        <option value="lead" class="text-info"> &#11044; Lead</option>
                        <option value="hot lead" class="text-primary"> &#11044; Hot Lead</option>
                        <option value="client" class="text-success"> &#11044; Client</option>
                    </select>   
                </div>

                <button type="submit" class="btn btn-primary">Save changes</button>
            </form>
        </div>
    </div>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.2/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/exceljs@latest/dist/exceljs.min.js"></script>


<!-- jQuery Script to handle modal and populate values -->
<script>
    $(document).on('click', '.edit-lead', function() {
        let leadId = $(this).data('id');
        let firstName = $(this).data('first_name');
        let lastName = $(this).data('last_name');
        let companyName = $(this).data('company_name');
        let description = $(this).data('description');
        let email = $(this).data('email');
        let phone = $(this).data('phone');
        let city = $(this).data('city');
        let state = $(this).data('state');
        let address = $(this).data('address');
        let status = $(this).data('status');
        let insLink = $(this).data('inslink'); // Instagram link
        let facebookLink = $(this).data('facebooklink'); // Facebook link
        let webLink = $(this).data('weblink'); // Website link
    
        // Set modal input values
        $('#lead-id').val(leadId);
        $('#first_name').val(firstName);
        $('#last_name').val(lastName);
        $('#company_name').val(companyName);
        $('#description').val(description);
        $('#email').val(email);
        $('#phone').val(phone);
        $('#city').val(city);
        $('#state').val(state);
        $('#address').val(address);
        $('#status-filter').val(status);
        $('#instagram').val(insLink); // Set Instagram link
        $('#facebook').val(facebookLink); // Set Facebook link
        $('#website').val(webLink); // Set Website link
    
        // Show the modal
        $('#editLeadModal').modal('show');
    });

    document.getElementById('download-excel').addEventListener('click', function() {
        // Create a new workbook and worksheet
        var wb = new ExcelJS.Workbook();
        var ws = wb.addWorksheet('Lead Data');

        // Select the table element
        var originalTable = document.getElementById('datatables-reponsive');
        
        // Get the header row and data rows
        var headerRow = originalTable.querySelector('thead tr');
        var dataRows = originalTable.querySelectorAll('tbody tr');
        
        // Extract headers
        var headers = Array.from(headerRow.querySelectorAll('th'))
                            .map(th => th.textContent.trim());
        
        // Remove the last header (Action column)
        headers.pop();

        // Add headers to worksheet
        ws.addRow(headers);
        ws.getRow(1).font = { bold: true };

        // Add data rows to worksheet
        dataRows.forEach(function(tr) {
            var cells = Array.from(tr.querySelectorAll('td'))
                             .map(td => td.textContent.trim());
            
            // Remove the last cell (Action column)
            cells.pop();
            
            // Add the row to the worksheet
            ws.addRow(cells);
        });

        // Save to file
        wb.xlsx.writeBuffer().then(function(buffer) {
            var blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'leads-data.xlsx';
            link.click();
        });
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
</script>

@endsection