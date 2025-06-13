<?php
@include 'config.php';

if (isset($_POST['submit'])) {
    $filter_name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $name = mysqli_real_escape_string($conn, $filter_name);
    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $email = mysqli_real_escape_string($conn, $filter_email);
    $filter_pass = filter_var($_POST['pass'], FILTER_SANITIZE_SPECIAL_CHARS);
    $filter_cpass = filter_var($_POST['cpass'], FILTER_SANITIZE_SPECIAL_CHARS);

    $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');

    if (mysqli_num_rows($select_users) > 0) {
        $message[] = 'user already exists!';
    } else {
        if ($filter_pass != $filter_cpass) {
            $message[] = 'confirm password does not match!';
        } else {
            $pass = password_hash($filter_pass, PASSWORD_DEFAULT);
            mysqli_query($conn, "INSERT INTO `users`(name, email, password, user_type) VALUES('$name', '$email', '$pass', 'user')") or die('query failed');
            $_SESSION['message'] = ['registered successfully!'];
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
    <title>sign up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

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
        <h3>sign up</h3>
        <input type="text" name="name" class="box" placeholder="enter your name" required>
        <input type="email" name="email" class="box" placeholder="enter your email" required>
        <input type="password" name="pass" class="box" placeholder="enter your password" required>
        <input type="password" name="cpass" class="box" placeholder="confirm your password" required>
        <input type="submit" class="btn" name="submit" value="sign up">
        <p>already have an account? <a href="login.php">sign in</a></p>
    </form>
</section>

</body>
</html>