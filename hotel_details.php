<?php
session_start();
require_once 'DBUtils.php';

// Redirect unauthenticated users
if (!isset($_SESSION['username'])) {
    header('Location: home.php');
    exit;
}

// Check if the hotel ID is provided in the query string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: listHotels.php');
    exit;
}

$hotelId = intval($_GET['id']); // Sanitize the hotel ID
$db = new DBConnection();

// Fetch hotel details
$hotel = $db->getHotelById($hotelId);
if (empty($hotel)) { // Check if the hotel exists
    die('Hotel not found.');
}

// Fetch rooms associated with the hotel
$rooms = $db->getRoomsByHotelId($hotelId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('your-background-image.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            color: #333;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-success {
            background-color: #00cec9;
            border: none;
        }

        .btn-success:hover {
            background-color: #00b894;
        }

        .btn-primary {
            background-color: #0984e3;
            border: none;
        }

        .btn-primary:hover {
            background-color: #74b9ff;
        }

        .room-img {
            width: 100px; /* Set a fixed width */
            height: 100px; /* Set a fixed height */
            object-fit: cover; /* Ensures the image fits the container without distortion */
            border-radius: 5px; /* Optional: Adds rounded corners to the image */
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); /* Optional: Adds a shadow for better aesthetics */
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h3>Available Rooms</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rooms)): ?>
                    <?php foreach ($rooms as $index => $room): ?>
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
                            <td><?= $index + 1; ?></td>
                            <td><?= ($room['category'] ?? 'Unknown'); ?></td>
                            <td>$<?= ($room['price'] ?? '0.00'); ?></td>
                            <td>
                                <img src="<?= $imagePath ?>" alt="Room Image" class="img-thumbnail room-img">
                            </td>
                            <td>
                                <a href="add_reservation.php?hotel_id=<?= htmlspecialchars($hotel['id']); ?>" class="btn btn-sm btn-success">Book Now</a>
                            </td>
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
    <a href="listHotels.php" class="btn btn-primary mt-3">Back to Hotels</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
