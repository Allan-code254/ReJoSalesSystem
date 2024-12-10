<?php
// Start session to store cart data
session_start();

// Handle Add to Cart logic before rendering the page
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];

    // Initialize cart session if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if product already exists in the cart
    $found = false;
    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['id'] === $product_id) {
            $cart_item['quantity'] += 1; // Increase quantity
            $found = true;
            break;
        }
    }

    // If the product is not found in the cart, add it
    if (!$found) {
        $_SESSION['cart'][] = array(
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'image' => $product_image,
            'quantity' => 1
        );
    }

    // Redirect to the cart page
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - ReJo Sales</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section id="header">
        <a href="#"><img src="img/logo.png" class="logo" alt=""></a>
        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a class="active" href="shop.php">Shop</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="transactions.php">Transactions</a></li>
                <li><a href="signup.php" class="auth-link">Signup</a></li>
                <li><a href="login.php" class="auth-link">Login</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </section>

    <div class="container">
        <h1>Shop Products</h1>

        <div class="products-grid">
            <?php
            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "sales_system";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch products from the database
            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Display each product
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='product'>
                            <img src='" . $row['image_path'] . "' alt='" . $row['name'] . "'>
                            <h3>" . $row['name'] . "</h3>
                            <p>" . $row['description'] . "</p>
                            <p>Ksh " . $row['price'] . "</p>
                            <form method='POST' action='shop.php'>
                                <input type='hidden' name='product_id' value='" . $row['id'] . "'>
                                <input type='hidden' name='product_name' value='" . $row['name'] . "'>
                                <input type='hidden' name='product_price' value='" . $row['price'] . "'>
                                <input type='hidden' name='product_image' value='" . $row['image_path'] . "'>
                                <button type='submit' name='add_to_cart'>Add to Cart</button>
                            </form>
                          </div>";
                }
            } else {
                echo "No products available.";
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
