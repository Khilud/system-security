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
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f7f7f7, #e9ecef); /* Subtle gradient */
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: auto;
        }
        .dashboard-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .dashboard-header h3 {
            font-size: 2rem;
            font-weight: bold;
        }
        .dashboard-header p {
            font-size: 1.2rem;
            color: #666;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background-color: #343a40;
            color: white;
            font-size: 1.2rem;
            text-align: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .btn-custom {
            margin: 10px 0;
            padding: 10px 20px;
            width: 100%;
            font-size: 1rem;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .logout-section {
            margin-top: 40px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Welcome Section -->
    <div class="dashboard-header">
        <h3>Welcome, <?= htmlspecialchars($admin_username); ?>!</h3>
        <p>Manage hotels, rooms, and reservations with ease.</p>
    </div>

    <!-- Dashboard Cards -->
    <div class="row g-4">
        
        <!-- Hotels Card -->
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-header">Hotels</div>
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Hotels</h5>
                    <p class="card-text">Add, view, or edit hotel listings.</p>
                    <button class="btn btn-primary btn-custom" onclick="location.href='add_hotel.php'">Add Hotel</button>
                    <button class="btn btn-secondary btn-custom" onclick="location.href='adminlistHotels.php'">View Hotels</button>
                </div>
            </div>
        </div>

        <!-- Rooms Card -->
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-header">Rooms</div>
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Rooms</h5>
                    <p class="card-text">Add, view, or edit room listings for your hotels.</p>
                    <button class="btn btn-primary btn-custom" onclick="location.href='add_room.php'">Add Room</button>
                    <button class="btn btn-secondary btn-custom" onclick="location.href='adminlist_rooms.php'">View Rooms</button>
                </div>
            </div>
        </div>

        <!-- Reservations Card -->
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-header">Reservations</div>
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Reservations</h5>
                    <p class="card-text">Add, edit, or remove reservations.</p>
                    <button class="btn btn-primary btn-custom" onclick="location.href='reservations.php'">View Reservations</button>
                    <button class="btn btn-secondary btn-custom" onclick="location.href='editreservation.php'">Edit Reservations</button>
                </div>
            </div>
        </div>

    </div>

    <!-- Logout Section -->
    <div class="logout-section">
        <form method="post">
            <button type="submit" class="btn btn-danger" name="logoutButton">Log Out</button>
        </form>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
