<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approver Login Dashboard</title>
</head>
<body>
   <h2>Approver Login</h2>

<a href="/login/google" style="padding:10px;background:red;color:white;text-decoration:none;">
    Login with Google
</a>

@if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif
</body>
</html> 