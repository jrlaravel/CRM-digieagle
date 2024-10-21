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
                        <h5 class="card-title">Present Days</h5>
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
                        <h5 class="card-title">Abesent Days</h5>
                    </div>
                </div>
                <h1 class="mt-1 mb-3">{{$absentDaysCount}}</h1>
            </div>
        </div>
    </div>
</div>

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