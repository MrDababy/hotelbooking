
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'database.php';

$db = new Database();
$conn = $db->connect();

// ---------------- REGISTER ----------------
if (isset($_POST['register'])) {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($fullname != "" && $email != "" && $password != "") {

        $check = $conn->prepare("SELECT * FROM users WHERE email=?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {

            $message = "Email already exists.";

        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insert = $conn->prepare("INSERT INTO users(fullname,email,password) VALUES(?,?,?)");

            if ($insert->execute([$fullname,$email,$hashedPassword])) {
                $message = "Registration Successful. Please Login.";
            }
        }

    } else {

        $message = "All fields are required.";

    }

}

// ---------------- LOGIN ----------------

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $login = $conn->prepare("SELECT * FROM users WHERE email=?");
    $login->execute([$email]);

    if($login->rowCount() > 0){

        $user = $login->fetch(PDO::FETCH_ASSOC);

        if(password_verify($password,$user['password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];

            header("Location: dashboard.php");
            exit();

        }else{

            $message = "Incorrect Password.";

        }

    }else{

        $message = "User not found.";

    }

}

?>

<!DOCTYPE html>
<html>
<head>

<title>Hotel Booking System</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="page-shell">
    <div class="hero-card">
        <div class="hero-content">
            <p class="eyebrow">Luxury stays made easy</p>
            <h1>Hotel Booking System</h1>
            <p class="hero-text">Reserve your perfect room with a smooth and secure experience for guests and staff alike.</p>
        </div>

        <?php if(isset($message)){ echo "<p class='message'>$message</p>"; } ?>

        <div class="forms single-form">
            <div class="form-box">
                <h2>Welcome Back</h2>
                <form method="POST">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button name="login">Login</button>
                </form>

                <p class="switch-text">
                    New here? <a href="register.php">Create an account</a>
                </p>
            </div>
        </div>
    </div>
</div>

</body>

</html>