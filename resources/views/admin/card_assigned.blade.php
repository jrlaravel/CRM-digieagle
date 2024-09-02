<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Assigned</title>
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
        }
        .email-body p {
            margin: 0 0 10px;
        }
        .card-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            border-left-width: 4px;
            border-left-style: solid;
            font-size: 20px;
        }
        .card-info strong {
            display: block;
            font-size: 22px;
            margin-top: 10px;
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
        <div class="email-header" style="background-color: 
            @if($card->name == 'red card')
                #ff4d4d;
            @elseif($card->name == 'green card')
                #4CAF50;
            @elseif($card->name == 'yellow card')
                #ffeb3b;
                color: #333;
            @elseif($card->name == 'black card')
                #ffffff;
            @else
                #4CAF50;
            @endif">
            <h1>Card Assigned</h1>
        </div>
        <div class="email-body">
            <h2>Hello, {{ $employee->first_name }}</h2>
            <p>We're excited to let you know that you have been assigned a new card.</p>
            <div class="card-info" style="border-left-color: 
            @if($card->name == 'red card')
                #ff4d4d;
            @elseif($card->name == 'green card')
                #4CAF50;
            @elseif($card->name == 'yellow card')
                #ffeb3b;
            @elseif($card->name == 'black card')
                #ffffff;
            @else
                #4CAF50;
            @endif">
                <strong>Card:</strong> {{ $card->name }}
                <strong>Message:</strong> {{ $messageContent }}
                <strong>Date:</strong> {{ \Carbon\Carbon::now()->toFormattedDateString() }}
            </div>
            <p style="margin-top: 15px">We hope this brings you motivation and joy! Thank you for being part of our team.</p>
        </div>
        <div class="email-footer">
            <p>&copy; 2024 Your Company. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
