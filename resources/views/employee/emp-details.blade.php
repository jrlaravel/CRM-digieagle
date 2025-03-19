@extends('layout/employee-sidebar')
@section('profile')
    <div class="d-flex justify-content-center">
        <div class="flex-shrink-0">
            @if (session('employee') && session('employee')->profile_photo_path)
                <img src="{{ asset('storage/profile_photos') . '/' . session('employee')->profile_photo_path }}"
                    class="avatar img-fluid rounded" />
            @else
                <img src="{{ asset('storage/profile_photos/default.png') }}" class="avatar img-fluid rounded" />
            @endif
        </div>
        <div class="flex-grow-1 ps-2">
            <h4 class="text-white">{{ session('employee')->first_name }}</h4>
        </div>
    </div>
@endsection


@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title">Taken Leaves (Days)</h5>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3">{{ $appleave }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title">Remaining leaves (Days)</h5>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3">{{ $remainingleave }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title">Present Days (Month)</h5>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3">{{ $presentDaysCount }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title">Remaining Days (Month)</h5>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3">{{ substr($remainingDaysCount, 1) }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee Details Section -->
        <div class="row">
            <div class="col-md-6">
                <h3 class="mb-3">Employee Details</h3>
                <div class="card shadow-sm p-4">
                    <h4 class="text-muted">{{ $data->first_name }} {{ $data->last_name }}</h4>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-4">
                            <p class="text-muted font-weight-normal"><i class="fa fa-user"></i> UserName</p>
                            <p class="mb-0 font-weight-bold">{{ $data->username }}</p>
                        </div>
                        <div class="col-4">
                            <p class="text-muted font-weight-normal"><i class="fa fa-building" ></i>
                                Department & Designation
                            </p>
                            <p class="mb-0 font-weight-bold">{{ $data->depname }} & {{ $data->desname }}</p>
                        </div>
                        <div class="col-4">
                            <p class="text-muted font-weight-normal"><i class="fa fa-map-marker"></i> Address</p>
                            <p class="mb-0 font-weight-bold">{{ $data->address }}</p>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-4">
                            <p class="text-muted font-weight-normal"><i class="fa fa-calendar"></i> Birthday Date</p>
                            <span
                                class="font-weight-bold">{{ \Carbon\Carbon::parse($data->birth_Date)->format('d-m-Y') }}</span>
                        </div>
                        <div class="col-4">
                            <p class="text-muted font-weight-normal"><i class="fa fa-envelope"></i> Email</p>
                            <a href="mailto:{{ $data->email }} font-weight-bold"
                                style="color: #495057">{{ $data->email }}</a>
                        </div>
                        <div class="col-4">
                            <p class="text-muted font-weight-normal"><i class="fa fa-phone"></i> Contact Number</p>
                            <p class="mb-0 font-weight-bold">{{ $data->phone }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Report Section -->
            <div class="col-md-6">
                <h3 class="mb-3">Attendance Report <span style="font-size: 12px !important">(monthly)</span></h3>
                <div class="card shadow-sm p-4 d-flex align-items-center">
                    <canvas id="attendanceChart"
                        style="width: 250px !important; height: 250px !important; align-items: center;"></canvas>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="d-flex align-items-center">
                <h3 class="mb-3">Documents</h3>
                <button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#uploadModal">+ Add</button>
            </div>

            <div class="card p-3">
                <div class="row">
                    @foreach ($documents as $document)
                        @php
                            $fileId = $document['id']; // Get file ID
                            $filePath = asset('storage/' . $document['path']); // Get file path
                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                        @endphp

                        <div class="col-md-2 col-sm-6 col-12 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-body text-center">
                                    @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <!-- Show image preview -->
                                        <img src="{{ $filePath }}" alt="Uploaded Media" style="width: 100px; height:150px;" class="img-fluid rounded mb-2">
                                    @elseif($fileExtension === 'pdf')
                                        <!-- Show PDF preview -->
                                        <iframe src="{{ $filePath }}" width="100px" height="150px"></iframe>
                                    @else
                                        <!-- Show a generic file icon -->
                                        <i class="fas fa-file fa-3x text-muted"></i>
                                    @endif

                                    <div class="d-flex justify-content-between mt-2">
                                        <a href="{{ $filePath }}" class="btn btn-primary btn-sm" download>Download</a>

                                        @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                            <!-- View Button to Open Modal for Images -->
                                            <button class="btn btn-info btn-sm view-image" data-image="{{ $filePath }}"
                                                data-bs-toggle="modal" data-bs-target="#imageModal">
                                                View
                                            </button>
                                        @elseif($fileExtension === 'pdf')
                                            <!-- View Button to Open PDF in New Tab -->
                                            <a href="{{ $filePath }}" target="_blank" class="btn btn-info btn-sm">
                                                View
                                            </a>
                                        @endif


                                        {{-- <!-- Delete button -->
                                    <form action="{{ route('emp/delete-media', $fileId) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="modalImage" src="" class="img-fluid rounded" alt="Preview Image">
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery Script to Update Modal Image -->
        <script>
            $(document).ready(function() {
                $(".view-image").click(function() {
                    var imageUrl = $(this).data("image");
                    $("#modalImage").attr("src", imageUrl);
                });
            });
        </script>

    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('emp/emp-document') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="id" value="{{ $data->id }}">
                        <div class="mb-3">
                            <label for="imageUpload" class="form-label">Choose Image</label>
                            <input type="file" class="form-control" id="imageUpload" multiple name="images[]">
                        </div>
                        <button type="submit" class="btn btn-success">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var presentDays = {{ $presentDaysCount }}; // Get data from Laravel
            var absentDays = {{ $absentDaysCount }}; // Added missing "$"

            var ctx = document.getElementById('attendanceChart').getContext('2d');

            // Set custom canvas size
            ctx.canvas.width = 250;
            ctx.canvas.height = 250;

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Present days', 'Absent Days'],
                    datasets: [{
                        data: [presentDays, absentDays],
                        backgroundColor: ['#4CAF50', '#FF5733'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true, // Disable responsiveness to allow custom size
                    maintainAspectRatio: false, // Prevent automatic aspect ratio adjustment
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
@endsection
