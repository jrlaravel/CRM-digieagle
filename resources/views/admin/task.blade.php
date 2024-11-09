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

<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">{{$project->name}}</h1>   
        <a href="{{route('admin/add-project-detail')}}" class="btn btn-primary float-end"><i class="fas fa-plus"></i> New Task</a>
    </div> 
</div>
<div class="row">
    <div class="col-12 col-lg-6 col-xl-4">
        <div class="card">
            <div class="card-header">
                <div class="card-actions float-end">
                    <div class="dropdown position-relative">
                        <a href="#" data-bs-toggle="dropdown" data-bs-display="static">
                            <i class="align-middle" data-feather="more-horizontal"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
                </div>
                <h5 class="card-title">Backlog</h5>
                <h6 class="card-subtitle text-muted">Nam pretium turpis et arcu. Duis arcu.</h6>
            </div>
            <div class="card-body">

                <div id="tasks-backlog" style="min-height:50px;">
                    <div class="card mb-3 bg-light cursor-grab border">
                        <div class="card-body p-3">
                            <div class="float-end me-n2">
                                <label class="form-check">
                                    <input type="checkbox" class="form-check-input" checked>
                                    <span class="form-check-label d-none">Checkbox</span>
                                </label>
                            </div>
                            <p>Curabitur ligula sapien, tincidunt non, euismod vitae, posuere imperdiet, leo. Maecenas malesuada.</p>
                            <div class="float-end mt-n1">
                                <img src="img/avatars/avatar.jpg" width="32" height="32" class="rounded-circle" alt="Avatar">
                            </div>
                            <a class="btn btn-primary btn-sm" href="#">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6 col-xl-4">
        <div class="card">
            <div class="card-header">
                <div class="card-actions float-end">
                    <div class="dropdown position-relative">
                        <a href="#" data-bs-toggle="dropdown" data-bs-display="static">
                            <i class="align-middle" data-feather="more-horizontal"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
                </div>
                <h5 class="card-title">In Progress</h5>
                <h6 class="card-subtitle text-muted">Nam pretium turpis et arcu. Duis arcu.</h6>
            </div>
            <div class="card-body">

                <div id="tasks-progress" style="min-height:50px;">
                    <div class="card mb-3 bg-light cursor-grab border">
                        <div class="card-body p-3">
                            <div class="float-end me-n2">
                                <label class="form-check">
                                    <input type="checkbox" class="form-check-input">
                                    <span class="form-check-label d-none">Checkbox</span>
                                </label>
                            </div>
                            <p>Nam pretium turpis et arcu. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum.</p>
                            <div class="float-end mt-n1">
                                <img src="img/avatars/avatar-2.jpg" width="32" height="32" class="rounded-circle" alt="Avatar">
                            </div>
                            <a class="btn btn-primary btn-sm" href="#">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6 col-xl-4">
        <div class="card">
            <div class="card-header">
                <div class="card-actions float-end">
                    <div class="dropdown position-relative">
                        <a href="#" data-bs-toggle="dropdown" data-bs-display="static">
                            <i class="align-middle" data-feather="more-horizontal"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
                </div>
                <h5 class="card-title">Completed</h5>
                <h6 class="card-subtitle text-muted">Nam pretium turpis et arcu. Duis arcu.</h6>
            </div>
            <div class="card-body">

                <div id="tasks-completed" style="min-height:50px;">
                    <div class="card mb-3 bg-light cursor-grab border">
                        <div class="card-body p-3">
                            <div class="float-end me-n2">
                                <label class="form-check">
                                    <input type="checkbox" class="form-check-input">
                                    <span class="form-check-label d-none">Checkbox</span>
                                </label>
                            </div>
                            <p>Nam pretium turpis et arcu. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum.</p>
                            <div class="float-end mt-n1">
                                <img src="img/avatars/avatar-2.jpg" width="32" height="32" class="rounded-circle" alt="Avatar">
                            </div>
                            <a class="btn btn-primary btn-sm" href="#">View</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection