<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form action="registration.php" method="post">
            <div class="form-group">
                <input type="text" name="fullname" placeholder="Full Name">
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <input type="password" name="confirm_password" placeholder="Confirm Password">
            </div>
            <div class="form-group">
                <input type="submit" value="Register" name="register">
            </div>
        </form>

    </div>
    
</body>
</html>