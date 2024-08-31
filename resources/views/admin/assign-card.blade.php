@extends('layout/admin-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        <img src="{{asset('storage/profile_photos').'/'.session('user')->profile_photo_path}}" class="avatar img-fluid rounded me-1" alt="Charles Hall" />
    </div>
    <div class="flex-grow-1 ps-2">
        
          <p class="text-white">{{session('user')->first_name}}</p>

    </div>
</div>
@endsection
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Assign Card</h5>
            </div>
        <div class="card-body">
            <form method="POST" action="{{route('admin/add-assign-card')}}">
                @csrf
                @if(Session::has('success'))
                <div class="alert alert-success">{{Session::get('success')}}</div>
                @endif
                @if(Session::has('error'))
                <div class="alert alert-danger">{{Session::get('error')}}</div>
                @endif
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