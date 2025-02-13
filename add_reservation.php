<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'DBUtils.php';

// Redirect unauthenticated users
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Instantiate the DB connection
$db = new DBConnection();

// Handle navigation buttons
if (isset($_POST['returnButton'])) {
    header('Location: home.php');
    exit;
}
if (isset($_POST['viewAllButton'])) {
    header('Location: reservations.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add a Reservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
            crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 20px;
        }
        .container {
            background: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            margin: auto;
        }
        h2 {
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #3498db;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        .btn-secondary {
            background-color: #2ecc71;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
        }
        .btn-secondary:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>
<div class="container text-center">
    <h2>Welcome to our hotel</h2>
    <div id="addFormDiv">
        <h5>Add a Reservation</h5>
        <form>
            <div class="mb-3">
                <label for="roomId" class="form-label">Room ID:</label>
                <input type="text" id="roomId" class="form-control" placeholder="Enter Room ID">
            </div>
            <div class="mb-3">
                <label for="startDate" class="form-label">Start Date:</label>
                <input type="date" id="startDate" class="form-control">
            </div>
            <div class="mb-3">
                <label for="endDate" class="form-label">End Date:</label>
                <input type="date" id="endDate" class="form-control">
            </div>
            <button type="button" id="insertLogButton" class="btn btn-primary mb-3">Add Reservation</button>
<script>
    const username = <?= json_encode($_SESSION['username']) ?>;
</script>

        </form>
        <form method="post" class="d-flex justify-content-between">
            <button type="submit" class="btn btn-secondary" name="viewAllButton">View All Reservations</button>
            <button type="submit" class="btn btn-primary" name="returnButton">Return to Main Page</button>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="add_script_reservations.js"></script>

</body>
</html>
