@extends('layout/employee-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        <img src="{{asset('storage/profile_photos').'/'.session('employee')->profile_photo_path}}" class="avatar img-fluid rounded me-1"  />
    </div>
    <div class="flex-grow-1 ps-2">
        
          <p class="text-white">{{session('employee')->first_name}}</p>

    </div>
</div>
@endsection
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

    .skill-badge {
            color: black;
            margin-right: 5px;
            margin-bottom: 5px;
        }
</style>
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Add New Employee</h5>
            </div>
        <div class="card-body">
            <form method="POST" action="{{route('emp/add-emp-data')}}">
                @csrf
                @if(Session::has('success'))
                <div class="alert alert-success">{{Session::get('success')}}</div>
                @endif
                @if(Session::has('error'))
                <div class="alert alert-danger">{{Session::get('error')}}</div>
                @endif

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
                <div class="mb-3">
                        <label class="form-label" for="inputEmail4">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{old('email')}}" placeholder="Email">
                        @error('email')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="inputAddress">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" value="{{old('username')}}" placeholder="Username">
                        @error('username')
                        <p class="invalid-feedback">{{$message}}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="inputPassword4">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{old('password')}}" id="password" placeholder="Password">
                        @error('password')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror 
                        <i 
                            id="toggleIcon" 
                            class="fa fa-eye position-absolute" 
                            onclick="togglePasswordVisibility()" 
                            style="cursor: pointer; right: 28px; top: 358px; transform: translateY(-50%);"
                        ></i> 
                </div>
                <div class="mb-3">
                    <label class="form-label" for="inputEmail4">Empcode</label>
                    <input type="number" class="form-control @error('empcode') is-invalid @enderror" name="empcode" id="empcode" value="{{old('empcode')}}" placeholder="Empcode">
                    @error('empcode')
                    <p class="invalid-feedback">{{$message}}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="inputEmail4">Birth Date</label>
                    <input type="date" class="form-control @error('email') is-invalid @enderror" name="birthdate" id="birthdate" value="{{old('birth_date')}}" placeholder="birthdate">
                    @error('birthdate')
                    <p class="invalid-feedback">{{$message}}</p>
                    @enderror
                </div>
                <div class="mb-3">
                        <label class="form-label" for="inputAddress">Phone No.</label>
                        <input type="number" class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone" value="{{old('phone')}}" placeholder="Phone No.">
                        @error('phone')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="inputAddress">Department</label>
                        <select name="department" class="form-control  @error('department') is-invalid @enderror" onselect="" id="department">
                            @error('department')
                            <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                                <option value="">Select Department</option>
                                @foreach($department as $data)
                                <option value="{{$data->id}}">{{$data->name}}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">                    
                        <label class="form-label" for="inputAddress">Designation</label>
                        <select name="designation" class="form-control  @error('designation') is-invalid @enderror" id="designation">
                            <option value="">Select Designation</option>
                            @error('designation')
                            <p class="invalid-feedback">{{$message}}</p>
                            @enderror
            
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="skills">Skills</label>
                    <input type="text" class="form-control @error('skills') is-invalid @enderror" id="skills-input"  placeholder="Enter a skill and press Enter">
                    @error('skills')
                    <p class="invalid-feedback">{{ $message }}</p>
                    @enderror  
                    <div id="skills-container" class="mt-2"></div>
                    <input type="hidden" name="skills[]" id="skills-hidden">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="inputPassword4">Address</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" id="address" value="{{ old('address') }}" placeholder="Address">
                        @error('address')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror  
                </div>
               
                <button type="submit" class="btn btn-success">Add</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#department').on('change', function() {
            var departmentId = this.value;
            $('#designation').html('');
            if (departmentId) {
                $.ajax({
                    url: '{{ route("emp/get-designations") }}',
                    type: 'GET',
                    data: { department_id: departmentId },
                    success: function(data) {
                        $('#designation').html('<option value="">Select Designation</option>');
                        $.each(data, function(key, value) {
                            $('#designation').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                        });
                    }
                });
            } 
            else {
                $('#designation').html('<option value="">Select Designation</option>');
            }
        });
    });

        document.addEventListener('DOMContentLoaded', function () {
            const skillsInput = document.getElementById('skills-input');
            const skillsContainer = document.getElementById('skills-container');
            const skillsHiddenInput = document.getElementById('skills-hidden');
            let skills = [];

            skillsInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const skill = skillsInput.value.trim();
                    if (skill && !skills.includes(skill)) {
                        skills.push(skill);
                        updateSkillsContainer();
                        updateSkillsHiddenInput();
                    }
                    skillsInput.value = '';
                }
            });

            skillsContainer.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-skill')) {
                    const skill = e.target.getAttribute('data-skill');
                    skills = skills.filter(s => s !== skill);
                    updateSkillsContainer();
                    updateSkillsHiddenInput();
                }
            });

            function updateSkillsContainer() {
                skillsContainer.innerHTML = skills.map(skill => `
                    <span class="badge badge-primary skill-badge">
                        ${skill} <span class="remove-skill" data-skill="${skill}" style="cursor: pointer;">&times;</span>
                    </span>
                `).join('');
            }

            function updateSkillsHiddenInput() {
                skillsHiddenInput.value = skills.join(',');
            }
        });

        function togglePasswordVisibility() {
		var passwordInput = document.getElementById('password');
		var toggleIcon = document.getElementById('toggleIcon');
		if (passwordInput.type === "password") {
			passwordInput.type = "text";
			toggleIcon.classList.remove('fa-eye');
			toggleIcon.classList.add('fa-eye-slash');
		} else {
			passwordInput.type = "password";
			toggleIcon.classList.remove('fa-eye-slash');
			toggleIcon.classList.add('fa-eye');
		}
	}
    </script>
@endsection