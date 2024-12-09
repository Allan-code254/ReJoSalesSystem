<?php
// Start the session to store user data (if logged in)
session_start();

include('db.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form input values (email and password)
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL query to select the user by email
    $query = "SELECT id, username, password FROM users WHERE email = ?";
    
    if ($stmt = $conn->prepare($query)) {
        // Bind the parameter (email) to the query
        $stmt->bind_param("s", $email);
        
        // Execute the query
        $stmt->execute();
        
        // Store the result
        $stmt->store_result();
        
        // Check if the email exists in the database
        if ($stmt->num_rows > 0) {
            // Bind the result to variables
            $stmt->bind_result($user_id, $username, $hashed_password);
            $stmt->fetch();
            
            // Verify if the provided password matches the hashed password in the database
            if (password_verify($password, $hashed_password)) {
                // Password is correct, log the user in by storing session data
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                
                // Redirect to the homepage (index.php)
                header("Location: index.php");
                exit;
            } else {
                // Incorrect password
                $_SESSION['error_message'] = "Incorrect password. Please try again.";
                header("Location: login.php");
                exit;
            }
        } else {
            // No user found with the provided email
            $_SESSION['error_message'] = "No user found with this email.";
            header("Location: login.php");
            exit;
        }
        
        // Close the statement
        $stmt->close();
    } else {
        // If the query preparation fails
        $_SESSION['error_message'] = "Error: Could not prepare the query.";
        header("Location: login.php");
        exit;
    }
}

// Close the database connection
$conn->close();
?>
