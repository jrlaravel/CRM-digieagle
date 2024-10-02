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
            padding: 20px;
            text-align: center;
            color: white;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 20px;
            line-height: 1.6;
        }
        .email-body h2 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #007bff;
        }
        .email-body p {
            margin: 0 0 10px;
        }
        .status-box {
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid;
            border-color: {{ $leaveStatus == 'approved' ? '#28a745' : '#dc3545' }};
            border-radius: 5px;
            margin: 20px 0;
        }
        .status-box strong {
            color: {{ $leaveStatus == 'approved' ? '#28a745' : '#dc3545' }};
        }
        .email-footer {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Leave Status Update</h1>
        </div>
        <div class="email-body">
            <h2>Hello, {{ $employeeName }}</h2>
            <p>Your leave request has been processed.</p>
            <div class="status-box">
                <p>Your leave from <strong>{{ $startDate }}</strong> to <strong>{{ $endDate }}</strong> has been <strong>{{ $leaveStatus }}</strong>.</p>
                @if ($leaveStatus == 'rejected' && isset($rejectionReason))
                    <p><strong>Rejection Reason:</strong> {{ $rejectionReason }}</p>
                @endif
            </div>
            <p>If you have any questions or concerns, please feel free to contact HR.</p>
            <p>Thank you!</p>
        </div>
        <div class="email-footer">
            <p>&copy; 2024 Digieagleinc. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
