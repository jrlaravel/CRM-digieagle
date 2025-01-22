<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            background: linear-gradient(to right, #f4f7fc, #e0e4e8);
            margin: 0;
            padding: 30px;
        }

        .email-container {
            max-width: 650px;
            background-color: #ffffff;
            margin: 0 auto;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeIn 2s ease-in;
        }

        /* Add fade-in animation */
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        .email-header {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 25px;
            text-align: center;
            border-radius: 15px 15px 0 0;
        }

        .email-header h2 {
            font-size: 32px;
            margin: 0;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .email-content {
            padding: 25px;
        }

        .email-content p {
            margin: 0 0 18px;
            font-size: 17px;
            line-height: 1.8;
            color: #555;
        }

        .email-content strong {
            color: #333;
        }

        /* Slide-in animation for the leave details */
        @keyframes slideIn {
            0% {
                transform: translateX(-50px);
                opacity: 0;
            }
            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .leave-details {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            animation: slideIn 1.5s ease-in-out;
        }

        .leave-details ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .leave-details ul li {
            margin: 10px 0;
            font-size: 16px;
            color: #333;
        }

        .leave-details ul li strong {
            color: #007bff;
        }

        .btn {
            display: inline-block;
            padding: 12px 28px;
            margin-top: 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 18px;
            text-align: center;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }

        .email-footer {
            text-align: center;
            padding: 25px 0;
            font-size: 14px;
            color: #777;
            border-top: 1px solid #eee;
            margin-top: 30px;
        }

        .email-footer p {
            margin: 5px 0;
        }

        .email-footer a {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
        }

        .email-footer a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .email-container {
                width: 100% !important;
                padding: 15px;
            }

            .email-header h2 {
                font-size: 26px;
            }

            .email-content p {
                font-size: 15px;
            }

            .btn {
                font-size: 16px;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h2>Leave Request Submitted</h2>
        </div>

        <!-- Content -->
        <div class="email-content">
            <p>Dear Management,</p>
            <p>
                A new leave request has been submitted by <strong>{{ $leaveDetails['first_name'] }} {{ $leaveDetails['last_name'] }}</strong>, <em>{{ $leaveDetails['designation'] }}</em>.
            </p>

            <!-- Leave Details Section -->
            <div class="leave-details">
                <p><strong>Leave Details:</strong></p>
                <ul>
                    <li><strong>Applied Date:</strong> {{ now()->format('d-m-Y') }}</li>
                    <li><strong>From:</strong> {{ \Carbon\Carbon::parse($leaveDetails['start_date'])->format('d-m-Y') }}</li>
                    <li><strong>To:</strong> {{ \Carbon\Carbon::parse($leaveDetails['end_date'])->format('d-m-Y') }}</li>
                    <li><strong>Reason:</strong> {{ $leaveDetails['reason'] }}</li>
                    <li><strong>Other Note:</strong> {{ $leaveDetails['other'] }}</li>
                </ul>
            </div>
            <p> 
                You can reply to this email by clicking <a href="mailto:{{ $leaveDetails['email']}}">here</a>
            </p>
        </div>
        <!-- Footer -->
        <div class="email-footer">
            <p>© 2025 Digieagleinc. All rights reserved.</p>
            <p>
                <a href="https://digieagleinc.com/">Privacy Policy</a> | 
                <a href="https://digieagleinc.com/">Contact Us</a>
            </p>
        </div>
    </div>
</body>
</html>
