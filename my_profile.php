<?php
@include 'includes/config.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: login.php');
    exit();
}

// Fetch user info
$stmt = mysqli_prepare($conn, "SELECT name, email, password FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'] ?? '';
    $confirm_new_password = $_POST['confirm_new_password'] ?? '';

    // Validate current password
    if (!password_verify($current_password, $user['password'])) {
        $messages[] = 'Current password is incorrect!';
    } else {
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $messages[] = 'Invalid email format!';
        } else {
            // Check if email is taken by another user
            $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? AND id != ?");
            mysqli_stmt_bind_param($stmt, "si", $email, $user_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) > 0) {
                $messages[] = 'Email already exists!';
            } else {
                // Update name and email
                $update_query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $update_query);
                mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $user_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $messages[] = 'Profile updated successfully!';
                // Update password if provided
                if (!empty($new_password)) {
                    if ($new_password !== $confirm_new_password) {
                        $messages[] = 'New passwords do not match!';
                    } elseif (strlen($new_password) < 6) {
                        $messages[] = 'New password must be at least 6 characters!';
                    } else {
                        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                        $stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE id = ?");
                        mysqli_stmt_bind_param($stmt, "si", $hashed, $user_id);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                        $messages[] = 'Password updated successfully!';
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .profile-form {
            max-width: 400px;
            margin: 3rem auto;
            background: #222;
            padding: 2rem 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .profile-form h2 {
            color: #ffd700;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .profile-form label {
            color: #fff;
            font-size: 1.2rem;
            margin-bottom: .3rem;
            display: block;
        }
        .profile-form input[type="text"],
        .profile-form input[type="email"],
        .profile-form input[type="password"] {
            width: 100%;
            padding: .8rem;
            margin-bottom: 1.2rem;
            border-radius: .5rem;
            border: 1px solid #444;
            background: #333;
            color: #fff;
        }
        .profile-form .btn {
            width: 100%;
            background: #ffd700;
            color: #222;
            font-weight: bold;
            border: none;
            padding: .9rem;
            border-radius: .5rem;
            cursor: pointer;
            font-size: 1.1rem;
        }
        .profile-form .btn:hover {
            background: #fff;
            color: #222;
        }
        .message {
            background: #ffd700;
            color: #222;
            padding: .7rem 1rem;
            border-radius: .5rem;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>
<section>
    <form class="profile-form" method="POST" action="">
        <h2>My Profile</h2>
        <?php foreach ($messages as $msg): ?>
            <div class="message"><?php echo htmlspecialchars($msg); ?></div>
        <?php endforeach; ?>
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required maxlength="100">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required maxlength="100">
        <label for="current_password">Current Password <span style="color:#ffd700">*</span></label>
        <input type="password" id="current_password" name="current_password" required minlength="6" maxlength="100" placeholder="Enter current password">
        <label for="new_password">New Password</label>
        <input type="password" id="new_password" name="new_password" minlength="6" maxlength="100" placeholder="Enter new password (optional)">
        <label for="confirm_new_password">Confirm New Password</label>
        <input type="password" id="confirm_new_password" name="confirm_new_password" minlength="6" maxlength="100" placeholder="Confirm new password (optional)">
        <input type="submit" class="btn" name="update_profile" value="Update Profile">
    </form>
</section>
</body>
</html> 