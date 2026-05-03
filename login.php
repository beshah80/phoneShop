<?php
include_once 'includes/config.php';

// If already logged in, redirect to home
if(isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
}
if(isset($_SESSION['admin_id'])) {
    header('Location: admin/dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($filter_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = ['Invalid email format!'];
        header('Location: login.php');
        exit();
    }
    $email = $filter_email;
    $pass = $_POST['pass'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM `users` WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($pass, $row['password'])) {
            // Regeneration of session ID for security (prevents session fixation)
            session_regenerate_id(true);

            if ($row['user_type'] == 'admin') {
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['admin_email'] = $row['email'];
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['user_type'] = 'admin'; // Essential for header check
                header('Location: admin/dashboard.php');
                exit();
            } else {
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_type'] = $row['user_type'];
                header('Location: home.php');
                exit();
            }
        } else {
            $_SESSION['message'] = ['Incorrect password!'];
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['message'] = ['No account found with this email!'];
        header('Location: login.php');
        exit();
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - PhoneSell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=1.2">
</head>
<body style="background: #f4f4f4;">

<?php
if (isset($_SESSION['message'])) {
    foreach ($_SESSION['message'] as $msg) {
        echo '
        <div class="message">
            <span>' . htmlspecialchars($msg) . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
    unset($_SESSION['message']);
}
?>

<section class="form-container">
    <form action="" method="post">
        <h3>Sign In</h3>
        <p style="margin-bottom: 2rem; font-size: 1.4rem;">Welcome back! Sign in to manage your ads and orders.</p>
        
        <input type="email" name="email" class="box" placeholder="Email Address" required>
        <input type="password" name="pass" class="box" placeholder="Password" required>
        
        <input type="submit" class="btn" name="submit" value="Sign In">
        
        <p>Don't have an account? <a href="register.php">Create one for free</a></p>
    </form>
</section>

</body>
</html>