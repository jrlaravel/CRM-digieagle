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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
<style>
    .platform-checkboxes {
    display: flex;
    flex-wrap: wrap;
    gap: 15px; /* Adjust space between items */
}
.platform-checkboxes label {
    font-size: 15px;
    margin-left: 5px;
}

.platform-checkboxes div {
    display: inline-flex;
    align-items: center;
}

</style>
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Add Project</h1> 
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
       
        <div class="card-body">
            <form method="POST" action="{{route('admin/add-project-detail')}}">
                @csrf
                @if(Session::has('success'))
                <div class="alert alert-success">{{Session::get('success')}}</div>
                @endif
                @if(Session::has('error'))
                <div class="alert alert-danger">{{Session::get('error')}}</div>
                @endif

                
                    <div class="mb-3">
                        <label class="form-label" for="inputAddress">Project Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name')}}" placeholder="Project name">
                        @error('name')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="inputPassword4">Description</label>
                        <input type="text" class="form-control @error('lname') is-invalid @enderror" name="description"  value="{{old('description')}}" placeholder="Description">
                        @error('description')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                    </div>
               
                <div class="mb-3">
                    <label class="form-label" for="inputEmail4">Project Type</label>
                    @error('type')
                    <p class="invalid-feedback">{{$message}}</p>
                    @enderror
                    <select name="type" class="form-control" id="">
                        <option value="">Select Type</option>
                        @foreach($type as $value)
                        <option value="{{$value->id}}">{{$value->name}}</option>
                        @endforeach
                    </select>
                </div>


                <div class="mb-3">
                    <label class="form-label" for="inputEmail4">Assign Members</label>
                    @error('member')
                    <p class="invalid-feedback">{{$message}}</p>
                    @enderror
                    <div class="d-flex flex-wrap">
                        {{-- <form action="http://httpbin.org/post" method="post"> --}}
                            <select multiple class="chosen-select form-control" name="member[]">
                              <option value=""></option>
                              @foreach($user as $data)
                              <option value="{{$data->id}}">{{$data->first_name}}</option>
                              @endforeach
                            </select>
                          {{-- </form> --}}
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="inputAddress">Target Audience Age (Optional)</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" name="tagetage"  value="{{old('tagetage')}}" placeholder="Age">
                        @error('tagetage')
                        <p class="invalid-feedback">{{$message}}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="inputEmail4">Target City (Optional)</label>
                    <input type="text" class="form-control @error('empcode') is-invalid @enderror" name="targetcity" value="{{old('target_city')}}" placeholder="target city">
                    @error('targetcity')
                    <p class="invalid-feedback">{{$message}}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="platform">Platform</label>
                    <div class="platform-checkboxes">
                        <div> 
                            <input type="checkbox" id="facebook" name="platform[]" value="Facebook">
                            <label for="facebook">Facebook</label>
                        </div>
                        <div>
                            <input type="checkbox" id="twitter" name="platform[]" value="Twitter">
                            <label for="twitter">Twitter</label>
                        </div>
                        <div>
                            <input type="checkbox" id="instagram" name="platform[]" value="Instagram">
                            <label for="instagram">Instagram</label>
                        </div>
                        <div>
                            <input type="checkbox" id="chrome" name="platform[]" value="Chrome">
                            <label for="chrome">Chrome</label>
                        </div>
                        <div>
                            <input type="checkbox" id="firefox" name="platform[]" value="Firefox">
                            <label for="firefox">Firefox</label>
                        </div>
                        <div>
                            <input type="checkbox" id="safari" name="platform[]" value="Safari">
                            <label for="safari">Safari</label>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label" for="inputPassword4">Start Date</label>
                        <input type="date" class="form-control" name="startdate"  value="{{ old('startdate') }}">
                        @error('startdate')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror  
                </div>

                <div class="mb-3">
                    <label class="form-label" for="inputPassword4">Deadline Date</label>
                        <input type="date" class="form-control" name="deadlinedate"  value="{{ old('deadlinedate') }}">
                        @error('deadlinedate')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror  
                </div>
                 
                <button type="submit" class="btn btn-success">Add</button>
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