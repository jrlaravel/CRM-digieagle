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
        <h1 class="h3 d-inline align-middle">Department List</h1>   
        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#defaultModalSuccess">Add</button> 
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Department Name</th>
                                <th>status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                             @php
                                $counter = 1;
                            @endphp
                           @foreach($data as $department) 
                            <tr>
                                <td>{{$counter}}</td>
                                <td>{{$department->name}}</td>
                                <td>
                                    @if($department->status == 1)
                                    <a href="{{ route('admin/status-department', [$department->id, $department->status]) }}" 
                                        class="btn btn-success" 
                                        data-bs-toggle="tooltip" 
                                        title="Change status to inactive">
                                        Active
                                     </a>
                                    @endif
                                    @if($department->status == 0)
                                    <a href="{{route('admin/status-department',[$department->id,$department->status])}}" 
                                        class="btn btn-danger" 
                                        data-bs-toggle="tooltip" 
                                        title="Change status to inactive">Inactive</a>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('admin/delete-department',$department->id)}}" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            @php
                                $counter++;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>  
</div>

<div class="modal fade" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <form method="POST" action="{{route('admin/add-department')}}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="inputAddress">Name</label>
                        <input type="text" class="form-control"  name="name" id="name" required placeholder="department">
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Datatables Responsive
        $("#datatables-reponsive").DataTable({
            responsive: true
        });
    });

   
</script>
@endsection