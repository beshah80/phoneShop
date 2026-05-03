<?php
@include 'includes/config.php';

if (isset($_POST['submit'])) {
    $filter_name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $name = mysqli_real_escape_string($conn, $filter_name);
    
    $filter_phone = filter_var($_POST['phone'], FILTER_SANITIZE_SPECIAL_CHARS);
    $phone = mysqli_real_escape_string($conn, $filter_phone);

    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $email = mysqli_real_escape_string($conn, $filter_email);
    
    $filter_pass = filter_var($_POST['pass'], FILTER_SANITIZE_SPECIAL_CHARS);
    $filter_cpass = filter_var($_POST['cpass'], FILTER_SANITIZE_SPECIAL_CHARS);

    $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');

    if (mysqli_num_rows($select_users) > 0) {
        $message[] = 'User with this email already exists!';
    } else {
        if ($filter_pass != $filter_cpass) {
            $message[] = 'Confirm password does not match!';
        } else {
            $pass = password_hash($filter_pass, PASSWORD_DEFAULT);
            // Insert with phone number
            mysqli_query($conn, "INSERT INTO `users`(name, email, phone_number, password, user_type) VALUES('$name', '$email', '$phone', '$pass', 'user')") or die('query failed');
            $_SESSION['message'] = ['Registered successfully! Welcome to PhoneSell.'];
            header('location:login.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - PhoneSell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=1.1">
</head>
<body style="background: #f4f4f4;">

<?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo '
        <div class="message">
            <span>' . htmlspecialchars($msg) . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>

<section class="form-container">
    <form action="" method="post">
        <h3>Join PhoneSell</h3>
        <p style="margin-bottom: 2rem; font-size: 1.4rem;">The best place to buy and sell phones in Ethiopia.</p>
        
        <input type="text" name="name" class="box" placeholder="Full Name" required>
        <input type="tel" name="phone" class="box" placeholder="Phone Number (e.g. +251...)" required>
        <input type="email" name="email" class="box" placeholder="Email Address" required>
        <input type="password" name="pass" class="box" placeholder="Create Password" required>
        <input type="password" name="cpass" class="box" placeholder="Confirm Password" required>
        
        <input type="submit" class="btn" name="submit" value="Sign Up Now">
        
        <p>Already a member? <a href="login.php">Sign In here</a></p>
    </form>
</section>

</body>
</html>