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
           <h4 class="text-white">{{session('employee')->first_name}}</h4>
    </div>
</div>
@endsection

@section('content')
<style>
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0; 
    }
</style>
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Add Client Details</h1> 
    </div> 
</div>


<div class="row">
    <div class="col-12 col-lg-12">
        <div class="tab">
            <form action="{{route('emp/store-answer')}}" method="post">
                @csrf
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" href="#tab-1" data-bs-toggle="tab" role="tab">Lead Details</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab-2" data-bs-toggle="tab" role="tab">Basic Questions</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-1" role="tabpanel">
                        <h4 class="tab-title">Default tabs</h4>
                            @if(Session::has('success'))
                            <div class="alert alert-success">{{Session::get('success')}}</div>
                            @endif
                            @if(Session::has('error'))
                            <div class="alert alert-danger">{{Session::get('error')}}</div>
                            @endif
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="inputAddress">Full Name</label>
                                    <input type="text" class="form-control @error('fname') is-invalid @enderror" name="fname" id="fname" required value="{{old('fname')}}" placeholder="Full name">
                                    @error('fname')
                                    <p class="invalid-feedback">{{$message}}</p>
                                    @enderror
                                </div>
                                {{-- Company Name --}}
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="company_name">Company Name:</label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" placeholder="Company name" value="{{ old('company_name') }}">
                                    @error('company_name')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        
                    
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="phone">Phone:</label>
                                    <input type="number" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" required placeholder="Phone" value="{{ old('phone') }}">
                                    @error('phone')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="whatsappphone">WhatsApp No:</label>
                                    <input type="number" class="form-control @error('whatsappphone') is-invalid @enderror" id="whatsappphone" name="whatsappphone" placeholder="WhatsApp Phone" value="{{ old('whatsappphone') }}">
                                    <input type="checkbox" name="same_as_phone" class="mt-2" id="same_as_phone"> Same as phone
                                    @error('whatsappphone')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                                                        
            
                            <div class="mb-3">
                                <label class="form-label" for="address">What does the client do? </label>
                                <textarea class="form-control @error('service_detail') is-invalid @enderror" id="service_detail" name="service_detail" placeholder="service detail" >{{ old('service_detail') }}</textarea>
                                @error('service_detail')
                                <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
            
                            <div class="mb-3">
                                <label class="form-label" for="address">Address:</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Address" >{{ old('address') }}</textarea>
                                @error('address')
                                <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Services</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="services[]" value="SMM" id="smm">
                                    <label class="form-check-label" for="smm">Social Media Marketing</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="services[]" value="SEO" id="seo">
                                    <label class="form-check-label" for="seo">Search Engine Optimization</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="services[]" value="web-development" id="web-development">
                                    <label class="form-check-label" for="web-development">Web Development</label>
                                </div>
                            </div>                        
                            <button type="button" class="btn btn-primary btn-next">Next</button>
                    </div>
                    <div class="tab-pane" id="tab-2" role="tabpanel">
                        <h4 class="tab-title">Basic Questions</h4>
                        <div class="question-content">
                            <!-- Questions will be loaded here via AJAX -->
                        </div>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>                    
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>


    document.getElementById('same_as_phone').addEventListener('change', function() {
        let phoneInput = document.getElementById('phone');
        let whatsappInput = document.getElementById('whatsappphone');
        
        if (this.checked) {
            whatsappInput.value = phoneInput.value;
            whatsappInput.setAttribute('readonly', true); // Make it read-only
        } else {
            whatsappInput.value = '';
            whatsappInput.removeAttribute('readonly'); // Allow editing if unchecked
        }
    });

    document.getElementById('phone').addEventListener('input', function() {
        let sameAsPhone = document.getElementById('same_as_phone');
        let whatsappInput = document.getElementById('whatsappphone');
        
        if (sameAsPhone.checked) {
            whatsappInput.value = this.value;
        }
    });
                            
    $(document).ready(function () {
        $(".btn-next").click(function (e) {
            e.preventDefault();
            
            // Get checked services
            let selectedServices = [];
            $("input[name='services[]']:checked").each(function () {
                selectedServices.push($(this).val());
            });

            if (selectedServices.length === 0) {
                alert("Please select at least one service.");
                return;
            }

            $.ajax({
                url: "{{ route('emp/get-question') }}", 
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    services: selectedServices
                },
                success: function (response) {
                    if (response.status === "success") {
                        let questionHtml = "";
                        response.questions.forEach((question, index) => {
                            questionHtml += `
                                <div class="mb-3">
                                    <label class="form-label">${question.question}</label>
                                    <input type="text" class="form-control" name="questions[${question.id}]" value="">
                                </div>
                            `;
                        });

                        $("#tab-2 .tab-title").text("Service Questions");
                        $("#tab-2 .question-content").html(questionHtml);
                        $(".nav-tabs .nav-link[href='#tab-2']").tab("show");
                    }
                },
                error: function () {
                    alert("Error fetching questions. Please try again.");
                }
            });
        });
    });
</script>

@endsection