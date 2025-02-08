<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['admin_username'])) {
    header('Location: admin_login.php'); // Redirect to login if not an admin
    exit();
}

$admin_username = $_SESSION['admin_username'];

// Handle logout request
if (isset($_POST['logoutButton'])) {
    session_destroy(); // Destroy session
    header('Location: ../index.php'); // Redirect to index page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hotel Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .btn-custom {
            margin: 5px;
            width: 100%;
        }
        .dashboard-card {
            margin: 20px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-custom:hover {
            background-color: #0056b3;
            color: white;
        }
        .card-header {
            background-color: #343a40;
            color: white;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

<div class="container py-5">

    <!-- Welcome Section -->
    <div class="text-center">
        <h3>Welcome, <?php echo $admin_username; ?>!</h3>
        <p class="lead">Manage hotels, rooms, and reservations from the dashboard.</p>
    </div>

    <!-- Dashboard Cards -->
    <div class="row row-cols-1 row-cols-md-3">
        
        <!-- Hotels Card -->
        <div class="col mb-4">
            <div class="card dashboard-card">
                <div class="card-header text-center">Hotels</div>
                <div class="card-body">
                    <h5 class="card-title">Manage Hotels</h5>
                    <p class="card-text">Add, view, or edit hotel listings.</p>
                    <button class="btn btn-primary btn-custom" onclick="location.href='add_hotel.php'">Add Hotel</button>
                    <button class="btn btn-secondary btn-custom" onclick="location.href='listHotels.php'">View Hotels</button>
                </div>
            </div>
        </div>

        <!-- Rooms Card -->
        <div class="col mb-4">
            <div class="card dashboard-card">
                <div class="card-header text-center">Rooms</div>
                <div class="card-body">
                    <h5 class="card-title">Manage Rooms</h5>
                    <p class="card-text">Add, view, or edit room listings for your hotels.</p>
                    <button class="btn btn-primary btn-custom" onclick="location.href='add_room.php'">Add Room</button>
                    <button class="btn btn-secondary btn-custom" onclick="location.href='list_rooms.php'">View Rooms</button>
                </div>
            </div>
        </div>

        <!-- Reservations Card -->
        <div class="col mb-4">
            <div class="card dashboard-card">
                <div class="card-header text-center">Reservations</div>
                <div class="card-body">
                    <h5 class="card-title">Manage Reservations</h5>
                    <p class="card-text">Add, edit, or remove reservations.</p>
                    <button class="btn btn-warning btn-custom" onclick="location.href='reservations.php'">Add Reservation</button>
                    <button class="btn btn-warning btn-custom" onclick="location.href='editreservation.php'">Edit Reservation</button>
                
            </div>
        </div>
    </div>

    <!-- Logout Section -->
    <div class="text-center">
        <form method="post">
            <button type="submit" class="btn btn-danger" name="logoutButton">Log Out</button>
        </form>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
