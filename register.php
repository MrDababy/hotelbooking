<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'database.php';

$db = new Database();
$conn = $db->connect();

$message = '';

if (isset($_POST['register'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($fullname != '' && $email != '' && $password != '') {
        $check = $conn->prepare("SELECT * FROM users WHERE email=?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {
            $message = "Email already exists.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users(fullname,email,password) VALUES(?,?,?)");

            if ($insert->execute([$fullname, $email, $hashedPassword])) {
                $message = "Registration successful. You can now login.";
            }
        }
    } else {
        $message = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Account</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page-shell">
        <div class="hero-card">
            <div class="hero-content">
                <p class="eyebrow">Join the hotel</p>
                <h1>Create Account</h1>
                <p class="hero-text">Register to start managing your stay and bookings.</p>
            </div>

            <?php if ($message != '') { echo "<p class='message'>$message</p>"; } ?>

            <div class="forms single-form">
                <div class="form-box">
                    <h2>Register</h2>
                    <form method="POST">
                        <input type="text" name="fullname" placeholder="Full Name" required>
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <button name="register">Register</button>
                    </form>

                    <p class="switch-text">
                        Already have an account? <a href="index.php">Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
