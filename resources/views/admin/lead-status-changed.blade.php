<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead Status Changed</title>
    <style>
        /* Email body styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        
        /* Container */
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        /* Header styling */
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        /* Content styling */
        .content {
            padding: 20px;
            color: #333;
        }

        .content p {
            font-size: 16px;
            line-height: 1.6;
        }

        .content strong {
            font-weight: bold;
        }

        /* Button styling */
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }

        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Lead Status Changed</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p>The status of the lead <strong>{{ $lead->title }}</strong> has been changed.</p>
            <p><strong>Previous Status:</strong> {{ $previousStatus }}</p>
            <p><strong>New Status:</strong> {{ $newStatus }}</p>
            <p><strong>Follow-up Message:</strong> {{ $followupMessage }}</p>
            <p><strong>Company Name:</strong> {{ $companyName }}</p>

            <!-- Optional action button -->
            <a href="#" class="button">View Lead Details</a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for using our service!</p>
            <p>If you have any questions, please contact us at support@example.com.</p>
        </div>
    </div>
</body>
</html>
