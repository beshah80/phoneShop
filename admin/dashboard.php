<?php
include '../includes/config.php';

$admin_id = $_SESSION['admin_id'] ?? null;
if(!isset($admin_id)) { header('location:../login.php'); exit(); }

// Dashboard stats
$stats = [
    'pendings' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_price) as total FROM `orders` WHERE payment_status = 'pending'"))['total'] ?? 0,
    'completes' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_price) as total FROM `orders` WHERE payment_status = 'completed'"))['total'] ?? 0,
    'orders' => mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `orders`")),
    'products' => mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `products`")),
    'users' => mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'")),
    'messages' => mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `message`"))
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - PhoneSell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>

<div class="admin-container">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-logo">PhoneSell Admin</div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="dashboard.php" class="active"><i class="fas fa-th-large"></i> Dashboard</a></li>
                <li><a href="products.php"><i class="fas fa-mobile-alt"></i> Phones</a></li>
                <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
                <li><a href="../home.php"><i class="fas fa-external-link-alt"></i> View Site</a></li>
                <li><a href="../logout.php" style="color: #d00000; margin-top: 2rem;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
            <h1 style="font-size: 2.5rem;">Overview</h1>
            <div style="font-size: 1.4rem; color: var(--text-gray);">Welcome back, <strong>Admin</strong></div>
        </header>

        <div class="dashboard-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
                <div class="stat-info">
                    <h3><?php echo number_format($stats['pendings']); ?> ETB</h3>
                    <p>Pending Payments</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color: #3db83a; background: #f0fdf4;"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <h3><?php echo number_format($stats['completes']); ?> ETB</h3>
                    <p>Completed Sales</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color: #ff9900; background: #fff7ed;"><i class="fas fa-shopping-bag"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['orders']; ?></h3>
                    <p>Total Orders</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color: #3b82f6; background: #eff6ff;"><i class="fas fa-mobile-alt"></i></div>
                <div class="stat-info">
                    <h3><?php echo $stats['products']; ?></h3>
                    <p>Phones for Sale</p>
                </div>
            </div>
        </div>

        <div class="admin-table-container">
            <h2 style="margin-bottom: 2rem; font-size: 1.8rem;">Recent Activity</h2>
            <p style="color: var(--text-gray);">Everything is looking great today. Your marketplace is active!</p>
            <!-- In a real scenario, we would list recent orders here -->
        </div>
    </main>
</div>

</body>
</html>