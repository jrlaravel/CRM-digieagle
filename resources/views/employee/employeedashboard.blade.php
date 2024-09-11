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

<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h3>Cards</h3>
        <div class="color-checkbox">
            <input type="checkbox" name="red card" id="red-checkbox">
            <label for="red-checkbox" class="red">Red</label>
        </div>
    
        <div class="color-checkbox">
            <input type="checkbox" name="green card" id="green-checkbox">
            <label for="green-checkbox" class="green">Green</label>
        </div>
    
        <div class="color-checkbox">
            <input type="checkbox" name="black card" id="black-checkbox">
            <label for="black-checkbox" class="black">Black</label>
        </div>
    
        <div class="color-checkbox">
            <input type="checkbox" name="yellow card" id="yellow-checkbox">
            <label for="yellow-checkbox" class="yellow">Yellow</label>
        </div>
    
        <div class="color-checkbox">
            <input type="checkbox" name="golden card" id="golden-checkbox">
            <label for="golden-checkbox" class="golden">Golden</label>
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

        // Add event listener to each checkbox
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', filterCards);
        });

        // Filter cards based on the checked checkboxes
        function filterCards() {
            // Get checked colors from checkbox IDs
            const checkedColors = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.name); // Extract color from checkbox id, e.g., "red-checkbox" => "red"

            console.log("Checked Colors: ", checkedColors); // Debugging line

            // Show or hide cards based on the checked colors
            cards.forEach(card => {
                const cardColor = card.getAttribute('data-color').toLowerCase();
                console.log("Card Color: ", cardColor); // Debugging line

                if (checkedColors.length === 0) {
                    // Hide all cards when no checkboxes are checked
                    card.style.display = 'none';
                } else if (checkedColors.includes(cardColor)) {
                    // Show card if its color is included in checkedColors
                    card.style.display = 'inline-block';
                } else {
                    // Hide card if its color is not included in checkedColors
                    card.style.display = 'none';
                }
            });
        }

        // Initial call to set the correct state of cards on page load
        // Set a timeout to ensure it runs after the page has fully loaded
        setTimeout(() => {
            filterCards();
        }, 1); // Delay slightly to ensure page is fully rendered
    });
</script>




@endsection