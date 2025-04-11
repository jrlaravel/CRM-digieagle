<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link class="js-stylesheet" href="{{ asset('css/light.css') }}" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                    <div
                        class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-center text-center text-sm-start flex-wrap">
                        <img src="{{ asset('storage/logo/Digieagle-Favicon.png') }}" class="mb-3 mb-sm-0" width="150px"
                            alt="Logo">
                        <h3 class="mb-0">Applicant's Interview Form</h3>
                        <h6 class="mb-0 mt-3 mt-sm-0">Date: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</h6>
                    </div>
                        <form action="{{ route('add-candidate-data') }}" method="post">
                            @csrf
                            @if (Session::has('error'))
                                <div class="alert alert-danger">{{ Session::get('error') }}</div>
                            @endif
                            <!-- Personal Information -->
                            <input type="hidden" name="name" value="{{ $token }}" id="">

                            <div class="row">
                                <div class="mb-3 col-md-4">
                                    <div class="mb-3">
                                        <label for="Name">Candidate Name:<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="Name" name="name"
                                            placeholder="Enter Name" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <div class="mb-3">
                                        <label for="Email">Email:<span class="text-danger">*</span> </label>
                                        <input type="email" class="form-control" id="Email" name="email"
                                            placeholder="Enter Email" required>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <div class="mb-3">
                                        <label for="Phone">Phone:<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="Phone" name="phone"
                                            maxlength="10" placeholder="Enter Phone" required>
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <label for="Phone">Address:<span class="text-danger">*</span> </label>
                                    <textarea name="address" class="form-control" id="" cols="20" rows="5" placeholder="Enter Address"
                                        required></textarea>
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="=">designation Applying For:<span
                                            class="text-danger">*</span></label>
                                    <select name="designation" class="form-control">
                                        <option value="">Select Designation</option>
                                        <option value="Front Developer">Front Developer</option>
                                        <option value="UI/UX Designer">UI/UX Designer</option>
                                        <option value="Backend Developer">Backend Developer</option>
                                        <option value="Graphic Designer">Graphic Designer</option>
                                        <option value="Motion Designer">Motion Designer</option>
                                        <option value="Wordpress developer">Wordpress developer</option>
                                        <option value="SEO Executive">SEO Executive</option>
                                        <option value="Content Writer">Full Stack Developer</option>
                                        <option value="Social Media Executive">Social Media Executive</option>
                                        <option value="HR Executive">HR Executive</option>
                                        <option value="brand strategist">brand strategist</option>
                                        <option value="Digital Marketer">Digital Marketer</option>
                                    </select>
                                    @error('designation')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="experience">Total Work Experience:<span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="0.1" class="form-control" id="experience"
                                        name="experience" placeholder="Enter work experience" required>
                                    <span class="text-danger">Please enter a valid numerical value for Example -
                                        1.5</span>
                                    @error('experience')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="">Reference Name (If any):</label>
                                    <input type="text" class="form-control" id="reference_name"
                                        name="reference_name" placeholder="Enter reference name">
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="">Reference Phone (If any):</label>
                                    <input type="number" class="form-control" id="reference_phone" max="10"
                                        name="reference_phone" placeholder="Enter reference phone">
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="">Current / last Organization Name:<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="organization_name"
                                        name="organization_name" placeholder="Enter Organization" required>
                                    @error('organization_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="">Current / Last Position:<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="position_name"
                                        name="position_name" placeholder="Enter Position" required>
                                    @error('position_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="">Notice Period:<span class="text-danger">*</span></label>
                                    <select class="form-control" id="notice_period" name="notice_period">
                                        <option value="">Select Period</option>
                                        <option value="0-1 Month">0-1 Month</option>
                                        <option value="1-2 Months">1-2 Months</option>
                                        <option value="Above 2 Months">Above 2 Months</option>
                                    </select>
                                    @error('notice_period')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="">Expected Date of Joining:<span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="expected_date"
                                        name="expected_date" required>
                                    @error('expected_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="">Current CTC (LPA):<span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" id="current_ctc"
                                        name="current_ctc" placeholder="Enter current ctc" required>
                                    @error('current_ctc')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="">Expected CTC (LPA):<span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" id="expected_ctc"
                                        name="expected_ctc" placeholder="Enter expected ctc" required>
                                    @error('expected_ctc')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="">Your Strengths:</label>
                                    <textarea name="strengths" id="" cols="30" rows="10" class="form-control"
                                        placeholder="Enter strength"></textarea>
                                    @error('strengths')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="">Your Weaknesses:</label>
                                    <textarea name="weaknesses" id="" cols="30" rows="10" class="form-control"
                                        placeholder="Enter weakness"></textarea>
                                    @error('weaknesses')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="mb-3 col-md-12 text-center">
                                    <p>Answer This Questions</p>
                                    <h3>Because We Want You to <br>
                                        Be a Real Superhero in Every Situation 😎</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <label for="">1. What is your long-term career goal ?<span
                                            class="text-danger">*</span></label>
                                    <textarea name="career_goal" id="" cols="30" rows="5" class="form-control" required
                                        placeholder="Enter career goal"></textarea>
                                    @error('career_goal')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-12">
                                    <label for="">2. Describe your present position's responsibilities?<span
                                            class="text-danger">*</span></label>
                                    <textarea name="position_responsibilities" id="" cols="30" rows="5" class="form-control"
                                        required placeholder="Enter position responsibilities"></textarea>
                                    @error('position_responsibilities')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-12">
                                    <label for="">3. What are your key skills and areas of expertise?<span
                                            class="text-danger">*</span></label>
                                    <textarea name="areas_of_expertise" id="" cols="30" rows="5" class="form-control" required
                                        placeholder="Enter areas of expertise"></textarea>
                                    @error('areas_of_expertise')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-12">
                                    <label for="">4. What do you do to improve your knowledge?<span
                                            class="text-danger">*</span></label>
                                    <textarea name="improve_your_knowledge" id="" cols="30" rows="5" class="form-control" required
                                        placeholder="Enter What do you do to improve your knowledge"></textarea>
                                    @error('improve_your_knowledge')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label for="">5. Are you familiar with our organization ? What kind of
                                        service are we providing?<span class="text-danger">*</span></label>
                                    <textarea name="service_are_we_providing" id="" cols="30" rows="5" class="form-control"
                                        required placeholder="Enter service are we providing"></textarea>
                                    @error('service_are_we_providing')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label for="">6. What is the reason for leaving the current / last
                                        organization?<span class="text-danger">*</span></label>
                                    <textarea name="reason_for_leaving" id="" cols="30" rows="5" class="form-control" required
                                        placeholder="Enter reason for leaving"></textarea>
                                    @error('reason_for_leaving')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label for="">7. Why are you applying for this position?<span
                                            class="text-danger">*</span></label>
                                    <textarea name="reason_for_applying" id="" cols="30" rows="5" class="form-control" required
                                        placeholder="Enter reason for applying"></textarea>
                                    @error('reason_for_applying')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" style="width: 100px">Submit</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>
