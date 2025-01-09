@extends('layout/employee-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        @if(session('employee') && session('employee')->profile_photo_path)
            <img src="{{ asset('storage/profile_photos') . '/' . session('employee')->profile_photo_path }}" class="avatar img-fluid rounded" />
        @else
            <img src="{{ asset('storage/profile_photos/default.png') }}" class="avatar img-fluid rounded" />
        @endif	
    </div>
    <div class="flex-grow-1 ps-2">
       
           <p class="text-white">{{session('employee')->first_name}}</p>
        {{-- <div class="sidebar-user-subtitle">Designer</div> --}}
    </div>
</div>
@endsection
@section('content')

<div class="main">

    @section('menu')
    <form class="d-none d-sm-inline-block">
        <div class="input-group input-group-navbar">
            <input type="text" class="form-control" placeholder="Search…" aria-label="Search">
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
                    @forEach($type as $data)
                    <div class="dropdown-mega-list">
                        <div class="dropdown-header">{{$data->name}}</div>
                        @forEach($project->where('project_type', $data->id) as $projectdata)
                        <a class="dropdown-item" href="#">{{$projectdata->name}}</a>
                         @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
        </li>
    </ul>
    @endsection

    <main class="content">
        <div class="container-fluid p-0">

            <a href="#" class="btn btn-primary float-end mt-n1"><i class="fas fa-plus"></i> New task</a>
            <div class="mb-3">
                <h1 class="h3 d-inline align-middle">Tasks</h1>
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
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        dragula([
            document.querySelector("#tasks-backlog"),
            document.querySelector("#tasks-progress"),
            document.querySelector("#tasks-completed")
        ]);
    });
</script>

@endsection