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

    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Designation List</h1>   
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
                                <th>Designation Name</th>
                                <th>Department Name</th>
                                <th>status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                             @php
                                $counter = 1;
                            @endphp
                            @foreach ($designation as $data1)
                            <tr>
                                <td>{{$counter}}</td>
                                <td>{{$data1->desname}}</td>
                                <td>{{$data1->depname}}</td>
                                <td>
                                    @if($data1->depstatus == 1)
                                    @if($data1->desstatus == 1)
                                    <a href="{{route('admin/status-designation',[$data1->desid,$data1->desstatus])}}"
                                    class="btn btn-success" 
                                    data-bs-toggle="tooltip" 
                                    title="Change status to inactive">active</a>
                                    @endif
                                    @if($data1->desstatus == 0)
                                    <a href="{{route('admin/status-designation',[$data1->desid,$data1->desstatus])}}" 
                                        class="btn btn-danger" 
                                        data-bs-toggle="tooltip" 
                                        title="Change status to active">Inactive</a>
                                    @endif
                                    @endif
                                    
                                    @if($data1->depstatus == 0)
                                    
                                    <span class="btn btn-danger">Inactive</span>
                                    
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('admin/delete-designation',$data1->desid)}}"  class="btn btn-danger">delete</a>
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
                <h5 class="modal-title">Add Designation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <form method="POST" action="{{route('admin/add-designation')}}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="inputAddress">Name</label>
                        <input type="text" class="form-control"  name="name" id="name" required placeholder="designation">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="inputAddress2">Department</label>
                        <select name="department" required class="form-control" id="department">
                            <option value="">Select Department</option>
                            @foreach($data as $department)
                            <option value="{{$department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                        
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