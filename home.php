<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user'; // Default role is 'user'

// Redirect to login page if not logged in when reserving or editing
if (isset($_GET['action']) && ($_GET['action'] == 'reserve' || $_GET['action'] == 'edit')) {
    if (!$username) {
        header('Location: login.php');
        die();
    }
}

// Log out functionality
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* General Body and Layout */
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            font-family: 'Arial', sans-serif;
            background: url('img/messina1.jpg') no-repeat center center fixed; /* Background Image */
            background-size: cover; /* Cover the entire screen */
            color: #333333; /* Dark Gray */
        }

        .content {
            flex: 1; /* Push footer to the bottom */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: rgba(255, 255, 255, 0.85); /* Semi-transparent white */
            border-radius: 15px;
            padding: 20px;
            margin: 20px;
            max-width: 900px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Header Styling */
        header {
            background-color: #1c3c47; /* Dark Teal */
            color: #ffffff; /* White */
            padding: 20px 0;
            text-align: center;
            font-size: 1.8rem;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        header p {
            margin: 5px 0 0;
            font-size: 1rem;
            color: #f4c542; /* Gold */
        }

        /* Buttons */
        .btn-outline-primary {
            border-color: #1c3c47; /* Dark Teal */
            color: #1c3c47; /* Dark Teal */
            font-weight: bold;
            font-size: 1rem;
            text-transform: uppercase;
            margin: 10px 5px;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }

        .btn-outline-primary:hover {
            background-color: #2e8b7d; /* Dark Teal */
            color: #ffffff; /* White */
        }

        .btn-danger {
            background-color: #f4c542; /* Gold */
            color: #ffffff; /* White */
            border: none;
            font-weight: bold;
            font-size: 1rem;
            text-transform: uppercase;
            margin: 20px 0;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }

        .btn-danger:hover {
            background-color: #2e8b7d; /* Darker Gold */
        }

        /* Footer Styling */
        footer {
            background-color: #1c3c47; /* Dark Teal */
            color: #ffffff; /* White */
            text-align: center;
            padding: 15px 0;
            margin-top: 20px;
            font-size: 0.9rem;
            width: 100%;
        }

        footer a {
            color: #f4c542; /* Gold */
            text-decoration: none;
            font-weight: bold;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Header -->
<header>
    <h2>Welcome<?php echo $username ? ', ' . htmlspecialchars($username) : ''; ?>!</h2>
    <?php if ($role === 'admin') : ?>
        <p>Administrator Panel</p>
    <?php else : ?>
        <p>Explore & Manage Reservations</p>
    <?php endif; ?>
</header>

<!-- Main Content -->
<div class="content">
    <main>
        <h4>Discover Our Hotels & Manage Your Stay</h4>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='listHotels.php'">View All Hotels</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='list_rooms.php'">View All Rooms</button>

        <?php if ($username) : ?>
            <button type="button" class="btn btn-outline-primary" onclick="location.href='reservations.php'">My Reservations</button>
            <?php if ($role === 'admin') : ?>
                <button type="button" class="btn btn-outline-primary" onclick="location.href='addHotel.php'">Add Hotel</button>
                <button type="button" class="btn btn-outline-primary" onclick="location.href='addRoom.php'">Add Room</button>
            <?php endif; ?>
        <?php else : ?>
            <button type="button" class="btn btn-outline-primary" onclick="location.href='login.php'">Login to Reserve</button>
        <?php endif; ?>

        <form method="post">
            <button type="submit" class="btn btn-danger" name="logoutButton">Return to home</button>
        </form>
    </main>
</div>

<!-- Footer -->
<footer>
    <p>Follow us on <a href="#">Social Media</a> | Contact: contact@hotelbooking.com</p>
</footer>

</body>
</html>
