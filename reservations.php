<?php
session_start();
require_once 'DBUtils.php';

// Redirect unauthenticated users
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$db = new DBConnection();
$reservations = $db->getAllReservations();


// Handle "Return to Main Page" button click
if (isset($_POST['returnButton'])) {
    header('Location: home.php');
    exit;
}

// Handle "Add Reservation" button click
if (isset($_POST['postButton'])) {
    header('Location: add_reservation.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>All Your Reservations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: rgba(255, 255, 255, 0.95);
            color: #fff;
            padding: 20px;
        }

        .container {
            background: rgba(223, 239, 244, 0.9);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(198, 193, 193, 0.2);
            color: #333;
        }

        h3 {
            font-size: 1.8rem;
            font-weight: bold;
            text-align: center;
        }

        .btn {
            margin: 5px;
        }

        table {
            width: 100%;
            text-align: center;
        }

        th, td {
            padding: 10px;
        }

        .form-label {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container text-center" id="viewReservationsDiv">
    <h3>All Your Reservations</h3>
    <div id="showReservations" class="mt-4">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Room Number</th>
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
    <div id="buttons" class="mt-4">
        <form method="post" class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary" name="postButton">Add Reservation</button>
            <button type="submit" class="btn btn-secondary" name="returnButton">Return to Main Page</button>
        </form>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Add event handlers for edit and delete functionality
        $('tbody tr').on('click', function() {
            var id = $(this).find('td:first').text();
            $('#editReservation input').val(id);
            $('#deleteReservation input').val(id);
        });

        $('#deleteForm').on('submit', function(e) {
            e.preventDefault();
            var id = $('#deleteReservation input').val();
            $.ajax({
                type: 'POST',
                url: 'DBUtils.php',
                data: { action: 'deleteReservetion', id: id },
                success: function(response) {
                    let res = JSON.parse(response);
                    if (res === 1) {
                        alert('Reservation deleted successfully.');
                        location.reload();
                    } else {
                        alert('Failed to delete the reservation.');
                    }
                }
            });
        });
    });
</script>
</body>
</html>
