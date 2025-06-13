<?php
@include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    
    if ($email) {
        // Check if email already exists
        $stmt = mysqli_prepare($conn, "SELECT id FROM newsletter_subscribers WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) === 0) {
            // Create newsletter_subscribers table if it doesn't exist
            $create_table = "CREATE TABLE IF NOT EXISTS newsletter_subscribers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL UNIQUE,
                subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                status ENUM('active', 'unsubscribed') DEFAULT 'active'
            )";
            mysqli_query($conn, $create_table);
            
            // Insert new subscriber
            $stmt = mysqli_prepare($conn, "INSERT INTO newsletter_subscribers (email) VALUES (?)");
            mysqli_stmt_bind_param($stmt, "s", $email);
            
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['message'] = "Thank you for subscribing to our newsletter!";
            } else {
                $_SESSION['message'] = "Error subscribing to newsletter. Please try again.";
            }
        } else {
            $_SESSION['message'] = "You are already subscribed to our newsletter!";
        }
    } else {
        $_SESSION['message'] = "Please enter a valid email address.";
    }
    
    // Redirect back to the previous page
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    // If not a POST request, redirect to home
    header('Location: home.php');
    exit();
}
?> 