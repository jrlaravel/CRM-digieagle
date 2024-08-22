<!DOCTYPE html>
<html>
<head>
    <title>Upcoming Employee Birthday Reminder</title>
</head>
<body>
    <h1>Reminder: Upcoming Employee Birthday</h1>
    <p>Dear Admin,</p>
    <p>This is a friendly reminder that {{ $user->first_name }}'s birthday is coming up on {{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('F j') : 'a date we don\'t have' }}.</p>
    <p>Please take a moment to prepare a special message or celebration for them.</p>
    <p>Best regards,<br>Your Team</p>
</body>
</html>
