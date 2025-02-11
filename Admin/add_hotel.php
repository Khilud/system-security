<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
session_start();
require_once '../DBUtils.php'; // Ensure this file contains the database connection and insertion logic.

if (!isset($_SESSION['admin_username'])) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['returnButton'])) {
    header('Location: admin_dashboard.php');
    exit;
}

if (isset($_POST['viewAllButton'])) {
    header('Location: adminlistHotels.php');
    exit;
}

// Initialize confirmation message
$confirmationMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hotel_name']) && isset($_POST['hotel_address'])) {
    $hotelName = htmlspecialchars($_POST['hotel_name']);
    $hotelAddress = htmlspecialchars($_POST['hotel_address']);

    // Insert the hotel into the database
    try {
        $db = new DBConnection(); // Ensure your DBUtils.php has this class
        $result = $db->insertHotel($hotelName, $hotelAddress); // Replace with your actual insertion method
        
        if ($result) {
            $confirmationMessage = "Hotel '$hotelName' added successfully!";
        } else {
            $confirmationMessage = "Failed to add the hotel. Please try again.";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Add a Hotel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f7f7f7, #e3f2fd);
            color: #333;
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

        .alert {
            margin-top: 15px;
        }
    </style>
</head>
<body>
<div class="container text-center">
    <h5>Add a Hotel</h5>
    <?php if (!empty($confirmationMessage)): ?>
        <div class="alert alert-info">
            <?= $confirmationMessage; ?>
        </div>
    <?php endif; ?>
    <div id="addForm">
        <form method="post">
            <div class="mb-3">
                <label for="typeField" class="form-label">Name:</label>
                <input type="text" id="typeField" name="hotel_name" class="form-control" placeholder="Enter hotel name" required>
            </div>
            <div class="mb-3">
                <label for="severityField" class="form-label">Address:</label>
                <input type="text" id="severityField" name="hotel_address" class="form-control" placeholder="Enter hotel address" required>
            </div>
            <button id="insertLogButton" type="submit" class="btn btn-primary">Add Hotel</button>
        </form>
    </div>
    <form method="post" class="mt-4">
        <button type="submit" class="btn btn-secondary" name="viewAllButton">View All Hotels</button>
        <button type="submit" class="btn btn-primary" name="returnButton">Return to Main Page</button>
    </form>
    <div class="footer-text">
        <p>&copy; 2025 Hotel Management System. All rights reserved.</p>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>
</html>
