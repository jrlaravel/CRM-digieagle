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
        <h1 class="h3 d-inline align-middle">Candidate List</h1> 
        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#defaultModalSuccess">Generate Link</button>    
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
                                    <th>Candidate Name</th>
                                    <th>Link</th>  
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($candidates) && count($candidates) > 0)
                                    @foreach($candidates as $key => $candidate)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{$candidate->name }}</td>
                                            <td>
                                                <span id="link-{{ $candidate->id }}">{{ $candidate->link }}</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-success copy-btn"
                                                        onclick="copyToClipboard('{{ $candidate->id }}', this)">
                                                        Copy
                                                    </button>
                                                <a href="{{route('admin/delete-link',$candidate->id)}}" class="btn btn-danger">Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
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
                <h5 class="modal-title" id="modalTitle">Add Candidate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <form method="POST" action="{{route('generate-link')}}" id="linkgenerate">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="cardName">Candidate Name</label>
                        <input type="text" class="form-control" name="name" id="leaveTypeName" required placeholder="Enter name">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="submitBtn">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    
<script>
    function copyToClipboard(candidateId, button) {
        let linkElement = document.getElementById(`link-${candidateId}`);
        let tempInput = document.createElement("input");
        tempInput.value = linkElement.innerText;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);

        // Change button text to "Copied!"
        button.innerText = "Copied!";
        button.disabled = true;

        // Revert back to "Copy" after 2 seconds
        setTimeout(() => {
            button.innerText = "Copy";
            button.disabled = false;
        }, 5000);
    }
</script>
@endsection