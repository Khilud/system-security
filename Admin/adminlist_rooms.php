<?php
session_start();
require_once '../DBUtils.php';

// Redirect unauthenticated admins
if (!isset($_SESSION['admin_username'])) {
    header('Location: ../login.php');
    die();
}

$username = $_SESSION['admin_username']; // Admin username from session

// Handle return button
if (isset($_POST['returnButton'])) {
    header('Location: admin_dashboard.php');
    exit;
}

$db = new DBConnection(); // Database connection

// Fetch all rooms from the database
$rooms = $db->getAllRooms(); // Assumes `getAllRooms()` function exists in DBUtils.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Rooms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    body {
        background: linear-gradient(135deg, rgba(0, 51, 102, 0.7), rgba(0, 76, 153, 0.8)); /* Dark Blue gradient */
        font-family: 'Arial', sans-serif;
        min-height: 100vh;
        margin: 0;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .container {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
        max-width: 900px;
        width: 100%;
    }

    h3 {
        font-size: 2rem;
        font-weight: bold;
        color: #003366; /* Navy Blue */
        text-align: center;
        margin-bottom: 20px;
    }

    .table {
        margin-bottom: 20px;
    }

    .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: #e6f0ff; /* Light Blue */
    }

    th {
        background-color: #003366; /* Navy Blue */
        color: #ffffff;
    }

    .btn-primary {
        background-color: #003366; /* Navy Blue */
        border: none;
        width: 100%;
        padding: 10px;
        font-size: 1rem;
        border-radius: 5px;
    }

    .btn-primary:hover {
        background-color: #00509e; /* Slightly Lighter Blue */
    }

    .btn {
        margin: 5px 0;
    }

    .form-label {
        font-weight: bold;
        color: #003366; /* Navy Blue */
    }

    .form-control {
        border-radius: 5px;
        padding: 10px;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #003366; /* Navy Blue border */
    }

    #buttons {
        margin-top: 20px;
    }

    #filterByCategory,
    #filterByPrice {
        background-color: #ffffff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.2);
    }
</style>


</head>
<body>
<div class="container">
    <h3>All Rooms</h3>
    <div id="showRooms">
        <table class="table table-striped text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hotel ID</th>
                    <th>Room Number</th>
                    <th>Category</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rooms)): ?>
                    <?php foreach ($rooms as $room): ?>
                        <tr>
                            <td><?= htmlspecialchars($room['id']); ?></td>
                            <td><?= htmlspecialchars($room['hotel_id']); ?></td>
                            <td><?= htmlspecialchars($room['room_number']); ?></td>
                            <td><?= htmlspecialchars($room['category']); ?></td>
                            <td>$<?= htmlspecialchars($room['price']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No rooms available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
  
    <div id="filterByCategory" class="mt-4">
        <label for="categoryInputFilter" class="form-label">Category:</label>
        <input type="text" id="categoryInputFilter" class="form-control mb-3" placeholder="Enter room category">
        <button id="filterByCategoryButton" type="button" class="btn btn-primary">Filter by Category</button>
    </div>
    <div id="filterByPrice" class="mt-4">
        <label for="priceInputFilter" class="form-label">Price:</label>
        <input type="text" id="priceInputFilter" class="form-control mb-3" placeholder="Enter maximum price">
        <button id="filterByPriceButton" type="button" class="btn btn-primary">Filter by Price</button>
    </div>
    <form method="post" class="mt-4">
        <input id="returnButton" type="submit" class="btn btn-primary" name="returnButton" value="Return to Admin Page">
    </form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="adminrooms_script.js"></script>
</body>
</html>
