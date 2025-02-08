<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body{
           font-family: Arial, sans-serif;
          }
          .hero{
            background: url('img/messina1.jpg') no-repeat center center/cover;
            height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
              }
              .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 30px;
        }
        </style>
</head>
    </body>
        <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
                    <li class="nav-item"><a class="nav-link" href="home.php">View Hotels</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <div class="hero">Welcome to Our Hotel Booking System</div>

    <!-- Description Section -->
    <div class="container text-center mt-5">
        <h2>Find the Best Hotels at the Best Prices</h2>
        <p class="lead">Book rooms easily and securely. Browse hotels, check prices, and reserve rooms effortlessly.</p>
        <div class="mt-4">
            <a href="login.php" class="btn btn-primary btn-lg m-2">Login</a>
            <a href="signup.php" class="btn btn-success btn-lg m-2">Sign Up</a>
            <a href="Admin/admin_login.php" class="btn btn-warning btn-lg m-2">Admin Login</a>
            <a href="home.php" class="btn btn-primary btn-lg m-2">View hotels</a>
            <br>
            <br>
            <br>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 Hotel Booking | Follow us:</p>
            <a href="#" class="text-white me-3">Facebook</a>
            <a href="#" class="text-white me-3">Instagram</a>
            <a href="#" class="text-white">Twitter</a>
            <p>Contact us: info@hotelbooking.gmail.com | +123 456 7890</p>
            <p>Adress: Messina, Italy</p>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
