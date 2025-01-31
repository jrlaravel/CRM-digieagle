<!DOCTYPE html>
<html>
<head>
    <title>Interview Scheduled</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #FAF3E0;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            background: #ffffff;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #9D4EDD;
        }
        .header {
            background: linear-gradient(90deg, #9D4EDD, #7B2CBF);
            color: white;
            text-align: center;
            padding: 15px;
            border-radius: 5px 5px 0 0;
            font-size: 22px;
            font-weight: bold;
        }
        .content {
            padding: 20px;
            font-size: 16px;
            color: #333;
        }
        .content p {
            margin: 10px 0;
        }
        .content strong {
            color: #5A189A;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 14px;
            color: #555;
            border-top: 1px solid #ddd;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="email-container">
        <div class="header">
            Interview Scheduled 
        </div>
        
        <div class="content">
            <p><strong>Candidate Name:</strong> {{ $candidateName }}</p>
            <p><strong>Interview Type:</strong> {{ $interviewType }}</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($interviewDate->format('d-m-Y')) }}</p>
            <p><strong>Time:</strong> {{ $interviewTime }}</p>
        </div>

        <div class="footer">
            <p><em>This is an automated email. Do not reply.</em></p>
        </div>
    </div>

</body>
</html>
