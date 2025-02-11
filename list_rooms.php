<?php
session_start();
require_once 'DBUtils.php';

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    header('Location: login.php');
    die();
}

// Redirect to home page when "Return to Main Page" is clicked
if (isset($_POST['returnButton'])) {
    header('Location: home.php');
    exit;
}

// Fetch all rooms from the database
$db = new DBConnection();
$rooms = $db->getAllRooms(); // Assuming `getAllRooms` fetches the correct data from your database
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <title>All Rooms</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: linear-gradient(135deg, #f3f4f6, #d9e4f5); /* Light gradient */
                padding: 20px;
            }

            .container {
                background: #ffffff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            }

            .table thead {
                background-color: #1c3c47; /* Dark teal */
                color: white;
            }

            .table tbody tr:hover {
                background-color: #f9f9f9; /* Light hover effect */
            }

            .btn-primary {
                background-color: #1c3c47;
                border: none;
            }

            .btn-primary:hover {
                background-color:rgb(238, 179, 111);
            }

            .btn-primary:focus {
                box-shadow: 0 0 0 0.2rem rgba(199, 0, 222, 0.25);
            }

            .room-img {
                width: 100px;
                height: 100px;
                object-fit: cover; /* Ensures the image fits within the dimensions without distortion */
                border-radius: 5px;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); /* Optional shadow for better appearance */
            }
        </style>
    </head>
    <body>
        <div class="container text-center">
            <h3 class="mb-4">All Rooms</h3>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hotel Name</th>
                        <th>Room Number</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rooms as $room): ?>
                        <?php
                        // Define supported extensions
                        $extensions = ['jpg', 'jpeg', 'png'];

                        // Initialize image path
                        $imagePath = "imgrooms/default.jpg"; // Default image

                        // Check for specific room images
                        foreach ($extensions as $ext) {
                            $filePath = "imgrooms/{$room['id']}.$ext";
                            if (file_exists($filePath)) {
                                $imagePath = $filePath;
                                break;
                            }
                        }
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($room['id']); ?></td>
                            <td><?= htmlspecialchars($room['hotel_name']); ?></td>
                            <td><?= htmlspecialchars($room['room_number']); ?></td>
                            <td><?= htmlspecialchars($room['category']); ?></td>
                            <td>$<?= htmlspecialchars($room['price']); ?></td>
                            <td>
                                <img src="<?= $imagePath ?>" alt="Room Image" class="img-thumbnail room-img">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <form method="post" class="mt-4">
                <input id="returnButton" type="submit" class="btn btn-primary" name="returnButton" value="Return to Main Page">
            </form>
        </div>
    </body>
</html>
