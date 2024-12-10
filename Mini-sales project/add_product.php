<?php
// Database connection
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "sales_system"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Get product details
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Image upload logic
    $image = $_FILES['image']['name'];
    $image_temp = $_FILES['image']['tmp_name'];

    // Define the upload directory
    $upload_directory = "products/";  // Folder to store images

    // Create the upload folder if it doesn't exist
    if (!is_dir($upload_directory)) {
        mkdir($upload_directory, 0777, true);  // Create the folder with permissions
    }

    // Generate a unique filename for the image
    $image_path = $upload_directory . uniqid() . "_" . basename($image);

    // Move the image to the uploads folder
    if (move_uploaded_file($image_temp, $image_path)) {
        // Insert product into the database
        $sql = "INSERT INTO products (name, description, price, image_path) VALUES ('$product_name', '$description', '$price', '$image_path')";

        if ($conn->query($sql) === TRUE) {
            echo "New product added successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error uploading image.";
    }
}

$conn->close();
?>
