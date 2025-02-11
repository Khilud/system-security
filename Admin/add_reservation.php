<?php
session_start();

// Handle navigation buttons
if (isset($_POST['returnButton'])) {
    header('Location: admin_dashboard.php');
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
            crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container text-center" id="addFormDiv" style="max-width:500px; margin-top:50px;">
    <h5>Add a Reservation</h5>
    <hr>

    <!-- Message Container -->
    <div id="reservationResult" class="mt-3"></div>
    
    <!-- Reservation Form Fields (Inputs) -->
    <div class="mb-3 text-start">
        <label for="roomId" class="form-label">Room ID:</label>
        <input type="text" id="roomId" class="form-control">
    </div>
    <div class="mb-3 text-start">
        <label for="startDate" class="form-label">Start Date:</label>
        <!-- You can use "type='date'" if desired -->
        <input type="text" id="startDate" class="form-control" placeholder="YYYY-MM-DD">
    </div>
    <div class="mb-3 text-start">
        <label for="endDate" class="form-label">End Date:</label>
        <input type="text" id="endDate" class="form-control" placeholder="YYYY-MM-DD">
    </div>
    <!-- Button triggers AJAX call -->
    <button id="insertLogButton" type="button" class="btn btn-primary mb-3">Add Reservation</button>

    <!-- Navigation Buttons -->
    <form method="post">
        <button type="submit" class="btn btn-primary mb-1" name="viewAllButton">
            View All Reservations
        </button>
        <button type="submit" class="btn btn-secondary mb-1" name="returnButton">
            Return to Main Page
        </button>
    </form>
</div>

<!-- jQuery + Custom Script -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="add_script_reservation.js"></script>
</body>
</html>
