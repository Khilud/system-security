<?php
session_start();
require_once 'DBUtils.php';

// Redirect unauthenticated users
if (!isset($_SESSION['username'])) {
    header('Location: home.php');
    exit;
}
// Handle "Return to Main Page" button click
if (isset($_POST['returnButton'])) {
    header('Location: home.php'); // Redirect to home.php
    exit; // Ensure no further code execution
}

// Fetch hotels with pagination
$db = new DBConnection();
$perPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;

$nameFilter = isset($_GET['nameFilter']) ? $_GET['nameFilter'] : '';
$addressFilter = isset($_GET['addressFilter']) ? $_GET['addressFilter'] : '';

$hotels = $db->getFilteredHotels($nameFilter, $addressFilter, $start, $perPage);
$totalHotels = $db->getHotelCount($nameFilter, $addressFilter);
$totalPages = ceil($totalHotels / $perPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Hotels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .img-thumbnail {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<div class="content">
    <div class="container mt-4">
        <h3 class="text-center mb-4">All Hotels</h3>
        <form method="get" class="row mb-4">
            <div class="col-md-5">
                <input type="text" name="nameFilter" placeholder="Search by Name" class="form-control" value="<?= htmlspecialchars($nameFilter); ?>">
            </div>
            <div class="col-md-5">
                <input type="text" name="addressFilter" placeholder="Search by Address" class="form-control" value="<?= htmlspecialchars($addressFilter); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-info w-100">Filter</button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Hotel Name</th>
                        <th>Address</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hotels as $hotel): ?>
                        <tr>
                            <td><?= htmlspecialchars($hotel['id']); ?></td>
                            <td><?= htmlspecialchars($hotel['name']); ?></td>
                            <td><?= htmlspecialchars($hotel['address']); ?></td>
                            <td>
                            <?php
// Supported extensions
$extensions = ['jpg', 'jpeg', 'png'];
$imagePath = null;

// Loop through supported extensions to find the image
foreach ($extensions as $ext) {
    $filePath = "imghotel/{$hotel['id']}.$ext";
    if (file_exists($filePath)) {
        $imagePath = $filePath;
        break;
    }
}

// Fallback to default image if no matching file is found
if (!$imagePath) {
    $imagePath = "imghotel/default.jpg";
}
?>
<img src="<?= $imagePath ?>" class="img-thumbnail" alt="Hotel Image">

                            <td>
                                <a href="hotel_details.php?id=<?= htmlspecialchars($hotel['id']); ?>" class="btn btn-sm btn-primary">View Details</a>
                                <a href="add_reservation.php?hotel_id=<?= htmlspecialchars($hotel['id']); ?>" class="btn btn-sm btn-success">Book Now</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
</td>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-3">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1; ?>" class="btn btn-primary me-2">Previous</a>
            <?php endif; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1; ?>" class="btn btn-primary">Next</a>
            <?php endif; ?>
        </div>
        <form method="post" class="text-center mt-4">
            <input id="returnButton" type="submit" class="btn btn-danger" name="returnButton" value="Return to Main Page">
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>