<!DOCTYPE html>
<html>
<head>
    <title>Reset Your Password</title>
</head>
<body>
    <h1>Password Reset Request</h1>
    
    <p>Hello,</p>
    
    <p>You are receiving this email because we received a password reset request for your account.</p>
    
    <p>Click the link below to reset your password:</p>
    
    <a href="{{ $resetLink }}">Reset Password</a>
    
    <p>This password reset link will expire in 60 minutes.</p>
    
    <p>If you did not request a password reset, no further action is required.</p>
    
    <p>Thank you,</p>
    <p>Your Application Team</p>
</body>
</html>