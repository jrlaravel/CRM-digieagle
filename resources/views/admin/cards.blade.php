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

    </div>
</div>
@endsection
@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Card List</h1> 
        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#defaultModalSuccess">Add</button>    
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Card Name</th>
                                <th>Description</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $card) 
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{ $card->name }}</td>
                                <td>{{ $card->description }}</td>
                                <td>
                                    <img src="{{asset('storage/cards').'/'. $card->image}}" alt="Card Image" style="width: 100px; height: 100px;">
                                </td>
                                <td>
                                    <a href="#" class="btn btn-primary edit-card-btn" data-id="{{ $card->id }}" data-name="{{ $card->name }}" data-description="{{ $card->description }}" data-image="{{ $card->image }}">Edit</a>
                                    <a href="{{route('admin/delete-card',$card->id)}}" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>  
</div>

<div class="modal fade" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Card</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <form method="POST" action="{{ route('admin/add-card') }}" id="cardForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="cardName">Card Name</label>
                        <input type="text" class="form-control" name="name" id="cardName" required placeholder="Enter name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="cardDescription">Card Description</label>
                        <input type="text" class="form-control" name="description" id="cardDescription" required placeholder="Enter Description">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="cardImage">Card Image</label>
                        <input type="file" class="form-control" name="image" id="cardImage" placeholder="Upload Image">
                    </div>
                    <input type="hidden" name="card_id" id="cardId">
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
    $(document).ready(function() {
    $('.edit-card-btn').click(function() {
        var cardId = $(this).data('id');
        var cardName = $(this).data('name');
        var cardDescription = $(this).data('description');

        // Update the modal fields
        $('#cardId').val(cardId);
        $('#cardName').val(cardName);
        $('#cardDescription').val(cardDescription);

        // Change the form action to update if editing
        $('#cardForm').attr('action', '{{ route("admin/update-card") }}');

        // Change the modal title and button text
        $('#modalTitle').text('Update Card');
        $('#submitBtn').text('Update');

        // Open the modal
        $('#defaultModalSuccess').modal('show');
    });

    $('#defaultModalSuccess').on('hidden.bs.modal', function () {
        // Reset the modal to default state for adding a new card
        $('#cardForm').attr('action', '{{ route("admin/add-card") }}');
        $('#modalTitle').text('Add Card');
        $('#submitBtn').text('Add');
        $('#cardForm')[0].reset();  // Clear the form
        $('#cardId').val('');  // Clear the hidden input
    });
});

</script>


@endsection