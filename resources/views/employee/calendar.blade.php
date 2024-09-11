@extends('layout/employee-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        <img src="{{asset('storage/profile_photos').'/'.session('employee')->profile_photo_path}}" class="avatar img-fluid rounded me-1" alt="Charles Hall" />
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
                <div id="fullcalendar"></div>
            </div>
        </div>
    </div>
    
    <script src="{{asset('js/fullcalendar.js')}}"></script>

   <script>
      document.addEventListener("DOMContentLoaded", function() {
    var calendarEl = document.getElementById("fullcalendar");
    
    
    var data = @json($data); 

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: data.map(event => ({
            title: event.name,
            start: event.start_date,
            end: event.end_date ,
            backgroundColor: 'orange', // Set event background color to orange
            borderColor: 'orange' 
        })),
    });

    calendar.render();
});

    </script>
    

@endsection