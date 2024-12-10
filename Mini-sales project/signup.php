<?php
// signup.php - Page for user registration
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - ReJo Sales</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header Section -->
    <section id="header">
        <a href="#"><img src="img/logo.png" class="logo" alt="ReJo Sales"></a>
        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="transactions.php">Transactions</a></li>
                <li><a class="active" href="signup.php">Signup</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </section>

    <!-- Signup Form Section -->
    <div class="signup-container">
        <h2>Create an Account</h2>
        <form action="process_signup.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="signup-btn">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php" class="login-link">Login here</a></p>
    </div>
</body>
</html>
