<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link class="js-stylesheet" href="{{ asset('css/light.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Rating - Form - DigieagleINC</title>
    <style>
        .star-rating {
            direction: rtl;
            display: inline-flex;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            color: #ddd;
            font-size: 24px;
            cursor: pointer;
            transition: color 0.2s;
        }

        .star-rating input[type="radio"]:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #f7c400;
        }
    </style>

</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card mt-3">
                    <div class="card-body">
                        <div
                            class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-center flex-wrap pt-3">
                            <img src="{{ asset('storage/logo/Digieagle-Favicon.png') }}" width="150px" alt="Logo">
                            <h3>Review Form</h3>
                            <h6>Date: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</h6>
                        </div>
                        <hr>
                        <form action="{{ route('add-review') }}" method="post">
                            @csrf
                            <div class="row mt-2">
                                <div class="mb-3">
                                    <label for="Name" class="form-label">Candidate Name:<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="Name" value="{{ $candidate }}" name="candidate_name"
                                        placeholder="Enter Name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="Name" class="form-label">Interviewer Name:<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="Name" name="interviewer_name"
                                        placeholder="Enter Name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label">What you learn from this Interview?<span
                                            class="text-danger">*</span></label>
                                    <textarea name="answer1" id="" class="form-control" placeholder="Enter Review"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label">Overall, how would you rate your interview
                                        experience?<span class="text-danger">*</span></label>
                                    <textarea name="answer2" id="" class="form-control" placeholder="Enter Review"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Give Rating:<span class="text-danger">*</span></label><br>
                                    <div class="star-rating">
                                        @for ($i = 5; $i >= 1; $i--)
                                            <input type="radio" id="star{{ $i }}" name="rate"
                                                value="{{ $i }}" required />
                                            <label for="star{{ $i }}" title="{{ $i }} stars"><i
                                                    class="fas fa-star"></i></label>
                                        @endfor
                                    </div>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" name="consent" id="consent" class="form-check-input" value="1" required>
                                    <label for="consent" class="form-check-label">
                                        I agree that my review may be used for showcasing or promotional purposes, and I have no objection to it.
                                    </label>
                                </div>
                                
                                
                            </div>

                            <button type="submit" class="btn btn-primary float-end"
                                style="width: 100px">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>
