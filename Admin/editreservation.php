<?php
session_start();

// Ensure only admin can access
if (!isset($_SESSION['admin_username'])) {
    header('Location: ../login.php');
    die();
}

// Get reservation ID
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (isset($_POST['returnButton'])) {
    header('Location: admin_dashboard.php'); // Redirect to admin home
}
if (isset($_POST['viewAllButton'])) {
    header('Location: reservations.php'); // Redirect to admin reservations
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Reservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f0f4f7, #d9e4ec); /* Light modern gradient */
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
        }

        h3 {
            font-size: 2rem;
            font-weight: bold;
            color: #004d73;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: bold;
            color: #004d73;
        }

        .form-control {
            border-radius: 5px;
            padding: 10px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .btn {
            margin-top: 10px;
            width: 100%;
        }

        .btn-primary {
            background-color: #004d73;
            border: none;
            padding: 10px;
            font-size: 1rem;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #003b59;
        }

        .btn-secondary {
            background-color: #00796b;
            border: none;
            padding: 10px;
            font-size: 1rem;
            border-radius: 5px;
        }

        .btn-secondary:hover {
            background-color: #004d40;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h3>Edit Reservation</h3>
    <form>
        <div class="mb-3">
            <label for="startDate" class="form-label">Start Date:</label>
            <input type="date" id="startDate" class="form-control" placeholder="Enter Start Date">
        </div>
        <div class="mb-3">
            <label for="endDate" class="form-label">End Date:</label>
            <input type="date" id="endDate" class="form-control" placeholder="Enter End Date">
        </div>
        <button id="editButton" type="button" class="btn btn-primary">Save Changes</button>
    </form>

    <form method="post" class="mt-4">
        <button type="submit" class="btn btn-secondary" name="viewAllButton">View All Reservations</button>
        <button type="submit" class="btn btn-secondary" name="returnButton">Return to Admin Panel</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $('#editButton').click(() => {
        let start_date = $('#startDate').val();
        let end_date = $('#endDate').val();
        let id = new URLSearchParams(window.location.search).get('id');

        if (start_date.trim().length && end_date.trim().length) {
            $.ajax({
                type: 'GET',
                url: "http://localhost/Hotel_app/DBUtils.php",
                data: {
                    action: 'editReservation',
                    id: id,
                    start_date: start_date,
                    end_date: end_date
                },
                success: (data) => {
                    let res = JSON.parse(data);
                    if (res === 0) {
                        alert("Reservation could not be edited!");
                    } else {
                        alert("Reservation updated successfully!");
                        location.reload(); // Reload page after successful update
                    }
                }
            });
        } else {
            alert("Please enter valid data in all fields!");
        }
    });
</script>
</body>
</html>
