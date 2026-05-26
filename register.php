<?php
// 1. Database Connection Settings (Configured for your Workbench Instance)
$host = "127.0.0.1";
$dbUser = "root";
$dbPassword = "12345"; 
$dbName = "login_register";
$port = 3306; 

$conn = mysqli_connect($host, $dbUser, $dbPassword, $dbName, $port);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// 2. Listen for Form Submission
$errors = [];
$successMessage = "";

if (isset($_POST['register'])) {
    $fullName = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // 3. Simple Form Validation
    if (empty($fullName) || empty($email) || empty($password) || empty($confirmPassword)) {
        $errors[] = "All fields are required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email is not valid.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // 4. Check if Email Already Exists
    if (empty($errors)) {
        $sqlCheck = "SELECT * FROM users WHERE email = ?";
        $stmtCheck = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmtCheck, $sqlCheck)) {
            mysqli_stmt_bind_param($stmtCheck, "s", $email);
            mysqli_stmt_execute($stmtCheck);
            $result = mysqli_stmt_get_result($stmtCheck);
            if (mysqli_num_rows($result) > 0) {
                $errors[] = "Email already registered.";
            }
        }
    }

    // 5. Insert New User into MySQL Workbench Database
    if (empty($errors)) {
        // Secure password hashing
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sqlInsert = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
        $stmtInsert = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmtInsert, $sqlInsert)) {
            mysqli_stmt_bind_param($stmtInsert, "sss", $fullName, $email, $passwordHash);
            mysqli_stmt_execute($stmtInsert);
            $successMessage = "You are registered successfully!";
        } else {
            $errors[] = "Something went wrong with the database entry.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">

        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        }
        if (!empty($successMessage)) {
            echo "<div class='alert alert-success'>$successMessage</div>";
        }
        ?>

        <h2>Register</h2>
        <form action="register.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Full Name">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="register">
            </div>
        </form>

    </div>
    
</body>
</html>