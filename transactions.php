<?php
// Include the database connection
include 'db.php';

// Start session to check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch transactions for the logged-in user
$sql = "SELECT sale_date, delivery_time, total_sales, profit, payment_status FROM transactions";
$result = $conn->query($sql);

// Initialize totals
$overall_sales = 0;
$overall_profit = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - ReJo Sales</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Styling for the table and pie chart */
        .container {
            margin: 2rem;
            padding: 2rem;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
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

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Pie Chart Styling */
        .pie-chart-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }

        .summary {
            font-size: 1.2rem;
            text-align: center;
            margin-top: 1rem;
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
            <li><a href="cart.php">Cart</a></li>
            <li><a href="transactions.php" class="active">Transactions</a></li>
            <li><a href="signup.php" class="auth-link">Signup</a></li>
            <li><a href="login.php" class="auth-link">Login</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</section>

<div class="container">
    <h1>Transactions</h1>

    <table>
    <thead>
        <tr>
            <th>Sale Date</th>
            <th>Total Sales</th>
            <th>Profit</th>
            <th>Delivery Time</th>
            <th>Payment Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $overall_sales += $row['total_sales'];
                $overall_profit += $row['profit'];
                echo "<tr>
                        <td>" . date("Y-m-d", strtotime($row['sale_date'])) . "</td>
                        <td>Ksh " . number_format($row['total_sales'], 2) . "</td>
                        <td>Ksh " . number_format($row['profit'], 2) . "</td>
                        <td>" . $row['delivery_time'] . "</td>
                        <td>" . $row['payment_status'] . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No transactions found.</td></tr>";
        }
        ?>
        <!-- Display overall totals -->
        <tr>
            <td><strong>Total</strong></td>
            <td><strong>Ksh <?php echo number_format($overall_sales, 2); ?></strong></td>
            <td><strong>Ksh <?php echo number_format($overall_profit, 2); ?></strong></td>
        </tr>
    </tbody>
</table>

    <!-- Pie chart -->
    <div class="pie-chart-container">
        <h3>Sales and Profit Breakdown</h3>
        <canvas id="salesProfitChart"></canvas>
    </div>

    <!-- Summary -->
    <div class="summary">
        <p>Total Sales: Ksh <?php echo number_format($overall_sales, 2); ?></p>
        <p>Total Profit: Ksh <?php echo number_format($overall_profit, 2); ?></p>
    </div>

</div>

<script>
    // Pie chart for sales and profit
    const ctx = document.getElementById('salesProfitChart').getContext('2d');
    const salesProfitChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Total Sales', 'Total Profit'],
            datasets: [{
                data: [<?php echo $overall_sales; ?>, <?php echo $overall_profit; ?>],
                backgroundColor: ['#4caf50', '#ff9800'],
                borderColor: ['#fff', '#fff'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': Ksh ' + tooltipItem.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>

</body>
</html>

<?php
$conn->close();
?>
