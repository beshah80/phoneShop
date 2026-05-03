<?php
include 'includes/config.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!isset($user_id)) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - PhoneSell Ethiopia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=2.1">
    <style>
        body { background: #f8f9fa; }
        .orders-container { max-width: 1000px; margin: 4rem auto; padding: 0 2rem; }
        .orders-header { margin-bottom: 3rem; }
        .orders-header h1 { font-size: 2.8rem; font-weight: 800; color: #333; }
        
        .order-card { 
            background: #fff; 
            border-radius: 12px; 
            padding: 2.5rem; 
            margin-bottom: 2rem; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #eee;
        }

        .order-main-info { display: flex; gap: 2rem; align-items: center; }
        .order-icon { width: 50px; height: 50px; background: #f0fdf4; color: #3db83a; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 2rem; }
        
        .order-title { font-size: 1.8rem; font-weight: 700; color: #333; margin-bottom: 0.5rem; }
        .order-meta { font-size: 1.3rem; color: #888; }
        
        .order-price { font-size: 1.8rem; font-weight: 800; color: #3db83a; text-align: right; }
        .status-badge { 
            display: inline-block; 
            padding: 0.6rem 1.2rem; 
            border-radius: 50px; 
            font-size: 1.1rem; 
            font-weight: 800; 
            text-transform: uppercase;
            margin-top: 1rem;
        }
        .status-pending { background: #fff7ed; color: #f97316; }
        .status-completed { background: #f0fdf4; color: #22c55e; }
        
        .order-actions { text-align: right; }
        .view-btn { 
            display: inline-block; 
            margin-top: 1.5rem; 
            padding: 0.8rem 1.5rem; 
            border: 1px solid #ddd; 
            border-radius: 6px; 
            font-size: 1.3rem; 
            font-weight: 600; 
            color: #555; 
            text-decoration: none; 
            transition: 0.3s; 
        }
        .view-btn:hover { background: #f8f9fa; border-color: #3db83a; color: #3db83a; }

        .empty-orders { text-align: center; padding: 10rem 0; background: #fff; border-radius: 12px; }
        .empty-orders i { font-size: 5rem; color: #eee; margin-bottom: 2rem; display: block; }
        .go-shopping { background: #3db83a; color: #fff; padding: 1.2rem 3rem; border-radius: 6px; font-weight: 800; text-decoration: none; display: inline-block; margin-top: 2rem; font-size: 1.5rem; }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="orders-container">
    <div class="orders-header">
        <h1>My Orders</h1>
        <p style="font-size: 1.4rem; color: #777;">Track and manage your phone purchases</p>
    </div>

    <div class="orders-list">
        <?php
        $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' ORDER BY id DESC") or die('query failed');
        if (mysqli_num_rows($select_orders) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
        ?>
        <div class="order-card">
            <div class="order-main-info">
                <div class="order-icon"><i class="fas fa-shopping-bag"></i></div>
                <div>
                    <div class="order-title">Order #<?php echo $fetch_orders['id']; ?></div>
                    <div class="order-meta">Placed on: <?php echo htmlspecialchars($fetch_orders['placed_on']); ?></div>
                    <div class="order-meta">Payment Method: <?php echo htmlspecialchars($fetch_orders['method']); ?></div>
                </div>
            </div>

            <div class="order-actions">
                <div class="order-price"><?php echo number_format($fetch_orders['total_price']); ?> ETB</div>
                <span class="status-badge <?php echo $fetch_orders['payment_status'] == 'pending' ? 'status-pending' : 'status-completed'; ?>">
                    <?php echo htmlspecialchars(ucfirst($fetch_orders['payment_status'])); ?>
                </span>
                <br>
                <a href="#" class="view-btn">View Details</a>
            </div>
        </div>
        <?php
            }
        } else {
        ?>
        <div class="empty-orders">
            <i class="fas fa-receipt"></i>
            <h2 style="font-size: 2rem; color: #333;">No Orders Yet</h2>
            <p style="font-size: 1.4rem; color: #777;">You haven't purchased any phones yet.</p>
            <a href="home.php" class="go-shopping">START SHOPPING</a>
        </div>
        <?php
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>