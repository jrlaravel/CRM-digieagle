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
        <p class="text-white">{{session('employee')->first_name}}</p>
        {{-- <div class="sidebar-user-subtitle">Designer</div> --}}
    </div>
</div>
@endsection
@section('content')
<div class="container-fluid p-0">
    <div class="card">
        <div class="card-body">
            <!-- Filter Checkboxes -->
            <div class="mb-3 d-flex flex-wrap justify-content-start align-items-center">
                <label class="me-4 mb-2" style="font-size: 16px;">
                    <input type="checkbox" id="birthdayFilter" checked> Show Birthdays
                </label>
                <label class="me-4 mb-2" style="font-size: 16px;">
                    <input type="checkbox" id="leaveFilter" checked> Show Festival Leaves
                </label>
            </div>

            <!-- Calendar Section -->
            <div id="fullcalendar" class="mt-3"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/fullcalendar.js')}}"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var calendarEl = document.getElementById("fullcalendar");

    var birthdays = @json($data);
    var leaves = @json($leave);

    function transformBirthdayData(birthdays) {
        const year = new Date().getFullYear();
        return birthdays.map(item => {
            const [day, month] = item.start.split('-');
            const formattedDate = `${year}-${month}-${day}`;
            return {
                id: 'birthday-' + item.id, // Unique ID for filtering
                title: item.name + "'s birthday",
                start: formattedDate,
                backgroundColor: '#ff9f89',
                borderColor: '#ff9f89',
                type: 'birthday' // Custom property for filtering
            };
        });
    }

    function transformLeaveData(leaves) {
        return leaves.map(item => {
            return {
                id: 'leave-' + item.id, // Unique ID for filtering
                title: item.name,
                start: item.start_date,
                end: item.end_date,
                description: item.reason,
                backgroundColor: '#1e90ff',
                borderColor: '#1e90ff',
                type: 'leave' // Custom property for filtering
            };
        });
    }

    var transformedBirthdays = transformBirthdayData(birthdays);
    var transformedLeaves = transformLeaveData(leaves);
    var allEvents = transformedBirthdays.concat(transformedLeaves);

    var calendar = new FullCalendar.Calendar(calendarEl, {
        themeSystem: "bootstrap",
        initialView: "dayGridMonth",
        initialDate: new Date(),
        events: allEvents,
        eventDidMount: function(info) {
            if (info.event.extendedProps.description) {
                $(info.el).tooltip({
                    title: info.event.extendedProps.description,
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body'
                });
            }
        }
    });

    calendar.render();

    // Event filtering function
    function filterEvents() {
        var showBirthdays = document.getElementById('birthdayFilter').checked;
        var showLeaves = document.getElementById('leaveFilter').checked;

        var filteredEvents = allEvents.filter(event => {
            if (event.type === 'birthday' && !showBirthdays) {
                return false;
            }
            if (event.type === 'leave' && !showLeaves) {
                return false;
            }
            return true;
        });

        calendar.removeAllEvents();
        calendar.addEventSource(filteredEvents);
    }

    // Attach event listeners to checkboxes
    document.getElementById('birthdayFilter').addEventListener('change', filterEvents);
    document.getElementById('leaveFilter').addEventListener('change', filterEvents);
});
</script>
@endsection
