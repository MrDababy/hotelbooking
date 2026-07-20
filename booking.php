<?php

session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}


require 'database.php';

$db = new Database();
$conn = $db->connect();


$id = "";
$guest_name = "";
$phone = "";
$room_number = "";
$check_in = "";
$check_out = "";


// EDIT MODE

if(isset($_GET['id'])){

    $id = $_GET['id'];

    $stmt = $conn->prepare(
        "SELECT * FROM bookings WHERE id=?"
    );

    $stmt->execute([$id]);

    $booking = $stmt->fetch(PDO::FETCH_ASSOC);


    if($booking){

        $guest_name = $booking['guest_name'];
        $phone = $booking['phone'];
        $room_number = $booking['room_number'];
        $check_in = $booking['check_in'];
        $check_out = $booking['check_out'];

    }

}



// SAVE BOOKING

if(isset($_POST['save'])){


    $guest_name = $_POST['guest_name'];
    $phone = $_POST['phone'];
    $room_number = $_POST['room_number'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];



    if($_POST['id'] == ""){


        // ADD NEW BOOKING

        $insert = $conn->prepare(
            "INSERT INTO bookings
            (guest_name, phone, room_number, check_in, check_out)
            VALUES(?,?,?,?,?)"
        );


        $insert->execute([
            $guest_name,
            $phone,
            $room_number,
            $check_in,
            $check_out
        ]);


    }else{


        // UPDATE BOOKING

        $update = $conn->prepare(
            "UPDATE bookings SET
            guest_name=?,
            phone=?,
            room_number=?,
            check_in=?,
            check_out=?
            WHERE id=?"
        );


        $update->execute([
            $guest_name,
            $phone,
            $room_number,
            $check_in,
            $check_out,
            $_POST['id']
        ]);

    }



    header("Location: dashboard.php");
    exit();

}


?>


<!DOCTYPE html>
<html>

<head>

<title>
Booking
</title>

<link rel="stylesheet" href="style.css">

</head>


<body>

<div class="page-shell">
    <div class="hero-card">
        <div class="hero-content">
            <p class="eyebrow">Booking details</p>
            <h1>Hotel Booking</h1>
            <p class="hero-text">Fill in the guest and reservation information below.</p>
        </div>

        <form method="POST" class="booking-form">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

            <label>Guest Name</label>
            <input type="text" name="guest_name" value="<?php echo htmlspecialchars($guest_name); ?>" required>

            <label>Phone Number</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>

            <label>Room Number</label>
            <input type="text" name="room_number" value="<?php echo htmlspecialchars($room_number); ?>" required>

            <label>Check In</label>
            <input type="date" name="check_in" value="<?php echo htmlspecialchars($check_in); ?>" required>

            <label>Check Out</label>
            <input type="date" name="check_out" value="<?php echo htmlspecialchars($check_out); ?>" required>

            <button name="save">Save Booking</button>
        </form>

        <div class="form-link">
            <a href="dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</div>

</body>


</html>