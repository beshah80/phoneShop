<?php
@include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($filter_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = ['Invalid email format!'];
        header('Location: login.php');
        exit();
    }
    $email = $filter_email;
    $filter_pass = htmlspecialchars($_POST['pass'], ENT_QUOTES, 'UTF-8');

    $stmt = mysqli_prepare($conn, "SELECT * FROM `users` WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($filter_pass, $row['password'])) {
            if ($row['user_type'] == 'admin') {
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['admin_email'] = $row['email'];
                $_SESSION['admin_id'] = $row['id'];
                header('Location: admin_page.php');
                exit();
            } elseif ($row['user_type'] == 'user') {
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_id'] = $row['id'];
                header('Location: home.php');
                exit();
            }
        } else {
            $_SESSION['message'] = ['Incorrect email or password!'];
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['message'] = ['Incorrect email or password!'];
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
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
if (isset($_SESSION['message'])) {
    foreach ($_SESSION['message'] as $msg) {
        echo '
        <div class="message">
            <span>' . htmlspecialchars($msg) . '</span>
            <i class="fas fa-times"></i>
        </div>
        ';
    }
    unset($_SESSION['message']);
}
?>

<section class="form-container">
    <form action="" method="post">
        <h3>Sign In</h3>
        <input type="email" name="email" class="box" placeholder="Enter your email" required>
        <input type="password" name="pass" class="box" placeholder="Enter your password" required>
        <input type="submit" class="btn" name="submit" value="Sign In">
        <p>Don't have an account? <a href="register.php">Sign Up</a></p>
    </form>
</section>

</body>
</html>