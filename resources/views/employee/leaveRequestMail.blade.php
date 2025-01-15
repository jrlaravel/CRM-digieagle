<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            background: linear-gradient(to right, #f4f4f4, #e0e0e0);
            margin: 0;
            padding: 20px;
        }

        .email-container {
            max-width: 600px;
            background-color: #ffffff;
            margin: 0 auto;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .email-header {
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 12px 12px 0 0;
        }

        .email-header h2 {
            font-size: 28px;
            margin: 0;
        }

        .email-content {
            padding: 20px 30px;
        }

        .email-content p {
            margin: 0 0 15px;
            font-size: 16px;
            line-height: 1.8;
        }

        .leave-details {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .leave-details ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .leave-details ul li {
            margin: 10px 0;
            font-size: 16px;
        }

        .leave-details ul li strong {
            color: #007bff;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin-top: 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .email-footer {
            text-align: center;
            padding: 20px 0;
            font-size: 14px;
            color: #777;
            border-top: 1px solid #eee;
        }

        .email-footer p {
            margin: 5px 0;
        }

        .email-footer a {
            color: #007bff;
            text-decoration: none;
        }

        .email-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h2>New Leave Request</h2>
        </div>

        <!-- Content -->
        <div class="email-content">
            <p>Dear HR,</p>
            <p>
                A new leave request has been submitted by 
                <strong>{{ $leaveDetails['first_name'] }} {{ $leaveDetails['last_name'] }}</strong>, 
                <em>{{ $leaveDetails['designation'] }}</em>.
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

            <!-- Call-to-Action Button -->
            <p>
                Please review and take the necessary action by clicking the button below:
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>© 2025 Digieagleinc. All rights reserved.</p>
            <p>
                <a href="your-privacy-policy-link-here">Privacy Policy</a> | 
                <a href="your-contact-link-here">Contact Us</a>
            </p>
        </div>
    </div>
</body>
</html>
