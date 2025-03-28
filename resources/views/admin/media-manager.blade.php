@extends('layout/admin-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        @if(session('user') && session('user')->profile_photo_path)
            <img src="{{ asset('storage/profile_photos') . '/' . session('user')->profile_photo_path }}" class="avatar img-fluid rounded" />
        @else
            <img src="{{ asset('storage/profile_photos/default.png') }}" class="avatar img-fluid rounded" />
        @endif	
    </div>
        <div class="flex-grow-1 ps-2">
            <p class="text-white">{{session('user')->first_name}}</p>
            {{-- <div class="sidebar-user-subtitle">Designer</div> --}}
    </div>
</div>
@endsection
@section('content')

<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Media Manager</h1> 
        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#defaultModalSuccess">Upload</button>    
    </div>
    <div class="row">
        @foreach ($media as $value)
            @php
                $filePath = asset('storage/' . $value->path);
                $fileExtension = pathinfo($value->path, PATHINFO_EXTENSION);
            @endphp
    
            <div class="col-md-2 col-sm-6 col-12 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                            <!-- Show image preview -->
                            <img src="{{ $filePath }}" alt="Uploaded Media" class="img-fluid rounded mb-2">
                        @elseif($fileExtension === 'pdf')
                            <!-- Show PDF preview -->
                            <iframe src="{{ $filePath }}" width="100%" height="150px"></iframe>
                        @else
                            <!-- Show a generic file icon -->
                            <i class="fas fa-file fa-3x"></i>
                        @endif
                        
                        <div class="d-flex justify-content-between mt-2">
                            <a href="{{ $filePath }}" class="btn btn-primary btn-sm" download>Download</a>
                            
                            <form action="{{ route('admin/delete-media', $value->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>    
</div>

<div class="modal fade" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <form method="POST" action="{{route('admin/upload-media')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="inputEmail4">Document</label>
                        <input type="file" class="form-control" id="document" name="document[]" multiple required>
                        <small id="documentHelp" class="form-text text-muted">Only.pdf,.docx,.xlsx,.jpeg,.jpg,.png files are allowed.</small>
                    </div>
                        
                    <button type="submit" class="btn btn-success">Add</button>
                </form>
        </div>
    </div>
</div>
@endsection