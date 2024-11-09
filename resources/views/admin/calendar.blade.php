@extends('layout/admin-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        <img src="{{asset('storage/profile_photos').'/'.session('user')->profile_photo_path}}" class="avatar img-fluid rounded me-1" />
    </div>
    <div class="flex-grow-1 ps-2">
        <p class="text-white">{{session('user')->first_name}}</p>
    </div>
</div>
@endsection

@section('content')
    <div class="container-fluid p-0">
        <div class="card">
            <div class="card-body">
                <!-- Filter Checkboxes -->
                <div class="mb-3">
                    <label><input type="checkbox" id="birthdayFilter" checked> Show Birthdays</label>
                    <label><input type="checkbox" id="leaveFilter" checked> Show Leaves</label>
                    <label><input type="checkbox" id="festivalLeaveFilter" checked> Show Festival Leaves</label>
                </div>

                <div id="fullcalendar"></div>
            </div>
        </div>
    </div>
    
    <script src="{{asset('js/fullcalendar.js')}}"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var calendarEl = document.getElementById("fullcalendar");

        var birthdays = @json($data);
        var leaves = @json($leave);
        var festivalLeaves = @json($festivalleave);

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
                    title: item.first_name + ' on leave',
                    start: item.start_date,
                    end: item.end_date,
                    description: item.reason,
                    backgroundColor: '#1e90ff',
                    borderColor: '#1e90ff',
                    type: 'leave' // Custom property for filtering
                };
            });
        }

        function transformFestivalLeaveData(festivalLeaves) {
            return festivalLeaves.map(item => {
                return {
                    id: 'festivalLeave-' + item.id, // Unique ID for filtering
                    title: item.name,
                    start: item.start_date,
                    end: item.end_date,
                    backgroundColor: '#ffd700', // Gold color for festival leaves
                    borderColor: '#ffd700',
                    type: 'festivalLeave' // Custom property for filtering
                };
            });
        }

        var transformedBirthdays = transformBirthdayData(birthdays);
        var transformedLeaves = transformLeaveData(leaves);
        var transformedFestivalLeaves = transformFestivalLeaveData(festivalLeaves);
        var allEvents = transformedBirthdays.concat(transformedLeaves, transformedFestivalLeaves);

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
            var showFestivalLeaves = document.getElementById('festivalLeaveFilter').checked;

            var filteredEvents = allEvents.filter(event => {
                if (event.type === 'birthday' && !showBirthdays) {
                    return false;
                }
                if (event.type === 'leave' && !showLeaves) {
                    return false;
                }
                if (event.type === 'festivalLeave' && !showFestivalLeaves) {
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
        document.getElementById('festivalLeaveFilter').addEventListener('change', filterEvents);
    });
    </script>
@endsection
