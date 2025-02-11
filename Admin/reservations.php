<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['admin_username'])) {
    header('Location: ../login.php');
    die();
}
require_once '../DBUtils.php'; // Adjust the path as needed
$db = new DBConnection();
$reservations = $db->getAllReservations();


// Fetch all reservations from the database
 // Assuming you have a method like this in your DBUtils.php
if (isset($_POST['returnButton'])) {
    header('Location: admin_dashboard.php'); 
}
if (isset($_POST['postButton'])) {
    header('Location: add_reservation.php');
    exit; // Always include exit after a header redirect
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Reservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f0f9ff, #c4e0e5);
            color: #fff;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            padding: 20px;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background:rgb(243, 250, 255);
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
            padding: 30px;
            max-width: 900px;
            width: 100%;
            color: #333;
        }

        h3 {
            font-size: 2rem;
            color: #1e3c72;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #1e3c72;
            color: #ffffff;
        }

        .btn {
            margin: 10px 5px;
        }

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #1e3c72;
            border: none;
        }

        .btn-primary:hover {
            background-color: #142850;
        }

        .btn-danger {
            background-color: #e74c3c;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        #reservationDates {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h3>All Reservations (Admin)</h3>
    <div id="showReservations">
        <table class="reservationtable table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Room Number</th>
                    <th>Hotel Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
            </thead>
            <tbody>
    <?php if (!empty($reservations)): ?>
        <?php foreach ($reservations as $reservation): ?>
            <tr>
                <td><?= htmlspecialchars($reservation['id']); ?></td>
                <td><?= htmlspecialchars($reservation['room_number']); ?></td>
                <td><?= htmlspecialchars($reservation['hotel_name']); ?></td>
                <td><?= htmlspecialchars($reservation['start_date']); ?></td>
                <td><?= htmlspecialchars($reservation['end_date']); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" class="text-center">No reservations found.</td>
        </tr>
    <?php endif; ?>
</tbody>

        </table>
    </div>

    <!-- Buttons -->
    <div id="buttons" class="text-center">
    <form method="post" >
    <button type="submit" id="postButton" class="btn btn-primary mb-3" name="postButton">Add Reservation</button>
</form>

        <form id="editForm" method="post">
            <label for="editInput" class="form-label">Reservation ID:</label>
            <input type="text" id="editInput" name="id" class="form-control mb-3">
            <button id="editReservation" type="submit" class="btn btn-primary mb-3">Edit Reservation</button>
        </form>
        <form id="deleteForm" method="post">
            <label for="deleteInput" class="form-label">Reservation ID:</label>
            <input type="text" id="deleteInput" class="form-control mb-3">
            <button id="deleteReservation" type="submit" class="btn btn-danger mb-3">Delete Reservation</button>
        </form>
        <form method="post">
            <button type="submit" id="returnButton" class="btn btn-primary mb-3" name="returnButton">Return to Admin Page</button>
        </form>
    </div>

    <!-- Reservation Dates -->
    
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        // Handle row click for editing
        $('.reservationtable').on('click', 'tbody tr', function () {
            var id = $(this).find('td:first').text();
            $('#editInput').val(id);
            $('#deleteInput').val(id);
        });

        // Handle delete
        $('#deleteForm').submit(function (e) {
            e.preventDefault();
            var id = $('#deleteInput').val();

            $.ajax({
                type: 'GET',
                url: "http://localhost/Hotel_app/DBUtils.php",
                data: { action: 'deleteReservation', id: id },
                success: function (data) {
                    let res = JSON.parse(data);
                    if (res === 0) {
                        alert("Reservation could not be deleted!");
                    } else {
                        $('.form-control').val("");
                        alert("Deleted successfully!");
                        location.reload();
                    }
                }
            });
        });
    });
</script>
</body>
</html>
