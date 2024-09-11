@extends('layout/admin-sidebar')
s@section('content')
<div class="container-fluid p-0">
    <style>
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    
        input[type=number] {
            -moz-appearance: textfield;
        }
    
        input[type=number]::-ms-inner-spin-button,
        input[type=number]::-ms-outer-spin-button {
            display: none;
            margin: 0;
        }
    
        .skill-badge {
                color: black;
                margin-right: 5px;
                margin-bottom: 5px;
            }
    </style>
    <h1 class="h3 mb-3">Profile</h1>

    <div class="row">
        <div class="col-md-4 col-xl-3">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Profile Details</h5>
                </div>
                <div class="card-body text-center">
                    <img src="{{asset('storage/profile_photos').'/'.session('user')->profile_photo_path}}" class="img-fluid rounded-circle mb-2" width="128" height="128" />
                    <h5 class="card-title mb-0">{{$data->first_name}} {{$data->last_name}} </h5>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('admin/profilephoto') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="profile_photo" class="form-control mt-3" required accept="image/*"/>
                        <button type="submit" class="btn btn-primary mt-2">Upload Photo</button>
                    </form>

                    <div class="mt-3">
                        <a class="btn btn-primary btn-sm" href="#">Follow</a>
                        <a class="btn btn-primary btn-sm" href="#"><span data-feather="message-square"></span> Message</a>
                    </div>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <h5 class="h6 card-title">Skills</h5>
                    <a href="#" class="badge bg-primary me-1 my-1">HTML</a>
                    <a href="#" class="badge bg-primary me-1 my-1">JavaScript</a>
                    <a href="#" class="badge bg-primary me-1 my-1">Sass</a>
                    <a href="#" class="badge bg-primary me-1 my-1">Angular</a>
                    <a href="#" class="badge bg-primary me-1 my-1">Vue</a>
                    <a href="#" class="badge bg-primary me-1 my-1">React</a>
                    <a href="#" class="badge bg-primary me-1 my-1">Redux</a>
                    <a href="#" class="badge bg-primary me-1 my-1">UI</a>
                    <a href="#" class="badge bg-primary me-1 my-1">UX</a>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <h5 class="h6 card-title">About</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-1"><span data-feather="home" class="feather-sm me-1"></span> Lives in <a href="#">{{$data->address}}</a>
                        </li>

                        <li class="mb-1"><span data-feather="briefcase" class="feather-sm me-1"></span> Department <a href="#">GitHub</a></li>
                        <li class="mb-1"><span data-feather="map-pin" class="feather-sm me-1"></span> From <a href="#">Boston</a></li>
                    </ul>
                </div>
                <hr class="my-0" />
            </div>
        </div>

        <div class="col-md-8 col-xl-9">
            <div class="card">
                <div class="card-header">

                    <h5 class="card-title mb-0">Proflie Update</h5>
                </div>
                <div class="card-body h-100">

                    <form method="POST" action="{{route('admin/update-emp-data')}}">
                    @csrf
                    @if(Session::has('success'))
                    <div class="alert alert-success">{{Session::get('success')}}</div>
                    @endif
                    @if(Session::has('error'))
                    <div class="alert alert-danger">{{Session::get('error')}}</div>
                    @endif
                    
                    <input type="hidden" name="id" value="{{session('user')->id}}">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="inputAddress">First Name</label>
                            <input type="text" class="form-control @error('fname') is-invalid @enderror" value="{{$data->first_name}}" name="fname" id="name" placeholder="First name">
                            @error('fname')
                            <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="inputPassword4">Last Name</label>
                            <input type="text" class="form-control @error('lname') is-invalid @enderror" name="lname" value="{{$data->last_name}}" id="password" placeholder="Last name">
                            @error('lname')
                            <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="inputEmail4">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" disabled value="{{$data->email}}" placeholder="Email">
                            @error('email')
                            <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="inputEmail4">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" value="{{$data->username}}" placeholder="username">
                            @error('username')
                            <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" for="inputAddress">Phone No.</label>
                        <input type="number" class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone" value="{{$data->phone}}" placeholder="Phone No.">
                        @error('phone')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" for="inputPassword4">Address</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" id="address" value="{{$data->address}}" placeholder="Address">
                            @error('address')
                            <p class="invalid-feedback">{{$message}}</p>
                            @enderror  
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection