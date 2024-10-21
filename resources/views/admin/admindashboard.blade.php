@extends('layout/admin-sidebar')

@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        <img src="{{ asset('storage/profile_photos') . '/' . session('user')->profile_photo_path }}" class="avatar img-fluid rounded me-1" alt="Charles Hall" />
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
            <form action="{{ route('emp/add-followup') }}" method="post">
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

@endsection