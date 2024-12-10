<?php
session_start();

// Handle remove item request
if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];

    // Find and remove the product from the cart session
    foreach ($_SESSION['cart'] as $key => $cart_item) {
        if ($cart_item['id'] === $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    // Re-index the cart array
    $_SESSION['cart'] = array_values($_SESSION['cart']);

    // Redirect to the cart page to avoid form resubmission
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - ReJo Sales</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Cart Table Styling */
        .cart-container {
            margin: 2rem auto;
            max-width: 80%;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem;
        }

        .cart-container h1 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        th, td {
            padding: 1rem;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
            color: #333;
        }

        td img {
            max-width: 60px;
            border-radius: 4px;
        }

        .checkout {
            display: flex;
            justify-content: flex-end;
            margin-top: 1rem;
        }

        .checkout a {
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .checkout a:hover {
            background-color: #2563eb;
        }

        .remove-btn {
            background-color: #ff4d4d;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            cursor: pointer;
            border-radius: 5px;
            font-size: 0.9rem;
            transition: background-color 0.3s;
        }

        .remove-btn:hover {
            background-color: #ff1a1a;
        }

        .empty-cart {
            text-align: center;
            color: #888;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <section id="header">
        <a href="#"><img src="img/logo.png" class="logo" alt=""></a>
        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a class="active" href="cart.php">Cart</a></li>
                <li><a href="transactions.php">Transactions</a></li>
                <li><a href="signup.php" class="auth-link">Signup</a></li>
                <li><a href="login.php" class="auth-link">Login</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </section>

    <div class="cart-container">
        <h1>Your Cart</h1>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                    $total = 0;
                    foreach ($_SESSION['cart'] as $cart_item) {
                        $subtotal = $cart_item['price'] * $cart_item['quantity'];
                        $total += $subtotal;
                        echo "<tr>
                                <td>
                                    <img src='" . $cart_item['image'] . "' alt='" . $cart_item['name'] . "'>
                                    <br>" . $cart_item['name'] . "
                                </td>
                                <td>Ksh " . $cart_item['price'] . "</td>
                                <td>" . $cart_item['quantity'] . "</td>
                                <td>Ksh " . $subtotal . "</td>
                                <td>
                                    <form method='POST' action='cart.php'>
                                        <input type='hidden' name='product_id' value='" . $cart_item['id'] . "'>
                                        <button type='submit' name='remove_item' class='remove-btn'>Remove</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                    echo "<tr>
                            <td colspan='3'><strong>Total:</strong></td>
                            <td colspan='2'><strong>Ksh " . $total . "</strong></td>
                          </tr>";
                } else {
                    echo "<tr><td colspan='5' class='empty-cart'>Your cart is empty.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="checkout">
            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) { ?>
                <a href="checkout.php">Proceed to Checkout</a>
            <?php } ?>
        </div>
    </div>
</body>
</html>
