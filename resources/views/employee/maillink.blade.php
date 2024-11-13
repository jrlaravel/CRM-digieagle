<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .email-body {
            padding: 20px;
            color: #333333;
            line-height: 1.5;
        }
        .email-body a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .email-footer {
            text-align: center;
            padding: 10px;
            background: #f4f4f4;
            font-size: 12px;
            color: #888888;
        }
        .reset-button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            color: #ffffff;
            background-color: #007bff;
            border-radius: 4px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
        }
        .reset-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h2>Reset Your Password</h2>
        </div>
        <div class="email-body">
            <p>Hi,</p>
            <p>It seems like you've requested to reset your password. Please click the button below to proceed:</p>
            <a href="{{route('emp/new-password', $token)}}" class="reset-button">Reset Password</a>
            <p>If you did not request this, you can safely ignore this email. Your password will remain unchanged.</p>
            <p>Thank you,<br>The Support Team</p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
