<?php

session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}

require 'database.php';

$db = new Database();
$conn = $db->connect();


// Search bookings

$search = "";

if(isset($_GET['search'])){

    $search = $_GET['search'];

    $stmt = $conn->prepare(
        "SELECT * FROM bookings 
        WHERE guest_name LIKE ? 
        OR room_number LIKE ?"
    );

    $stmt->execute([
        "%$search%",
        "%$search%"
    ]);

}else{

    $stmt = $conn->prepare(
        "SELECT * FROM bookings ORDER BY id DESC"
    );

    $stmt->execute();

}


$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>


<!DOCTYPE html>
<html>

<head>

<title>Dashboard</title>

<link rel="stylesheet" href="style.css">

</head>


<body>

<div class="dashboard">
    <div class="dashboard-card">
        <div class="dashboard-header">
            <h1>Hotel Dashboard</h1>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?></p>
        </div>

        <div class="dashboard-actions">
            <a href="booking.php"><button type="button">Add New Booking</button></a>
            <a href="logout.php" class="logout-link"><button type="button">Logout</button></a>
        </div>

        <form method="GET" class="search-box">
            <input type="text" name="search" placeholder="Search Guest or Room" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Accommodations</button>
        </form>

        <table>
            <tr>
                <th>Guest Name</th>
                <th>Phone</th>
                <th>Room</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Actions</th>
            </tr>

            <?php foreach($bookings as $booking): ?>
            <tr>
                <td><?php echo htmlspecialchars($booking['guest_name']); ?></td>
                <td><?php echo htmlspecialchars($booking['phone']); ?></td>
                <td><?php echo htmlspecialchars($booking['room_number']); ?></td>
                <td><?php echo htmlspecialchars($booking['check_in']); ?></td>
                <td><?php echo htmlspecialchars($booking['check_out']); ?></td>
                <td>
                    <a href="booking.php?id=<?php echo $booking['id']; ?>">Edit</a>
                    |
                    <a href="delete.php?id=<?php echo $booking['id']; ?>" onclick="return confirm('Delete this booking?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

</body>

</html>