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

@section('menu')
<form class="d-none d-sm-inline-block">
  <div class="input-group input-group-navbar">
      <input type="text" id="search" class="form-control" placeholder="Search…" aria-label="Search">
      <button class="btn" type="button">
          <i class="align-middle" data-feather="search"></i>
      </button>
  </div>
</form>

<ul class="navbar-nav d-none d-lg-flex">
    <li class="nav-item px-2 dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="megaDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            Mega Menu
        </a>
        <div class="dropdown-menu dropdown-menu-start dropdown-mega" aria-labelledby="megaDropdown">
            <div class="d-md-flex align-items-start justify-content-start">
               
            </div>
        </div>
    </li>
</ul>
@endsection
@section('content')
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
        <h1 class="h3 d-inline align-middle">Projects</h1> 
        <a href="{{route('admin/add-project-detail')}}" class="btn btn-primary float-end mt-n1"><i class="fas fa-plus"></i> New project</a>
    </div> 
</div>

<div class="col-md-12">
  <div class="card">
    <div class="row">
        @foreach($project as $data)
        <div class="col-12 col-md-6 col-lg-3 project-item" data-name="{{ strtolower($data->name) }}">  
            <div class="card">
                <div class="card-header px-4 pt-4">
                    <div class="card-actions float-end">
                        <div class="dropdown position-relative">
                            <a href="#" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item edit-project-btn" href="#" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editProjectModal" 
                                data-id="{{ $data->project_id }}"
                                data-name="{{ $data->name }}" 
                                data-description="{{ $data->description }}" 
                                data-status="{{ $data->status }}"
                                data-priority="{{ $data->priority }}">Edit</a>

                                <a class="dropdown-item" href="{{route('admin/delete-project-detail',$data->project_id)}}">Delete</a>  

                                <a class="dropdown-item edit-users-btn" href="#" data-bs-toggle="modal" 
                                data-bs-target="#editUsersModal" data-id="{{ $data->project_id }}">Edit Users</a>
                            </div>
                        </div>
                    </div>
                    <h3 class="card-title" style="font-size: large">{{$data->name}}</h3>
                    <span>({{$data->typename}})</span><br>
                    @if($data->status == '2')
                    <div class="badge bg-success">Finished</div>
                    @elseif($data->status == '1')
                    <div class="badge bg-warning">Ongoing</div>
                    @else
                    <div class="badge bg-danger">Pending</div>
                    @endif

                    <!-- New Priority Label -->
                    <div class="">
                        <span class="badge 
                            @if($data->priority == 'urgent') bg-danger
                            @elseif($data->priority == 'normal') bg-warning
                            @else bg-secondary @endif">
                            {{ ucfirst($data->priority) }} Priority
                        </span>
                    </div>
                </div>
                <div class="card-body px-4 pt-2">
                    <p>{{$data->description}}</p>

                    @php
                        $projectUsers = collect($user)->filter(function ($projectUser) use ($data) {
                        return $projectUser->project_id === $data->project_id;
                        });
                    @endphp
        
                    @foreach($projectUsers as $projectUser)
                        @if(!empty($projectUser->profile_photo_path)) 
                            <img src="{{ asset('storage/profile_photos/'.$projectUser->profile_photo_path) }}" class="rounded-circle me-1" alt="Avatar" width="28" height="28" data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="{{ $projectUser->first_name . ' ' . $projectUser->last_name }}">
                        @else
                            <img src="{{ asset('storage/img/avatars/default.png') }}" class="rounded-circle me-1" alt="Avatar" width="28" height="28" data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="{{ $projectUser->first_name . ' ' . $projectUser->last_name }}">
                        @endif
                    @endforeach

                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-4 pb-4">
                        <p class="mb-2 font-weight-bold">Progress <span class="float-end">100%</span></p>
                        <div class="progress progress-sm">
                            <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        @endforeach
    </div>
</div>
</div>


<!-- Edit Project & User Modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editProjectModalLabel">Edit Project & Users</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editProjectForm" method="POST" action="{{route('admin/project-detail-update')}}">
            @csrf
            <input type="hidden" id="project_id" name="project_id">
            <div class="mb-3">
              <label for="name" class="form-label">Project Name</label>
              <input type="text" class="form-control" id="project_name" name="name" required>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Description</label>   
              <textarea class="form-control" id="project_description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
              <label for="status" class="form-label">Status</label>
              <select class="form-select" id="project_status" name="status" required>
                <option value="0" style="color: red">Pending</option>
                <option value="1"  style="color: #ffcc00">Ongoing</option>
                <option value="2"  style="color: green">Finished</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="priority" class="form-label">Priority</label>
              <select class="form-select" id="project_priority" name="priority" required>
                <option value="normal"  style="color: #ffcc00">Normal</option>
                <option value="urgent" style="color: red">Urgent</option>
              </select>
            </div>
  
            <button type="submit" class="btn btn-primary">Save changes</button>
          </form>
        </div>
      </div>
    </div>
  </div>


<!-- Edit Users Modal -->
<div class="modal fade" id="editUsersModal" tabindex="-1" aria-labelledby="editUsersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editUsersModalLabel">Edit Assigned Users</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editUsersForm" method="POST" action="{{route('admin/project-user-update')}}">
            @csrf
            <input type="hidden" id="id" name="project_id">

            <div class="mb-3">
              <label for="assigned_users" class="form-label">Assigned Users</label>
              <select multiple class="form-select" id="edit_assigned_users" name="assigned_users[]">
                @foreach($employee as $data)
                  <option value="{{ $data->id }}">{{ $data->first_name }}{{$data->last_name}}</option>
                @endforeach
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  
<script>

    document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.edit-project-btn');
    
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const projectId = this.getAttribute('data-id');
            const projectName = this.getAttribute('data-name');
            const projectDescription = this.getAttribute('data-description');
            const projectPriority = this.getAttribute('data-priority');

            // Populate modal fields for project
            document.getElementById('project_id').value = projectId;
            document.getElementById('project_name').value = projectName;
            document.getElementById('project_description').value = projectDescription;
            document.getElementById('project_status').value = projectStatus;
            document.getElementById('project_priority').value = projectPriority;

        });
    });
});

  document.addEventListener('DOMContentLoaded', function () {
          var editUsersModal = document.getElementById('editUsersModal');
          var projectIDInput = document.getElementById('id');

         
          document.querySelectorAll('.edit-users-btn').forEach(function (button) {
              button.addEventListener('click', function (event) {
                  var projectId = this.getAttribute('data-id');
                  
                  projectIDInput.value = projectId;
              });
          });
      });

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const projectItems = document.querySelectorAll('.project-item');

    searchInput.addEventListener('keyup', function() {

      const query = searchInput.value.toLowerCase();

        projectItems.forEach(function(item) {
            const projectName = item.getAttribute('data-name'); 

            if (projectName.includes(query)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
    
</script> 
@endsection