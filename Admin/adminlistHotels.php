<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../DBUtils.php';

$db = new DBConnection();
$hotels = $db->getAllHotels(); // Fetch all hotels using your DBUtils method


if (isset($_SESSION['admin_username'])) {
    $username = $_SESSION['admin_username'];
} else {
    header('Location: ../login.php');
    die();
}
if (isset($_POST['returnButton'])) {
    header('Location: admin_dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Hotels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f0f9ff, #c4e0e5); /* Light blue gradient */
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
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            max-width: 1100px;
            width: 100%;
        }

        h3 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #0077b6;
            text-align: center;
            margin-bottom: 30px;
        }

        .table {
            margin-bottom: 30px;
        }

        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: #f7fdfc;
        }

        th {
            background-color: #0077b6;
            color: #fff;
            text-align: center;
        }

        td {
            vertical-align: middle;
            text-align: center;
        }

        .btn-primary {
            background-color: #0077b6;
            border: none;
            padding: 10px;
            font-size: 1rem;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #005f87;
        }

        .form-label {
            font-weight: bold;
            color: #0077b6;
        }

        .form-control {
            border-radius: 5px;
            padding: 10px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .filter-box {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        #buttons {
            margin-top: 30px;
        }

        #buttons form {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .img-thumbnail {
            border-radius: 8px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<div class="container">
    <h3>All Hotels</h3>
    <div id="showLogs">
        <table class="table table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($hotels as $hotel) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($hotel['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($hotel['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($hotel['address']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
  
    <div class="filter-box">
        <h5 class="text-center mb-3">Filters</h5>
        <div id="filterByName" class="mb-3">
            <label for="nameInputFilter" class="form-label">Name:</label>
            <input type="text" id="nameInputFilter" class="form-control" placeholder="Enter hotel name">
            <button id="filterByNameButton" type="button" class="btn btn-primary mt-2">Filter by Name</button>
        </div>
        <div id="filterByAddress">
            <label for="addressInputFilter" class="form-label">Address:</label>
            <input type="text" id="addressInputFilter" class="form-control" placeholder="Enter address">
            <button id="filterByAddressButton" type="button" class="btn btn-primary mt-2">Filter by Address</button>
        </div>
    </div>
  
    <div id="buttons">
        
        <form method="post" class="mt-3">
            <button id="returnButton" type="submit" class="btn btn-primary" name="returnButton">Return to Admin Page</button>
        </form>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="admin_hotels_script.js"></script>
</body>
</html>
