@extends('layout/admin-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        @if(session('user') && session('user')->profile_photo_path)
            <img src="{{ asset('storage/profile_photos') . '/' . session('user')->profile_photo_path }}" class="avatar img-fluid rounded" />
        @else
            <img src="{{ asset('storage/profile_photos/default.png') }}" class="avatar img-fluid rounded" />
        @endif	
    </div>
        <div class="flex-grow-1 ps-2">
            <p class="text-white">{{session('user')->first_name}}</p>
            {{-- <div class="sidebar-user-subtitle">Designer</div> --}}
    </div>
</div>
@endsection
@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Activity log</h1>   
    </div>

    <div class="row">
        <div class="col-12">    
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>User id</th>
                                    <th>Description</th>
                                    <th>Action Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forEach($data as $key => $log)
                                <tr>
                                     <td>{{ $key + 1 }}</td>
                                    <td>{{ $log->user_id }}</td>
                                    <td>{{ $log->description }}</td>
                                     <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:m:s') }}</td>
                                </tr>
                               @endforeach
                            </tbody>
                        </table>                        
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>

@endsection
@section('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

<script>

    $(document).ready(function () {
        $('#datatables-reponsive').DataTable({
            responsive: true,
            pageLength: 5, // Number of rows per page
        });
    });
</script>
@endsection