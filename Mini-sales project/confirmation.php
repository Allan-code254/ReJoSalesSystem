<?php
session_start();

// Check if payment details exist
if (!isset($_SESSION['payment_status'])) {
    echo "<h2>Error: No transaction found!</h2>";
    exit();
}

// Retrieve and clear payment details
$payment_total = $_SESSION['payment_total'];
$phone_number = $_SESSION['phone_number'];
$delivery_time = $_SESSION['delivery_time'];
$payment_method = $_SESSION['payment_method'];

unset($_SESSION['payment_status']);
unset($_SESSION['payment_total']);
unset($_SESSION['phone_number']);
unset($_SESSION['delivery_time']);
unset($_SESSION['payment_method']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation - ReJo Sales</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function printReceipt() {
            const receiptContent = document.getElementById('receipt').innerHTML;
            const originalContent = document.body.innerHTML;
            document.body.innerHTML = receiptContent;
            window.print();
            document.body.innerHTML = originalContent;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Payment Confirmation</h1>
        <div id="receipt" class="receipt-box">
            <h2>Receipt</h2>
            <p><strong>Total Amount:</strong> Ksh <?php echo number_format($payment_total, 2); ?></p>
            <p><strong>Phone Number:</strong> <?php echo $phone_number; ?></p>
            <p><strong>Preferred Delivery Time:</strong> <?php echo $delivery_time; ?></p>
            <p><strong>Payment Method:</strong> <?php echo $payment_method; ?></p>
            <p><strong>Status:</strong> <?php echo ($payment_method === 'M-Pesa') ? 'Paid' : 'Pending Payment'; ?></p>
        </div>

        <div class="actions">
            <button onclick="printReceipt()">Print Receipt</button>
            <a href="index.php" class="btn">Back to Home</a>
        </div>
    </div>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .receipt-box {
            text-align: left;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .receipt-box h2 {
            color: #007bff;
            margin-bottom: 15px;
        }
        .receipt-box p {
            margin: 5px 0;
            font-size: 16px;
        }
        .actions {
            margin-top: 20px;
        }
        button, .btn {
            padding: 10px 20px;
            font-size: 16px;
            color: #ffffff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        button:hover, .btn:hover {
            background-color: #0056b3;
        }
        .btn {
            display: inline-block;
            margin: 10px 0;
        }
    </style>
</body>
</html>
