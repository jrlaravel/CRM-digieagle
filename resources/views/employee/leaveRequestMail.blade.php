<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .email-container {
            max-width: 600px;
            background-color: #ffffff;
            margin: 0 auto;
            border: 1px solid #dddddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            text-align: center;
            padding: 10px 0;
            border-bottom: 1px solid #eeeeee;
        }

        .email-header h2 {
            color: #007bff;
            font-size: 24px;
            margin: 0;
        }

        .email-content {
            padding: 20px 0;
        }

        .email-content p {
            margin: 0 0 10px;
            font-size: 16px;
        }

        .email-content ul {
            list-style-type: none;
            padding: 0;
        }

        .email-content ul li {
            margin: 5px 0;
            font-size: 16px;
        }

        .email-content ul li strong {
            color: #007bff;
        }

        .email-footer {
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
            text-align: center;
            font-size: 14px;
            color: #999;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 15px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h2>New Leave Request</h2>
        </div>
        <div class="email-content">
            <p>Hello HR,</p>
            <p>A new leave request has been submitted by <strong>{{ $leaveDetails['first_name'] }} {{ $leaveDetails['last_name'] }}</strong>, <em>{{ $leaveDetails['designation'] }}</em>.</p>

            <p><strong>Leave Details:</strong></p>
            <ul>
                <li><strong>Applied Date:</strong> {{ now()->format('d-m-Y') }}</li>
                <li><strong>From:</strong> {{ \Carbon\Carbon::parse($leaveDetails['start_date'])->format('d-m-Y') }}</li>
                <li><strong>To:</strong> {{ \Carbon\Carbon::parse($leaveDetails['end_date'])->format('d-m-Y') }}</li>
                <li><strong>Reason:</strong> {{ $leaveDetails['reason'] }}</li>
                <li><strong>Other Note:</strong> {{ $leaveDetails['other'] }}</li>
            </ul>

            <p>Please review and take necessary action from here <a href="{{route('admin/leave')}}">Click here</a> </p>
        </div>
        <div class="email-footer">
            <p>2024 Digieagleinc. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
