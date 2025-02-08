<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user'; // Default role is 'user'

// Check if user is trying to book or edit reservations
if (isset($_GET['action']) && ($_GET['action'] == 'reserve' || $_GET['action'] == 'edit')) {
    if (!$username) {
        // Redirect to login page if not logged in
        header('Location: login.php');
        die();
    }
}

if (isset($_POST['logoutButton'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<header class="bg-primary text-white text-center py-4">
    <h2>Welcome<?php echo $username ? ', ' . $username : ''; ?>!</h2>
</header>

<div class="content">
    <br>

<main class="container mt-4">
    <div class="text-center">
        <h4>Explore Our Hotels & Manage Your Reservations</h4>
        <br>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='listHotels.php'">View All Hotels</button>
        <br>
        <br>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='list_rooms.php'">View All Rooms</button>

        <?php if ($username) : ?>
            <button type="button" class="btn btn-outline-primary" onclick="location.href='reservations.php'">My Reservations</button>
        <?php else : ?>
            <button type="button" class="btn btn-outline-primary" onclick="location.href='login.php'">Login to Reserve</button>
        <?php endif; ?>

        <br><br>


        <form method="post">
            <button type="submit" class="btn btn-danger" name="logoutButton">Log Out</button>
        </form>
    </div>
</main>

<footer class="bg-dark text-white text-center py-3 mt-4">
    <p>Follow us on <a href="#" class="text-info">Social Media</a> | Contact: contact@hotelbooking.com</p>
</footer>

</body>
</html>
