<?php

session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}


require 'database.php';


$db = new Database();
$conn = $db->connect();



if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];
    $delete = $conn->prepare("DELETE FROM bookings WHERE id=?");
    $delete->execute([$id]);
}

$_SESSION['message'] = "Booking deleted successfully.";

echo "<!DOCTYPE html>
<html>
<head>
    <title>Booking Deleted</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4fff7; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .box { background: white; padding: 24px 30px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.12); text-align: center; }
        a { color: #167a3d; font-weight: bold; text-decoration: none; }
    </style>
</head>
<body>
    <div class='box'>
        <h2>Booking deleted successfully.</h2>
        <p><a href='dashboard.php'>Go back to dashboard</a></p>
    </div>
</body>
</html>";
exit();

?>