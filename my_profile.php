<?php
include 'includes/config.php';

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

    if (!password_verify($current_password, $user['password'])) {
        $messages[] = 'Current password is incorrect!';
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $messages[] = 'Invalid email format!';
        } else {
            $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? AND id != ?");
            mysqli_stmt_bind_param($stmt, "si", $email, $user_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) > 0) {
                $messages[] = 'Email already exists!';
            } else {
                $update_query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $update_query);
                mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $user_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $messages[] = 'success:Profile updated successfully!';
                
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
                        $messages[] = 'success:Password updated successfully!';
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
    <title>Settings - PhoneSell Ethiopia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=2.3">
    <style>
        body { background: #f8f9fa; }
        .profile-container { max-width: 900px; margin: 3rem auto; padding: 0 2rem; }
        
        .compact-header { 
            background: #fff; 
            padding: 2rem; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
            display: flex; 
            align-items: center; 
            gap: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #eee;
        }

        .avatar-small { 
            width: 50px; 
            height: 50px; 
            background: #3db83a; 
            color: #fff; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 2rem; 
            font-weight: 800; 
        }

        .header-info h1 { font-size: 1.8rem; font-weight: 800; color: #333; margin: 0; }
        .header-info p { font-size: 1.2rem; color: #3db83a; font-weight: 700; margin: 0; }

        .compact-card { 
            background: #fff; 
            padding: 2.5rem; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
            border: 1px solid #eee;
        }

        .compact-card h2 { font-size: 1.6rem; margin-bottom: 2rem; color: #333; font-weight: 800; border-bottom: 1px solid #f8f9fa; padding-bottom: 0.8rem; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.6rem; font-weight: 700; font-size: 1.3rem; color: #666; }
        .form-group input { 
            width: 100%; 
            padding: 1.1rem; 
            border: 1px solid #ddd; 
            border-radius: 6px; 
            font-size: 1.4rem; 
            background: #fafafa;
            transition: 0.2s;
        }
        .form-group input:focus { border-color: #3db83a; background: #fff; box-shadow: 0 0 8px rgba(61, 184, 58, 0.1); }

        .save-btn { 
            background: #3db83a; 
            color: #fff; 
            padding: 1.2rem 3rem; 
            border-radius: 6px; 
            font-size: 1.5rem; 
            font-weight: 800; 
            cursor: pointer; 
            transition: 0.2s; 
            border: none;
            float: right;
            margin-top: 1rem;
        }
        .save-btn:hover { background: #34a832; transform: translateY(-1px); }

        .msg-box { padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 1.3rem; font-weight: 600; text-align: center; }
        .msg-success { background: #f0fdf4; color: #3db83a; border: 1px solid #3db83a; }
        .msg-error { background: #fef2f2; color: #ef4444; border: 1px solid #ef4444; }

        @media (max-width: 768px) { .form-row { grid-template-columns: 1fr; gap: 0; } }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="profile-container">
    <div class="compact-header">
        <div class="avatar-small"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></div>
        <div class="header-info">
            <h1>Account Settings</h1>
            <p><i class="fas fa-check-circle"></i> Verified: <?php echo htmlspecialchars($user['name']); ?></p>
        </div>
    </div>

    <div class="compact-card">
        <?php foreach ($messages as $msg): ?>
            <div class="msg-box <?php echo strpos($msg, 'success') !== false ? 'msg-success' : 'msg-error'; ?>">
                <?php echo htmlspecialchars(str_replace('success:', '', $msg)); ?>
            </div>
        <?php endforeach; ?>

        <form action="" method="post">
            <h2 style="margin-top:0;">Personal Details</h2>
            <div class="form-row">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
            </div>

            <h2 style="margin-top:1rem;">Security</h2>
            <div class="form-group" style="max-width: 50%;">
                <label>Current Password <span style="color: #ef4444;">*</span></label>
                <input type="password" name="current_password" placeholder="Required to save changes" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" placeholder="Leave blank to keep same">
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_new_password" placeholder="Re-type new password">
                </div>
            </div>

            <div style="overflow: hidden; border-top: 1px solid #eee; margin-top: 2rem; padding-top: 1rem;">
                <button type="submit" name="update_profile" class="save-btn">SAVE CHANGES</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>