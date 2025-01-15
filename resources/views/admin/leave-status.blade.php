<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Status Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background-color: #007bff;
            padding: 25px;
            text-align: center;
            color: white;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .email-header h1 {
            margin: 0;
            font-size: 26px;
        }
        .email-body {
            padding: 20px;
            line-height: 1.6;
        }
        .email-body h2 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #007bff;
        }
        .email-body p {
            margin: 0 0 15px;
            font-size: 16px;
        }
        .status-box {
            padding: 20px;
            background-color: #f8f9fa;
            border-left: 5px solid;
            border-radius: 5px;
            margin: 25px 0;
        }
        .status-box strong {
            font-size: 18px;
            font-weight: bold;
        }
        .status-box.approved {
            border-color: #28a745;
            color: #28a745;
        }
        .status-box.rejected {
            border-color: #dc3545;
            color: #dc3545;
        }
        .email-footer {
            background-color: #f4f4f4;
            padding: 15px;
            text-align: center;
            font-size: 14px;
            color: #777;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
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
            <h1>Leave Status Update</h1>
        </div>

        <!-- Body Content -->
        <div class="email-body">
            <h2>Hello, {{ $employeeName }}</h2>
            <p>Your leave request has been processed successfully.</p>

            <!-- Status Box -->
            <div class="status-box {{ $leaveStatus == 'approved' ? 'approved' : 'rejected' }}">
                <p>Your leave from <strong>{{ $startDate }}</strong> to <strong>{{ $endDate }}</strong> has been <strong>{{ ucfirst($leaveStatus) }}</strong>.</p>
                @if ($leaveStatus == 'rejected' && isset($rejectionReason))
                    <p><strong>Rejection Reason:</strong> {{ $rejectionReason }}</p>
                @endif
            </div>

            <p>If you have any questions or concerns, please feel free to contact HR.</p>
            <p>Thank you!</p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>&copy; 2024 Digieagleinc. All rights reserved. <br> <a href="mailto:hr@digieagleinc.com">Contact HR</a></p>
        </div>
    </div>
</body>
</html>
