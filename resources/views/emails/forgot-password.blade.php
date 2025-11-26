<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
</head>
<body>
    <p>Hello,</p>

    <p>You requested to reset your password for your PDAO account.</p>

    <p>
        Click the link below to choose a new password:
    </p>

    <p>
        <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
    </p>

    <p>This link will expire in 60 minutes.</p>

    <p>If you didn't request this, you can safely ignore this email.</p>

    <p>Thank you,<br>PDAO System</p>
</body>
</html>
