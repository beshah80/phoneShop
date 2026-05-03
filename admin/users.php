<?php
include '../includes/config.php';

$admin_id = $_SESSION['admin_id'] ?? null;

if (!isset($admin_id)) {
    header('Location: ../login.php');
    exit();
}

// Create seller_applications table if it doesn't exist
$create_table_query = "CREATE TABLE IF NOT EXISTS `seller_applications` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `business_name` varchar(255) NOT NULL,
    `business_address` text NOT NULL,
    `phone_number` varchar(20) NOT NULL,
    `email` varchar(255) NOT NULL,
    `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    CONSTRAINT `seller_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

mysqli_query($conn, $create_table_query) or die('Error creating seller_applications table: ' . mysqli_error($conn));

// Handle delete
if (isset($_GET['delete'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die(mysqli_error($conn));
    $messages[] = 'User deleted successfully!';
    header('Location: users.php');
    exit();
}

// Handle update
if (isset($_POST['update'])) {
    $update_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $user_type = mysqli_real_escape_string($conn, trim($_POST['user_type']));

    if (empty($name) || empty($email) || empty($user_type)) {
        $messages[] = 'All fields are required!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $messages[] = 'Invalid email format!';
    } else {
        $check_email = mysqli_query($conn, "SELECT id FROM `users` WHERE email = '$email' AND id != '$update_id'");
        if (mysqli_num_rows($check_email) > 0) {
            $messages[] = 'Email already exists!';
        } else {
            mysqli_query($conn, "UPDATE `users` SET name = '$name', email = '$email', user_type = '$user_type' WHERE id = '$update_id'") or die(mysqli_error($conn));
            $messages[] = 'User updated successfully!';
        }
    }
}

// Handle seller applications
if (isset($_POST['approve_application'])) {
    $application_id = (int)$_POST['application_id'];
    $csrf_token = $_POST['csrf_token'];

    if ($csrf_token !== $_SESSION['csrf_token']) {
        $messages[] = 'Invalid CSRF token!';
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE `seller_applications` SET status = 'approved' WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $application_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $messages[] = 'Seller application approved!';
    }
}

if (isset($_POST['reject_application'])) {
    $application_id = (int)$_POST['application_id'];
    $csrf_token = $_POST['csrf_token'];

    if ($csrf_token !== $_SESSION['csrf_token']) {
        $messages[] = 'Invalid CSRF token!';
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE `seller_applications` SET status = 'rejected' WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $application_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $messages[] = 'Seller application rejected!';
    }
}

$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Handle search
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, trim($_GET['search']));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>

<?php include '../includes/admin_header.php'; ?>

<section class="users">
    <h1 class="title">Customer Accounts</h1>

    <!-- Search Form -->
    <div class="search-form">
        <form action="" method="GET">
            <input type="text" name="search" class="box" placeholder="Search by username or email..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="btn"><i class="fas fa-search"></i> Search</button>
            <?php if ($search_query): ?>
                <a href="users.php" class="btn"><i class="fas fa-times"></i> Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Message Display -->
    <?php
    if (isset($messages)) {
        foreach ($messages as $msg) {
            echo '<p class="message"><span>' . htmlspecialchars($msg) . '</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></p>';
        }
    }
    ?>

    <!-- Users Table -->
    <div class="table-container">
        <table class="modern-admin-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>User Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM `users`";
                if ($search_query) {
                    $query .= " WHERE name LIKE '%$search_query%' OR email LIKE '%$search_query%'";
                }
                $select_users = mysqli_query($conn, $query) or die(mysqli_error($conn));
                if (mysqli_num_rows($select_users) > 0) {
                    while ($fetch_users = mysqli_fetch_assoc($select_users)) {
                ?>
                <tr>
                    <td><?php echo $fetch_users['id']; ?></td>
                    <td><?php echo htmlspecialchars($fetch_users['name']); ?></td>
                    <td><?php echo htmlspecialchars($fetch_users['email']); ?></td>
                    <td><span class="user-type-badge user-type-<?php echo htmlspecialchars($fetch_users['user_type']); ?>"><?php echo htmlspecialchars(ucfirst($fetch_users['user_type'])); ?></span></td>
                    <td>
                        <a href="users.php?update=<?php echo $fetch_users['id']; ?>" class="option-btn"><i class="fas fa-edit"></i> Update</a>
                        <a href="users.php?delete=<?php echo $fetch_users['id']; ?>" class="delete-btn" onclick="return confirm('Delete this user?');"><i class="fas fa-trash"></i> Delete</a>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="5" class="empty">No users found!</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Seller Applications Section -->
    <h1 class="title">Seller Applications</h1>
    <div class="table-container">
        <table class="modern-admin-table">
            <thead>
                <tr>
                    <th>Application ID</th>
                    <th>User</th>
                    <th>Business Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT sa.*, u.name as user_name, u.email as user_email 
                         FROM `seller_applications` sa 
                         JOIN `users` u ON sa.user_id = u.id 
                         ORDER BY sa.created_at DESC";
                $select_applications = mysqli_query($conn, $query) or die(mysqli_error($conn));
                if (mysqli_num_rows($select_applications) > 0) {
                    while ($fetch_app = mysqli_fetch_assoc($select_applications)) {
                ?>
                <tr>
                    <td><?php echo $fetch_app['id']; ?></td>
                    <td><?php echo htmlspecialchars($fetch_app['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($fetch_app['business_name']); ?></td>
                    <td><?php echo htmlspecialchars($fetch_app['user_email']); ?></td>
                    <td><?php echo htmlspecialchars($fetch_app['phone_number']); ?></td>
                    <td><span class="seller-status-badge status-<?php echo htmlspecialchars($fetch_app['status']); ?>"><?php echo htmlspecialchars(ucfirst($fetch_app['status'])); ?></span></td>
                    <td>
                        <?php if ($fetch_app['status'] === 'pending'): ?>
                        <form action="" method="POST" style="display: inline;">
                            <input type="hidden" name="application_id" value="<?php echo $fetch_app['id']; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <button type="submit" name="approve_application" class="option-btn">Approve</button>
                            <button type="submit" name="reject_application" class="delete-btn">Reject</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="7" class="empty">No seller applications found!</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<script src="../assets/js/admin_script.js"></script>
</body>
</html>