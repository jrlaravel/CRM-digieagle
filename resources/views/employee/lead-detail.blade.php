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
    .form-control:disabled {
    background-color: #ffffff;
    opacity: 1;
    }

    /* Style the select dropdown */
.select-container {
    position: relative;
    width: 100%;
}

.select-container select {
    appearance: none; /* Remove default arrow */
    -webkit-appearance: none; /* For Safari */
    -moz-appearance: none; /* For Firefox */
    background: none;
    padding-right: 30px; /* Space for the arrow */
    width: 100%;
    height: 100%;
    font-size: 14px;
    padding: 5px;
    box-sizing: border-box;
}

/* Add a custom down arrow */
.select-container::after {
    content: '\25BC'; /* Unicode for down arrow */
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none; /* Make sure the icon doesn't block interaction */
    font-size: 12px;
    color: #333;
}
</style>
<div class="container-fluid p-0">

    <h1 class="h3 mb-3">Lead Details</h1>

    <div class="row">
        <div class="col-md-3 col-xl-2">

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Lead Settings</h5>
                </div>

                <div class="list-group list-group-flush" role="tablist">
                    <a class="list-group-item list-group-item-action active" data-bs-toggle="list" href="#account" role="tab">
                        Lead Details
                    </a>
                    <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#password" role="tab">
                       Add Follow-up
                    </a>
                    <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#activity" role="tab">
                        Follow-up Details
                     </a>
                     <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#details" role="tab">
                        Lead Bussiness Details
                     </a>
                </div>
            </div>
        </div>

        <div class="col-md-9 col-xl-10">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="account" role="tabpanel">

                    <div class="card">
                        <div class="card-header">

                            <h5 class="card-title mb-0">Private info</h5>
                             @if(Session::has('success'))
                                <div class="alert alert-success">{{Session::get('success')}}</div>
                                @endif
                                @if(Session::has('error'))
                                <div class="alert alert-danger">{{Session::get('error')}}</div>
                                @endif
                        </div>
                        <div class="card-body">
                            <form>
                              
                                <div class="row">
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputFirstName">First name</label>
                                        <input type="text" class="form-control" id="inputFirstName" value="{{$lead->first_name}}" disabled placeholder="First name">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputLastName">Status</label>
                                        <input type="text" class="form-control" id="inputLastName" value="{{$lead->status}}" disabled placeholder="Last name">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="inputFirstName">Company name</label>
                                        <input type="text" class="form-control" id="inputcomName" value="{{$lead->company_name}}" disabled placeholder="First name">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="inputLastName">Description</label>
                                        <input type="text" class="form-control" id="inputdescription" value="{{$lead->description}}" disabled placeholder="Last name">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputEmail4">Email</label>
                                        <input type="email" class="form-control" id="inputEmail4" value="{{$lead->email}}" disabled placeholder="Email">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputEmail4">Phone</label>
                                        <input type="number" class="form-control" id="inputphone" value="{{$lead->phone}}" disabled placeholder="Email">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="phone">Whatsapp No:</label>
                                        <input type="number" class="form-control" id="whatsappphone" min="10" name="whatsappphone" placeholder="Whatsapp Phone" value="{{$lead->whatsappno}}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputAddress">Instagram</label>
                                        <input type="text" class="form-control" id="inputAddress" value="{{$lead->inslink}}" disabled >
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputCity">Facebook</label>
                                        <input type="text" class="form-control" value="{{$lead->facebooklink}}" disabled >
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputState">Website</label>
                                        <input type="text" class="form-control" value="{{$lead->weblink}}" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputAddress">Address</label>
                                        <input type="text" class="form-control" id="inputAddress" value="{{$lead->address}}" disabled placeholder="1234 Main St">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputCity">City</label>
                                        <input type="text" class="form-control" value="{{$lead->city}}" disabled id="inputCity">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputState">State</label>
                                        <input type="text" class="form-control" value="{{$lead->state}}" disabled id="inputstate">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="tab-pane fade" id="password" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Add Follow-up</h5>
                                <form action="{{ route('emp/add-followup') }}" method="post">
                                    @csrf

                                    <input type="hidden" id="id" name="id">

                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                
                                    <div class="mb-3">
                                        <label class="form-label" for="inputTitle">Title</label>
                                        <input type="text" class="form-control" name="title" id="inputTitle">
                                    </div>
                                
                                    <div class="mb-3">
                                        <label class="form-label" for="dateInput">Date</label>
                                        <input type="date" name="date" id="dateInput" class="form-control">
                                    </div>
                                
                                    <div class="mb-3">
                                        <label class="form-label" for="message">Update Message</label>
                                        <input type="text" name="message" class="form-control" id="message">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="message">Call Reminder</label>
                                        <input type="checkbox" name="callreminder" id="callreminder">
                                    </div>
                                    
                                    <div class="mb-3" id="callDateContainer" style="display: none;">
                                        <label class="form-label" for="dateInput">Call Date</label>
                                        <input type="date" name="call_date" id="call-dateInput" class="form-control">
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
                <div class="tab-pane fade" id="activity" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Follow-up Details</h5>
    
                            <ul class="timeline mt-4 mb-0">
                                @foreach($followups as $data)
                                <li class="timeline-item">
                                    <a href="#" class="edit-followup" 
                                       data-id="{{ $data->id }}" 
                                       data-title="{{ $data->title }}"
                                       data-call-date="{{ $data->call_date }}"
                                       data-date="{{ $data->date }}"
                                       data-message="{{ $data->message }}"
                                       data-status="{{ $data->previous_status }}">
                                       <strong>{{ $data->title }}</strong>
                                    </a>
                                    <span class="float-end text-muted text-sm">{{ $data->date }}</span>
                                    @if(!empty($data->call_date)) <!-- Check if call_date is not empty -->
                                    <span class="badge bg-info text-white">Call Date: {{ \Carbon\Carbon::parse($data->call_date)->format('M d, Y') }}</span> <!-- Display call date -->
                                    @endif
                                    <p style="margin-bottom: 2rem !important">
                                        {{ $data->message }}
                                        <a href="{{ route('emp/delete-followup', $data->id) }}" class="btn btn-danger btn-sm float-end">
                                            <i class="fas fa-trash-alt fa-xs"></i> <!-- Font Awesome trash icon -->
                                        </a>
                                    </p>
                                    <!-- Delete Button -->
                                    
                                </li>
                                @endforeach
                            </ul>      
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="details" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Lead Business Details</h5>
                
                                @if(!empty($leadDetails) && count($leadDetails) > 0)
                                    @foreach($leadDetails as $serviceName => $questions)
                                        <div class="card mb-3">
                                            <div class="card-header bg-primary">
                                                <h5 class="mb-0" style="color: #ffffff">{{ $serviceName }}</h5>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-group">
                                                    @foreach($questions as $key => $q)
                                                        <li class="list-group-item">
                                                            <strong>{{ $key+1 }}. {{ $q->question }}</strong> <br>
                                                            Answer: {{ $q->answer }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p>No questions available.</p>
                                @endif
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>

</div>
<script>

    document.getElementById('callreminder').addEventListener('change', function() {
    const callDateContainer = document.getElementById('callDateContainer');
    callDateContainer.style.display = this.checked ? 'block' : 'none';
    });

    const today = new Date().toISOString().split('T')[0];
    document.getElementById('dateInput').setAttribute('min', today);

    document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.edit-followup').forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default link behavior

            // Get data attributes from the clicked element
            var id = this.getAttribute('data-id');
            var title = this.getAttribute('data-title');
            var date = this.getAttribute('data-date');
            var message = this.getAttribute('data-message');
            var status = this.getAttribute('data-status');
            var calldate = this.getAttribute('data-call-date');

            
            // Populate the form fields with the extracted data
            document.getElementById('id').value = id;
            document.getElementById('inputTitle').value = title;
            document.getElementById('dateInput').value = date;
            document.getElementById('message').value = message;
            
            var statusDropdown = document.getElementById('status-filter');
            statusDropdown.value = status;

            if (!statusDropdown.value) {
                var options = statusDropdown.options;
                for (var i = 0; i < options.length; i++) {
                    if (options[i].text === status) {
                        statusDropdown.selectedIndex = i;
                        break;
                    }
                }
            }
              var callReminderCheckbox = document.getElementById('callreminder');
            callReminderCheckbox.checked = calldate !== null && calldate !== ''; 

            var callDateInput = document.getElementById('call-dateInput');
            if (calldate) {
                callDateInput.value = calldate; 
                callDateInput.closest('.mb-3').style.display = 'block'; 
            } else {
                callDateInput.value = ''; 
                callDateInput.closest('.mb-3').style.display = 'none'; 
            }

            // Optionally, activate the tab
            var tabLink = document.querySelector('a[href="#password"]');
            if (tabLink) {
                var bootstrapTab = new bootstrap.Tab(tabLink);
                bootstrapTab.show();
            }
        });
    });
});

</script>
@endsection