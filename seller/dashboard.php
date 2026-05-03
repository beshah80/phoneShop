<?php
include '../includes/config.php';

$user_id = $_SESSION['user_id'] ?? null;
if(!isset($user_id)) { header('location:../login.php'); exit(); }

// Get seller statistics
$stats = [
    'total_products' => mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `products` WHERE seller_id = '$user_id'")),
    'active_products' => mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `products` WHERE seller_id = '$user_id' AND status = 'available'")),
    'sold_products' => mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `products` WHERE seller_id = '$user_id' AND status = 'sold'")),
    'total_revenue' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(price) as total FROM `products` WHERE seller_id = '$user_id' AND status = 'sold'"))['total'] ?? 0
];

// Get recent products
$recent_products = mysqli_query($conn, "SELECT * FROM `products` WHERE seller_id = '$user_id' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Dashboard - PhoneSell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .seller-dashboard { background: #f8f9fa; min-height: 100vh; }
        .dashboard-header { background: #fff; border-bottom: 1px solid #eee; padding: 3rem 0; margin-bottom: 3rem; }
        .dashboard-header .container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; display: flex; justify-content: space-between; align-items: center; }
        .dashboard-header h1 { font-size: 2.5rem; font-weight: 800; color: #333; }
        .post-ad-btn { background: #3db83a; color: #fff; padding: 1.2rem 2.5rem; border-radius: 8px; font-weight: 800; font-size: 1.4rem; text-decoration: none; transition: 0.3s; border: none; cursor: pointer; }
        .post-ad-btn:hover { background: #34a832; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(61, 184, 58, 0.3); }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 2rem; margin-bottom: 4rem; max-width: 1200px; margin-left: auto; margin-right: auto; padding: 0 2rem; }
        .stat-card { background: #fff; padding: 2.5rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 2rem; }
        .stat-icon { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; }
        .stat-icon.products { background: #e3f2fd; color: #2196f3; }
        .stat-icon.revenue { background: #fdf2f2; color: #dc2626; }
        .stat-number { font-size: 2.2rem; font-weight: 800; color: #333; }
        .stat-label { font-size: 1.2rem; color: #777; text-transform: uppercase; }
        .products-section { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
        .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem; }
        .product-card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .product-img { width: 100%; height: 200px; object-fit: cover; }
        .product-info { padding: 2rem; }
        .product-title { font-size: 1.6rem; font-weight: 700; margin-bottom: 0.5rem; }
        .product-price { font-size: 1.8rem; font-weight: 800; color: #3db83a; margin-bottom: 1.5rem; }

        /* Empty State Styling */
        .empty-state { 
            text-align: center; 
            padding: 8rem 2rem; 
            background: #fff; 
            border-radius: 12px; 
            border: 1px dashed #ddd;
            margin-top: 2rem;
        }
        .empty-state i { 
            font-size: 6rem; 
            color: #eee; 
            margin-bottom: 2rem; 
            display: block;
        }
        .empty-state h3 { 
            font-size: 2.2rem; 
            font-weight: 800; 
            color: #333; 
            margin-bottom: 1rem; 
        }
        .empty-state p { 
            font-size: 1.5rem; 
            color: #777; 
            margin-bottom: 3rem; 
        }
    </style>
</head>
<body class="seller-dashboard">
    
<?php include '../includes/header.php'; ?>

<header class="dashboard-header">
    <div class="container">
        <h1>Seller Dashboard</h1>
        <!-- Trigger Global Modal -->
        <button onclick="openPostAdModal()" class="post-ad-btn"><i class="fas fa-plus"></i> POST NEW AD</button>
    </div>
</header>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon products"><i class="fas fa-mobile-alt"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo $stats['total_products']; ?></div>
            <div class="stat-label">Total Ads</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #e8f5e8; color: #3db83a;"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo $stats['active_products']; ?></div>
            <div class="stat-label">Active</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon revenue"><i class="fas fa-money-bill-wave"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo number_format($stats['total_revenue']); ?></div>
            <div class="stat-label">Revenue (ETB)</div>
        </div>
    </div>
</div>

<div class="products-section">
    <h2 style="font-size: 2rem; margin-bottom: 2rem; font-weight: 800;">Your Phone Listings</h2>
    
    <?php if(mysqli_num_rows($recent_products) > 0): ?>
        <div class="products-grid">
            <?php while($product = mysqli_fetch_assoc($recent_products)): ?>
                <div class="product-card">
                    <img src="../assets/uploads/<?php echo $product['image']; ?>" alt="" class="product-img">
                    <div class="product-info">
                        <h3 class="product-title"><?php echo $product['name']; ?></h3>
                        <div class="product-price"><?php echo number_format($product['price']); ?> ETB</div>
                        <div style="display: flex; gap: 1rem;">
                            <a href="update_product.php?id=<?php echo $product['id']; ?>" style="flex: 1; text-align: center; padding: 1rem; background: #f0f0f0; border-radius: 5px; font-weight: 600; color: #333;">Edit</a>
                            <a href="delete_product.php?id=<?php echo $product['id']; ?>" onclick="return confirm('Delete this ad?')" style="flex: 1; text-align: center; padding: 1rem; background: #fff1f1; border-radius: 5px; font-weight: 600; color: #dc2626;">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-mobile-alt"></i>
            <h3 style="font-size: 2rem; margin-bottom: 1rem;">No Active Ads</h3>
            <p style="font-size: 1.4rem; color: #777; margin-bottom: 3rem;">Start selling by posting your first phone ad today!</p>
            <button onclick="openPostAdModal()" class="post-ad-btn">Post Your First Ad</button>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
