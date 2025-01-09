@extends('layout/employee-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        <img src="{{asset('storage/profile_photos').'/'.session('employee')->profile_photo_path}}" class="avatar img-fluid rounded me-1"  />
    </div>
        <div class="flex-grow-1 ps-2">
            <p class="text-white">{{session('employee')->first_name}}</p>
            {{-- <div class="sidebar-user-subtitle">Designer</div> --}}
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
</style>
<div class="container-fluid p-0">

    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Employee List</h1>    
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
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Username</th>
                                    <th colspan="3">Birth Date</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Phone No.</th>
                                    <th>Address</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                    $counter = 1;
                                @endphp
                                @foreach($employees as $data) 
                                <tr>
                                    <td>{{$counter}}</td>
                                    <td>{{$data->first_name}}</td>
                                    <td>{{$data->last_name}}</td>
                                    <td>{{$data->username}}</td>
                                    <td colspan="3">{{ \Carbon\Carbon::parse($data->birth_date)->format('d-m-y') }}</td>
                                    <td>{{$data->depname}}</td>
                                    <td>{{$data->desname}}</td>
                                    <td>{{$data->phone}}</td>
                                    <td>{{ \Illuminate\Support\Str::words($data->address, 50, '...') }}</td>
                                    <td>{{$data->email}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <li>
                                                    <a class="dropdown-item text-primary" href="#" data-bs-toggle="modal" data-bs-target="#defaultModalSuccess" onclick="openpostmethod({{ $data->uid }})">Edit</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="{{ route('emp/delete-emp-data', $data->uid) }}">Delete</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-secondary" href="#">Inactive</a>
                                                </li>
                                            </ul>
                                        </div>
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
</div>

<div class="modal fade" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <form method="POST" action="{{route('emp/update-emp-data')}}">
                    @csrf
                    <input type="text" id="id" hidden name="id">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="inputAddress">First Name</label>
                            <input type="text" class="form-control @error('fname') is-invalid @enderror" name="fname" id="fname" placeholder="First name">
                            @error('fname')
                            <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="inputPassword4">Last Name</label>
                            <input type="text" class="form-control @error('lname') is-invalid @enderror" name="lname" id="lname" placeholder="Last name">
                            @error('lname')
                            <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                                <label class="form-label" for="inputEmail4">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Email">
                                @error('email')
                                <p class="invalid-feedback">{{$message}}</p>
                                @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="inputAddress">Phone No.</label>
                            <input type="number" class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone">
                            @error('phone')
                            <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="inputAddress">Birthdate</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" required name="date" id="date">
                            @error('date')
                            <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="inputAddress">Emp Code</label>
                            <input type="number" class="form-control @error('code') is-invalid @enderror" required name="code" id="code">
                            @error('code')
                            <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="inputAddress">Department</label>
                            <select name="department" class="form-control  @error('department') is-invalid @enderror" required onselect="" id="department">
                                @error('department')
                                <p class="invalid-feedback">{{$message}}</p>
                                @enderror
                                    <option value="">Select Department</option>
                                    @foreach($department as $data1)
                                    <option value="{{$data1->id}}">{{$data1->name}}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">                    
                            <label class="form-label" for="inputAddress">Designation</label>
                            <select name="designation" class="form-control  @error('designation') is-invalid @enderror" required id="designation">
                                @error('designation')
                                <p class="invalid-feedback">{{$message}}</p>
                                @enderror
                                @foreach($designation as $data1)
                                <option value="{{$data1->id}}">{{$data1->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="inputPassword4">Address</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" id="address" placeholder="Address">
                            @error('address')
                            <p class="invalid-feedback">{{$message}}</p>
                            @enderror  
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save changes</button>
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

    function openpostmethod(id)
    {
    $.ajax({
            url: "{{url('emp/edit-emp-data/')}}/"+id,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            statusCode:{
              200: function (response) {
                let birthDate = new Date(response.birth_date);
                let day = ('0' + birthDate.getDate()).slice(-2);
                let month = ('0' + (birthDate.getMonth() + 1)).slice(-2);
                let year = birthDate.getFullYear();
                let formattedDate = `${year}-${month}-${day}`;
                $('#id').val(response.id);
                $('#fname').val(response.first_name);
                $('#lname').val(response.last_name);
                $('#email').val(response.email);
                $('#phone').val(response.phone);
                $('#address').val(response.address);
                $('#date').val(formattedDate);
                $('#department').val(response.department);
                $('#designation').val(response.designation);
                $("#code").val(response.empcode);

            }
            },
            error: function (error) {
                console.log(error);
            }
      });
   }

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
</script>
@endsection