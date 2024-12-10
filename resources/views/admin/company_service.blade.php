@extends('layout/admin-sidebar')
@section('profile')
<div class="d-flex justify-content-center">
    <div class="flex-shrink-0">
        <img src="{{asset('storage/profile_photos').'/'.session('user')->profile_photo_path}}" class="avatar img-fluid rounded me-1"  />
    </div>
    <div class="flex-grow-1 ps-2">
            <p class="text-white">{{session('user')->first_name}}</p> 
        {{-- <div class="sidebar-user-subtitle">Designer</div> --}}
    </div>
</div>
@endsection
@section('content')
<style>
   
</style>
<div class="container-fluid p-0">

    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Company List</h1>   
        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#defaultModalSuccess">Add</button> 
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
                                    <th>Company Name</th>
                                    <th>Company Industry</th>
                                    <th>Company Description</th>
                                    <th>Serices Provide by Us</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $counter = 1; @endphp
                            @forEach($details as $item)
                                    <tr>
                                        <td>{{$counter}}</td>
                                        <td>{{$item->company_name}}</td>
                                        <td>{{$item->company_industry}}</td>
                                        <td>{{$item->company_description}}</td>
                                        <td>{{$item->services_provided}}</td>
                                        <td>
                                            <button class="btn btn-primary edit-lead" 
                                            data-id="{{ $item->company_id }}"
                                            data-name="{{ $item->company_name }}"
                                            data-description="{{ $item->company_description }}"
                                            data-service="{{ $item->services_provided }}"
                                            data-industry = "{{$item->company_industry}}"
                                            data-note = "{{$item->company_notes}}">
                                            Edit
                                        </button>
                                        <a href="{{route('admin/delete-company-service',$item->company_id)}}" class="btn btn-danger">Delete</a>
                                        </td>
                                        
                                        </td>
                                    </tr>   
                                    @php $counter++; @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Company Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="{{ route('admin/update-company-service') }}">
                    @csrf
                    <input type="hidden" id="company_id" name="company_id">

                    <div class="mb-3">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="company_name" name="company_name">
                    </div>

                    <div class="mb-3">
                        <label for="company_industry" class="form-label">Industry</label>
                        <input type="text" class="form-control" id="company_industry" name="company_industry">
                    </div>

                    <div class="mb-3">
                        <label for="company_description" class="form-label">Description</label>
                        <textarea class="form-control" id="company_description" name="company_description" rows="4"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="inputAddress">Services Provided by Us</label>
                        @foreach($services as $service)
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                value="{{$service->id}}" 
                                id="service-{{$service->id}}" 
                                name="services[]">
                            <label class="form-check-label" for="service-{{$service->id}}">
                                {{ $service->main_service }}
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <div class="mb-3">
                        <label for="company_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="company_notes" name="company_notes" rows="4"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <form method="POST" action="{{route('admin/add-company-service')}}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="inputAddress">Company Name</label>
                        <input type="text" class="form-control"  name="name" id="name" required placeholder="Enter Name">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="inputAddress">Company Industry</label>
                        <input type="text" class="form-control"  name="industry" id="industry" required placeholder="Enter Industry">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="inputAddress">Company Description</label>
                        <input type="text" class="form-control"  name="description" id="description" required placeholder="Enter Description">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="inputAddress">Services Provided by Us</label>
                        @foreach($services as $service)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{$service->id}}" id="service" name="services[]">
                            <label class="form-check-label" for="service">
                                {{ $service->main_service }}
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="inputAddress">Notes</label>
                        <input type="text" class="form-control"  name="note" id="note" required placeholder="Enter Note">
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Add</button>
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

    
    // Function to populate the modal with data
    $(document).on('click', '.edit-lead', function() {
    let Id = $(this).data('id');
    let name = $(this).data('name');
    let industry = $(this).data('industry');
    let services = $(this).data('service'); // Assuming this is a comma-separated string like "Facebook Story,Instagram Story"
    let description = $(this).data('description');
    let notes = $(this).data('note');

    // Log to ensure services data is correct
    console.log('Services:', services);

    // Set modal fields
    $('#company_id').val(Id);
    $('#company_name').val(name);
    $('#company_industry').val(industry);
    $('#company_description').val(description);
    $('#company_notes').val(notes);

    // Clear all checkboxes first
    $('.form-check-input').prop('checked', false);

    // Check the appropriate checkboxes
    if (services) {
        // Split services into an array
        let serviceArray = services.split(',').map(item => item.trim()); // Trim whitespace

        // Iterate through the array and check the corresponding checkboxes
        serviceArray.forEach(function(serviceName) {
            $('.form-check-input').each(function() {
                let checkboxLabel = $(this).siblings('label').text().trim(); // Get label text
                if (checkboxLabel === serviceName) {
                    $(this).prop('checked', true);
                }
            });
        });
    }

    $('#editModal').modal('show');
});



    document.addEventListener("DOMContentLoaded", function() {
        // Datatables Responsive
        $("#datatables-reponsive").DataTable({
            responsive: true
        });
    });

</script>
@endsection