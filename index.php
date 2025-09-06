<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'DBUtils.php';

// Fetch hotels from the database
$db = new DBConnection();
$hotels = $db->getAllHotels();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .hero {
            background: url('img/messina1.jpg') no-repeat center center/cover;
            height: 70vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            font-size: 2.5rem;
            font-weight: bold;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
        }

        .hero .btn {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1.2rem;
            background-color: #3498db;
            color: white;
            border: none;
        }

        .hero .btn:hover {
            background-color: #2980b9;
        }

        .featured-hotels .card {
            background-color: #ffffff;
            border: 1px solid #ddd;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .featured-hotels .btn-primary {
            background-color: #5cb85c;
            border: none;
        }

        .featured-hotels .btn-primary:hover {
            background-color: #4cae4c;
        }

        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 20px 0;
        }
    </style>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">Hotel Booking</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>
                    <li class="nav-item"><a class="nav-link" href="Admin/admin_login.php">Admin Login</a></li>
                
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero">
        Welcome to Our Hotel Booking System
        <a href="login.php" class="btn">Get Started</a>
    </div>

    <!-- Featured Hotels Section -->
    <div class="container featured-hotels mt-5">
        <h2 class="text-center mb-4">Featured Hotels</h2>
        <div class="row">
            <?php foreach ($hotels as $hotel): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                    
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($hotel['name']); ?></h5>
                            <p class="card-text"><?= htmlspecialchars($hotel['address']); ?></p>
                            </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 Hotel Booking | Follow us:</p>
            <a href="#" class="text-white me-3"><i class="fab fa-facebook"></i></a>
            <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
            <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
            <p>Contact us: info@hotelbooking.gmail.com | +123 456 7890</p>
            <p>Address: Messina, Italy</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
