<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="assets/css/theme.css">
    <meta charset="UTF-8">
    <title>TrackWise | Smart Expense Tracker</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand fw-bold" href="#">TrackWise</a>
    <div class="ms-auto">
        <a href="auth/login.php" class="btn btn-outline-light me-2">Login</a>
        <a href="auth/register.php" class="btn btn-primary">Get Started</a>
    </div>
</nav>

<section class="hero">
    <div class="container text-center">
        <h1 class="fw-bold">Take Control of Your Expenses</h1>
        <p class="text-muted mt-3">
            TrackWise helps you manage daily expenses, analyze spending,
            and stay financially organized.
        </p>

        <div class="mt-4">
            <a href="auth/register.php" class="btn btn-primary btn-lg me-2">
                Start Tracking
            </a>
            <a href="auth/login.php" class="btn btn-outline-secondary btn-lg">
                Login
            </a>
        </div>
    </div>
</section>

<section class="features container mt-5">
    <div class="row text-center">
        <div class="col-md-4">
            <h5>📊 Expense Tracking</h5>
            <p class="text-muted">Log and monitor daily expenses easily</p>
        </div>
        <div class="col-md-4">
            <h5>📈 Smart Dashboard</h5>
            <p class="text-muted">Visual insights into your spending</p>
        </div>
        <div class="col-md-4">
            <h5>🔒 Secure</h5>
            <p class="text-muted">Your data is safe and private</p>
        </div>
    </div>
</section>

<footer class="text-center mt-5 p-4 bg-light text-muted">
    © <?php echo date("Y"); ?> TrackWise. All rights reserved.
</footer>

</body>
</html>