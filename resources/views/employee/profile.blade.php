@extends('layout/employee-sidebar')
@section('content')
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
</style>
<div class="container-fluid p-0">

    <h1 class="h3 mb-3">Profile</h1>

<div class="row">
    <div class="col-md-4 col-xl-3">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Profile Details</h5>
            </div>
            <div class="card-body text-center">
                @if($data[0]->profile_photo_path == null)
                <img src="{{ asset('storage/profile_photos/default.png') }}" 
                     class="img-fluid rounded-circle mb-2" 
                     style="width: 128px; height: 128px; object-fit: cover;" />
                @else
                    <img src="{{ asset('storage/profile_photos') . '/' . $data[0]->profile_photo_path }}"  
                        class="img-fluid rounded-circle mb-2" 
                        style="width: 128px; height: 128px; object-fit: cover;" />
                @endif
            
                <h5 class="card-title mb-0">{{$data[0]->first_name}} {{$data[0]->last_name}} </h5>
                <div class="text-muted mb-2">{{$data[0]->desname}}</div>
                
                <form action="{{ route('emp/profilephoto') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="profile_photo" class="form-control mt-3" required accept="image/*"/>
                    <button type="submit" class="btn btn-primary mt-2">Upload Photo</button>
                </form>
            </div>
            <hr class="my-0" />
            <div class="card-body">
                <h5 class="h6 card-title">Skills</h5>
                @php
                    $decodedSkills = json_decode($data[0]->skills, true);
                    $decodedSkills = json_decode($decodedSkills, true);

                    if (is_array($decodedSkills) && isset($decodedSkills[0])) {
                        $skillsArray = array_map('trim', explode(',', $decodedSkills[0]));

                        foreach ($skillsArray as $skill) {
                            
                            echo '<h4 class="badge bg-primary me-1 my-1">'.$skill.'</h4>';
                        }
                    } else {
                        echo "No skills available.";
                    }
                @endphp
            </div>
            <hr class="my-0" />
            <div class="card-body">
                <h5 class="h6 card-title">About</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-1"><span data-feather="home" class="feather-sm me-1"></span> Lives in <a href="#">{{$data[0]->address}}</a>
                    </li>

                    <li class="mb-1"><span data-feather="briefcase" class="feather-sm me-1"></span> Department <a href="#">{{$data[0]->depname}}</a></li>
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

                <form method="POST" action="{{route('emp/Updateprofile')}}">
                @csrf
                @if(Session::has('success'))
                <div class="alert alert-success">{{Session::get('success')}}</div>
                @endif
                @if(Session::has('error'))
                <div class="alert alert-danger">{{Session::get('error')}}</div>
                @endif
                
                <input type="hidden" name="id" value="{{session('employee')->id}}">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="inputAddress">First Name</label>
                        <input type="text" class="form-control @error('fname') is-invalid @enderror" value="{{$data[0]->first_name}}" name="fname" id="name" placeholder="First name">
                        @error('fname')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="inputPassword4">Last Name</label>
                        <input type="text" class="form-control @error('lname') is-invalid @enderror" name="lname" value="{{$data[0]->last_name}}" id="password" placeholder="Last name">
                        @error('lname')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="inputEmail4">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" disabled value="{{$data[0]->email}}" placeholder="Email">
                        @error('email')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="inputEmail4">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" value="{{$data[0]->username}}" placeholder="username">
                        @error('username')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="inputAddress">Phone No.</label>
                    <input type="number" class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone" value="{{$data[0]->phone}}" placeholder="Phone No.">
                    @error('phone')
                    <p class="invalid-feedback">{{$message}}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="inputAddress">Birth date</label>
                    <input type="date" class="form-control @error('phone') is-invalid @enderror" name="birthdate" id="birthdate" value="{{$data[0]->birth_Date}}" placeholder="Phone No.">
                    @error('birthdate')
                    <p class="invalid-feedback">{{$message}}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="inputPassword4">Address</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" id="address" value="{{$data[0]->address}}" placeholder="Address">
                        @error('address')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror  
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Change Password</h5>
            </div>
            <div class="card-body h-100">
                <form method="POST" action="{{ route('emp/changepassword') }}">
                    @csrf
                    @if(Session::has('success_password'))
                        <div class="alert alert-success">{{ Session::get('success_password') }}</div>
                    @endif
                    @if(Session::has('error_password'))
                        <div class="alert alert-danger">{{ Session::get('error_password') }}</div>
                    @endif
                
                    <div class="mb-3">
                        <label class="form-label" for="current_password">Current Password</label>
                        <input type="password" 
                                class="form-control @error('current_password') is-invalid @enderror" 
                                name="current_password" 
                                id="current_password" 
                                placeholder="Current Password">
                        @error('current_password')
                            <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                
                    <div class="mb-3">
                        <label class="form-label" for="new_password">New Password</label>
                        <input type="password" 
                                class="form-control @error('new_password') is-invalid @enderror" 
                                name="new_password" 
                                id="new_password" 
                                placeholder="New Password">
                        @error('new_password')
                            <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                
                    <div class="mb-3">
                        <label class="form-label" for="new_password_confirmation">Confirm New Password</label>
                        <input type="password" 
                                class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                name="new_password_confirmation" 
                                id="new_password_confirmation" 
                                placeholder="Confirm New Password">
                        @error('new_password_confirmation')
                            <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </form>       
            </div>
        </div>
    </div>
</div>

@endsection