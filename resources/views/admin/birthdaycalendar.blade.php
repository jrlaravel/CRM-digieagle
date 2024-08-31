@extends('layout/admin-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        <img src="{{asset('storage/profile_photos').'/'.session('user')->profile_photo_path}}" class="avatar img-fluid rounded me-1" alt="Charles Hall" />
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
        
        var data = @json($data);
        // Function to convert the data
        function transformData(data) {
            const year = new Date().getFullYear(); // Current year
            
            return data.map(item => {
                const [day, month] = item.start.split('-');
                
                // Format to YYYY-MM-DD
                const formattedDate = `${year}-${month}-${day}`;
                
                return {
                    title:  item.name + "'s birthday",
                    start: formattedDate
                };
            });
        }
        
        // Transform the data
        var transformedData = transformData(data);
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            themeSystem: "bootstrap",
            initialView: "dayGridMonth",
            initialDate: "2024-07-07",
            
            events: transformedData,
        });
        setTimeout(function() {
            calendar.render();
        }, 250)
    });
</script>

@endsection