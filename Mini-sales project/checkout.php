<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sales_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Calculate total cart value
$cart_total = 0;
$total_quantity = 0;

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_total += $item['price'] * $item['quantity'];
        $total_quantity += $item['quantity'];
    }
}

// Process payment and add to transactions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone_number = $_POST['phone_number'];
    $delivery_time = $_POST['delivery_time'];
    $payment_option = $_POST['payment_option'];

    if (preg_match('/^07[0-9]{8}$/', $phone_number)) {
        $payment_status = ($payment_option === 'M-Pesa') ? 'Paid' : 'Pending';

        $conn->begin_transaction();

        try {
            foreach ($_SESSION['cart'] as $item) {
                $product_id = $item['id'];
                $quantity = $item['quantity'];
                $total_sales = $item['price'] * $quantity;

                $profit_query = "SELECT profit FROM products WHERE id = ?";
                $stmt_profit = $conn->prepare($profit_query);
                $stmt_profit->bind_param("i", $product_id);
                $stmt_profit->execute();
                $stmt_profit->bind_result($profit);
                $stmt_profit->fetch();
                $stmt_profit->close();

                $profit *= $quantity;
                $sale_date = date('Y-m-d');

                $stmt = $conn->prepare("INSERT INTO transactions (product_id, quantity, total_sales, profit, sale_date, delivery_time, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iidssss", $product_id, $quantity, $total_sales, $profit, $sale_date, $delivery_time, $payment_status);

                if (!$stmt->execute()) {
                    throw new Exception("Error processing transaction.");
                }
            }

            $conn->commit();
            unset($_SESSION['cart']);
            $_SESSION['payment_status'] = 'success';
            $_SESSION['payment_total'] = $cart_total;
            $_SESSION['phone_number'] = $phone_number;
            $_SESSION['delivery_time'] = $delivery_time;
            $_SESSION['payment_method'] = $payment_option;

            header('Location: confirmation.php');
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            $error_message = "Transaction failed: " . $e->getMessage();
        }
    } else {
        $error_message = "Invalid phone number format. Use 07XXXXXXXX.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - ReJo Sales</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Checkout</h1>

        <div class="summary">
            <p><strong>Total Amount:</strong> Ksh <?php echo number_format($cart_total, 2); ?></p>
            <p><strong>Total Quantity:</strong> <?php echo $total_quantity; ?></p>
        </div>

        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST" class="checkout-form">
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" placeholder="07XXXXXXXX" required>

            <label for="delivery_time">Preferred Delivery Time:</label>
            <input type="time" id="delivery_time" name="delivery_time" required>

            <label for="payment_option">Payment Method:</label>
            <select id="payment_option" name="payment_option" required>
                <option value="M-Pesa">M-Pesa</option>
                <option value="Pay on Delivery">Pay on Delivery</option>
            </select>

            <button type="submit">Simulate Payment</button>
        </form>
    </div>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .summary p {
            font-size: 18px;
            margin: 10px 0;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .checkout-form label {
            font-size: 16px;
            margin: 10px 0;
        }
        .checkout-form input, .checkout-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</body>
</html>
