@extends('layout/admin-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        <img src="{{asset('storage/profile_photos').'/'.session('user')->profile_photo_path}}" class="avatar img-fluid rounded me-1"  />
    </div>
    <div class="flex-grow-1 ps-2">
        
          <p class="text-white">{{session('user')->first_name}}</p>

    </div>
</div>
@endsection
@section('content')

<div class="container-fluid p-0">

    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Assign Card</h1>   
        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#defaultModalSuccess">Assign</button> 
    </div>

    <div class="row">
        <div class="col-12">
            
            <div class="card">
                <div class="card-body">
                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Card</th>
                                <th>Date</th>
                                <th>Message</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assigncard as $key => $cards)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$cards->first_name}} {{$cards->last_name}}</td>
                                <td>{{$cards->name}}</td>
                                <td>{{$cards->date}}</td>
                                <td>{{$cards->message}}</td>
                                <td>
                                    <a href="{{route('admin/delete-assign-card',$cards->id)}}" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
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
                <h5 class="modal-title">Assign Card</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <form method="POST" action="{{route('admin/add-assign-card')}}">
                    @csrf
                    
                    <div class="mb-3">
                            <label class="form-label" for="inputEmail4">Employee</label>
                            <select class="form-control" name="employee" id="">
                                <option value="">Select Employee</option>
                                @foreach($data as $emp)
                                <option value="{{$emp->id }}">{{$emp->first_name.' '.$emp->last_name}}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="inputAddress">Card</label>
                        <select class="form-control" name="card" id="">
                            <option value="">Select Card</option>
                            @foreach($card as $cards)
                            <option value="{{$cards->id }}">{{$cards->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="inputPassword4">Message</label>
                            <input type="text" class="form-control @error('message') is-invalid @enderror" name="message" value="{{old('message')}}" id="message" placeholder="message">
                            @error('password')1
                            <p class="invalid-feedback">{{$message}}</p>
                            @enderror  
                    </div>
                    
                   
                    <button type="submit" class="btn btn-success">Add</button>
                </form>
        </div>
    </div>
</div>
@endsection