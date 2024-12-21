<!DOCTYPE html>
<html>

<head>
    <title>Password Reset</title>
</head>

<body>
    <h1>Hello {{ $name }},</h1>

    <p>Your password has been reset. Here are your new credentials:</p>

    <p>Email: {{ $email }}</p>
    <p>New Password: {{ $password }}</p>

    <p>Please change your password after logging in.</p>

    <p>Best regards,<br>
        Your Application Team</p>
</body>

</html>