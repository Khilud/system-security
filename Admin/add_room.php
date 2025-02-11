<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['admin_username'])) {
    header('Location: login.php');
    exit; // Important: Add exit after header redirect
}

if (isset($_POST['returnButton'])) {
    header('Location: admin_dashboard.php');
    exit;
}

if (isset($_POST['viewAllButton'])) {
    header('Location: adminlist_rooms.php');
    exit;
}

require_once '../DBUtils.php'; // Include your database utility file

$confirmationMessage = ""; // Initialize confirmation message

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hotel_id']) && isset($_POST['room_number']) && isset($_POST['category']) && isset($_POST['price'])) {
    $hotelId = htmlspecialchars($_POST['hotel_id']);
    $roomNumber = htmlspecialchars($_POST['room_number']);
    $category = htmlspecialchars($_POST['category']);
    $price = htmlspecialchars($_POST['price']);

    try {
        $db = new DBConnection(); // Create your database connection object
        $result = $db->insertRoom($hotelId, $roomNumber, $category, $price); // Call the insert function

        if ($result) {
            $confirmationMessage = "Room added successfully!";
        } else {
            $confirmationMessage = "Failed to add the room. Please try again.";
        }
    } catch (Exception $e) {
        $confirmationMessage = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add a Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f0f9ff, #c4e0e5);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: #ffffff;
            border-radius: 10px;
            padding: 30px;
            max-width: 500px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        h5 {
            font-size: 1.8rem;
            color: #007bff;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-label {
            font-weight: bold;
            color: #333;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            width: 100%;
            margin: 10px 0;
            font-size: 1rem;
            padding: 10px;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            width: 100%;
            margin: 10px 0;
            font-size: 1rem;
            padding: 10px;
            border-radius: 5px;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .form-control {
            border-radius: 5px;
            font-size: 1rem;
            padding: 10px;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #555;
        }
    </style>
</head>
<body>
<div class="container">
    <h5>Add a Room</h5>
    <form method="post" id="addRoomForm">
        <div class="mb-3">
            <label for="typeField" class="form-label">Hotel ID:</label>
            <input type="text" id="typeField" name="hotel_id" class="form-control" placeholder="Enter hotel ID" required>
        </div>
        <div class="mb-3">
            <label for="severityField" class="form-label">Room Number:</label>
            <input type="text" id="severityField" name="room_number" class="form-control" placeholder="Enter room number" required>
        </div>
        <div class="mb-3">
            <label for="typeField1" class="form-label">Category:</label>
            <input type="text" id="typeField1" name="category" class="form-control" placeholder="Enter room category (e.g., Single, Double)" required>
        </div>
        <div class="mb-3">
            <label for="severityField1" class="form-label">Price:</label>
            <input type="text" id="severityField1" name="price" class="form-control" placeholder="Enter room price" required>
        </div>
        <button type="button" id="insertLogButton" class="btn btn-primary">Add Room</button>
    </form>
    <form method="post" class="mt-3">
        <button type="submit" class="btn btn-secondary" name="viewAllButton">View All Rooms</button>
        <button type="submit" class="btn btn-primary" name="returnButton">Return to Main Page</button>
    </form>
    <div class="footer-text">
        <p>&copy; 2025 Hotel Management System. All rights reserved.</p>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="add_script_rooms.js"></script>
</body>
</html>
