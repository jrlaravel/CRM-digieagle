@extends('layout/admin-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        <img src="{{asset('storage/profile_photos').'/'.session('user')->profile_photo_path}}" class="avatar img-fluid rounded me-1"  />
    </div>
    <div class="flex-grow-1 ps-2">
       
           <p class="text-white">{{session('user')->first_name}}</p>
        {{-- <div class="sidebar-user-subtitle">Designer</div> --}}
    </div>
</div>
@endsection
@section('content')
    <div class="container-fluid p-0">
        <div class="card">
            <div class="card-body">
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
    
            // Function to transform birthday data
            function transformBirthdayData(birthdays) {
                const year = new Date().getFullYear(); // Current year
                
                return birthdays.map(item => {
                    const [day, month] = item.start.split('-');
                    
                    // Format to YYYY-MM-DD
                    const formattedDate = `${year}-${month}-${day}`;
                    
                    return {
                        title: item.name + "'s birthday",
                        start: formattedDate,
                        backgroundColor: '#ff9f89', // Color for birthday events
                        borderColor: '#ff9f89'
                    };
                });
            }
    
            // Function to transform leave data
            function transformLeaveData(leaves) {
                return leaves.map(item => {
                    return {
                        title: item.first_name + ' on leave',
                        start: item.start_date,
                        end: item.end_date,
                        description: item.reason,
                        backgroundColor: '#1e90ff', // Color for leave events
                        borderColor: '#1e90ff'
                    };
                });
            }
    
            // Transform the data
            var transformedBirthdays = transformBirthdayData(birthdays);
            var transformedLeaves = transformLeaveData(leaves);
    
            // Combine both arrays
            var events = transformedBirthdays.concat(transformedLeaves);
    
            var calendar = new FullCalendar.Calendar(calendarEl, {
                themeSystem: "bootstrap",
                initialView: "dayGridMonth",
                initialDate: new Date(),
                events: events, // Load combined events
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
    
            setTimeout(function() {
                calendar.render();
            }, 250);
        });
    </script>
    

@endsection