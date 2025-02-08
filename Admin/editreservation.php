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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../style.css"> <!-- Adjusted path -->
    <title>Admin - Edit Reservation</title> 
</head>
<body>
    <div class="container text-center">
        <h3>Edit Reservation</h3>
        <div class="mb-1">
            <label for="startDate" class="form-label">Start Date:</label>
            <input type="text" id="startDate" class="form-control">
        </div>
        <div class="mb-1">
            <label for="endDate" class="form-label">End Date:</label>
            <input type="text" id="endDate" class="form-control">
        </div>
        <button id="editButton" class="btn btn-primary mb-1">Save Changes</button>

        <form method="post">
            <input type="submit" class="btn btn-secondary mb-1" name="viewAllButton" value="View All Reservations">
            <input type="submit" class="btn btn-secondary mb-1" name="returnButton" value="Return to Admin Panel">
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
