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
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Add Lead</h1> 
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
       
        <div class="card-body">
            <form action="{{route('emp/add-lead')}}" method="post">
                @csrf
                @if(Session::has('success'))
                <div class="alert alert-success">{{Session::get('success')}}</div>
                @endif
                @if(Session::has('error'))
                <div class="alert alert-danger">{{Session::get('error')}}</div>
                @endif
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="inputAddress">First Name</label>
                        <input type="text" class="form-control @error('fname') is-invalid @enderror" name="fname" id="fname" value="{{old('fname')}}" placeholder="First name">
                        @error('fname')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="inputPassword4">Last Name</label>
                        <input type="text" class="form-control @error('lname') is-invalid @enderror" name="lname" id="lname" value="{{old('lname')}}" placeholder="Last name">
                        @error('lname')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                    </div>
                </div>
            
                {{-- Company Name --}}
                <div class="mb-3">
                    <label class="form-label" for="company_name">Company Name:</label>
                    <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" placeholder="Company name" value="{{ old('company_name') }}" required>
                    @error('company_name')
                    <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="company_name">Description</label>
                    <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="description" value="{{ old('description') }}" required>
                    @error('description')
                    <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>
            
                {{-- Assigned User --}}
                <div class="mb-3">
                    <label class="form-label" for="user_id">Assigned User:</label>
                    <select id="user_id" name="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                        <option value="">Select User</option>
                        @foreach($user as $data)
                        <option value="{{ $data->id }}">{{ $data->first_name }} {{$data->last_name}}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                    <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>
            
                {{-- Email --}}
                <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label" for="email">Email:</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                    @error('email')
                    <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>
            
                {{-- Phone --}}
                <div class="mb-3 col-md-6">
                    <label class="form-label" for="phone">Phone:</label>
                    <input type="number" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="Phone" value="{{ old('phone') }}" required>
                    @error('phone')
                    <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>
                </div>
            
                {{-- City --}}
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="city">City:</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" placeholder="City" value="{{ old('city') }}" required>
                        @error('city')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                
                    {{-- State --}}
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="state">State:</label>
                        <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" placeholder="State" value="{{ old('state') }}" required>
                        @error('state')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            
                {{-- Status --}}
                <div class="mb-3">
                    <label class="form-label" for="status">Status:</label>
                    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                        <option value="">Select Status</option>
                        <option value="Not interested"  {{ old('status') == 'Not interested' ? 'selected' : '' }}  class="text-danger">Not interested</option>
                        <option value="Prospect" {{ old('status') == 'Prospect' ? 'selected' : '' }} class="text-warning">Prospect</option>
                        <option value="lead" {{ old('status') == 'lead' ? 'selected' : '' }} class="text-info">Lead</option>
                        <option value="hot lead" {{ old('status') == 'hot lead' ? 'selected' : '' }} class="text-primary">Hot Lead</option>
                        <option value="client" {{ old('status') == 'client' ? 'selected' : '' }} class="text-success">Client</option>
                    </select>
                    @error('status')    
                    <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>
            
                {{-- Address --}}
                <div class="mb-3">
                    <label class="form-label" for="address">Address:</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Address" required>{{ old('address') }}</textarea>
                    @error('address')
                    <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>
            
                {{-- Submit Button --}}
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            
            
        </div>
    </div>
</div>


<script>
    $(".chosen-select").chosen({
  no_results_text: "Oops, nothing found!"
})
</script>
@endsection