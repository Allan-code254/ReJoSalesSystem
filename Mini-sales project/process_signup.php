<?php
// process_signup.php - Handles the signup form submission

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sales_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form input data and sanitize it
    $user_name = mysqli_real_escape_string($conn, $_POST['username']);
    $user_email = mysqli_real_escape_string($conn, $_POST['email']);
    $user_password = mysqli_real_escape_string($conn, $_POST['password']);

    // Validate if username, email, and password are provided
    if (empty($user_name) || empty($user_email) || empty($user_password)) {
        echo "All fields are required!";
        exit;
    }

    // Hash the password before storing
    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

    // SQL query to insert the data into the 'users' table
    $sql = "INSERT INTO users (username, email, password) VALUES ('$user_name', '$user_email', '$hashed_password')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Redirect to the login page on successful registration
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the connection
    $conn->close();
}
?>
