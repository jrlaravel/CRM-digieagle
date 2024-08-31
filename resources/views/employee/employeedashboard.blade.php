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
        
    </div>
</div>
@endsection