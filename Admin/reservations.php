<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['admin_username'])) {
    header('Location: ../login.php');
    die();
}


if (isset($_POST['returnButton'])) {
    header('Location: admin_dashboard.php'); 
}

if (isset($_POST['postButton'])) {
    header('Location: add_reservation.php');
}

if (isset($_POST['EDITRES'])) {
    header('Location: editreservation.php');
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="../style.css">
        <title>Admin Reservations</title> 
    </head>
    <body>
        <br>
        <div class="container text-center" id="viewReservationsDiv">
            <div class="container" id="showReservations">
                <h3>All Reservations (Admin)</h3>
                <table class="reservationtable table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Room Number</th>
                            <th>Hotel Name</th>  
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div id="buttons" class="container text-center">
                <button type="button" id="previousButton" class="btn btn-primary mb-1">Previous</button>
                <button id="nextButton" type="button" class="btn btn-primary mb-1">Next</button>
                
                <br><br>
                <div id="ADDRES" class="mb-1">
                    <form method="post">
                        <input id="postButton" type="submit" class="btn btn-primary mb-1" name="postButton" value="Add Reservation">
                    </form>
                </div>

                <br>
                <div id="EDITRES" class="mb-1">
                    <form id="editForm" method="post" action="editreservation.php">
                        <label for="editInput" class="form-label">ID: </label>
                        <input type="text" id="editInput" name="id" class="form-control mb-3">
                        <button id="editReservation" type="submit" class="btn btn-primary mb-3">Edit Reservation</button>
                    </form>
                </div>

                <br>
                <div id="DELETERES" class="mb-1">
                    <form id="deleteForm" method="post"> 
                        <label for="deleteInput" class="form-label">ID: </label>
                        <input type="text" id="deleteInput" class="form-control mb-3">
                        <button id="deleteReservation" type="submit" class="btn btn-danger mb-3">Delete Reservation</button>
                    </form>
                </div>

                <br>
                <form method="post">
                    <input id="returnButton" type="submit" class="btn btn-primary mb-1" name="returnButton" value="Return to Admin Page">
                </form>
            </div>
        </div> 
        
        <!-- Add Datepicker Inputs -->
        <div id="reservationDates" class="mb-3">
                    <form method="post">
                        <label for="startDate" class="form-label">Start Date: </label>
                        <input type="text" id="startDate" class="form-control mb-3" placeholder="Select Start Date">
                        
                        <label for="endDate" class="form-label">End Date: </label>
                        <input type="text" id="endDate" class="form-control mb-3" placeholder="Select End Date">
                    </form>
                </div>

                <br>
                <form method="post">
                    <input id="returnButton" type="submit" class="btn btn-primary mb-1" name="returnButton" value="Return to Admin Page">
                </form>
            </div>
        </div>  

        </script>
    </body>
</html>
    </body>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="reservations.js"></script>
    <script>
        $(document).ready(function() {
            // Handle row click for editing
            $('.reservationtable').on('click', 'tbody tr', function() {
                var id = $(this).find('td:first-child').text(); 
                $('#editInput').val(id);
                $('#deleteInput').val(id);
                
                $('#editForm').submit(function(e) {
                    e.preventDefault();
                    var id = $('#editInput').val();
                    window.location.href = 'editreservation.php?id=' + id;
                });
            });

            // Handle delete
            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                var id = $('#deleteInput').val();

                $.ajax({
                    type: 'GET',
                    url: "http://localhost/Hotel_app/DBUtils.php",
                    data:{ action: 'deleteReservation',
                        id: id },
                    success: function(data) {
                        let res = JSON.parse(data);
                        if (res === 0) {
                            alert("Reservation could not be deleted!");
                        } else {
                            $('.form-control').val("");
                            alert("Deleted successfully!");
                        }
                    }       
               });
           });
        })
    </script>
</html>