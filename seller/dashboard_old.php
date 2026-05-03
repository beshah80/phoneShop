<?php
include '../includes/config.php';

$user_id = $_SESSION['user_id'] ?? null;
if(!isset($user_id)) { header('location:../login.php'); exit(); }

// Get seller statistics
$stats = [
    'total_products' => mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `products` WHERE seller_id = '$user_id'")),
    'active_products' => mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `products` WHERE seller_id = '$user_id' AND status = 'available'")),
    'sold_products' => mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `products` WHERE seller_id = '$user_id' AND status = 'sold'")),
    'total_views' => 1250, // Placeholder - would need views tracking
    'total_revenue' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(price) as total FROM `products` WHERE seller_id = '$user_id' AND status = 'sold'"))['total'] ?? 0
];

// Get recent products
$recent_products = mysqli_query($conn, "SELECT * FROM `products` WHERE seller_id = '$user_id' ORDER BY id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Ads - PhoneSell Ethiopia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .seller-dashboard {
            background: #f8f9fa;
            min-height: 100vh;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, #3db83a, #2ea02d);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .dashboard-header .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .dashboard-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
        }
        
        .dashboard-header p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin: 0.5rem 0 0 0;
        }
        
        .post-ad-btn {
            background: white;
            color: #3db83a;
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .post-ad-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        
        .dashboard-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }
        
        .stat-icon.products { background: #e3f2fd; color: #2196f3; }
        .stat-icon.active { background: #e8f5e8; color: #4caf50; }
        .stat-icon.sold { background: #fff3e0; color: #ff9800; }
        .stat-icon.revenue { background: #fce4ec; color: #e91e63; }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 1rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .dashboard-tabs {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            overflow: hidden;
        }
        
        .tab-nav {
            display: flex;
            border-bottom: 1px solid #eee;
        }
        
        .tab-btn {
            flex: 1;
            padding: 1.5rem;
            background: none;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }
        
        .tab-btn.active {
            color: #3db83a;
            border-bottom-color: #3db83a;
            background: #f8f9fa;
        }
        
        .tab-content {
            padding: 2rem;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        
        .product-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-3px);
        }
        
        .product-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .product-info {
            padding: 1.5rem;
        }
        
        .product-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .product-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: #3db83a;
            margin-bottom: 1rem;
        }
        
        .product-status {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-active {
            background: #e8f5e8;
            color: #4caf50;
        }
        
        .status-sold {
            background: #fff3e0;
            color: #ff9800;
        }
        
        .product-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .btn-small {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .btn-edit {
            background: #e3f2fd;
            color: #2196f3;
        }
        
        .btn-delete {
            background: #ffebee;
            color: #f44336;
        }
        
        .btn-small:hover {
            transform: translateY(-1px);
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1rem;
        }
        
        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .empty-state p {
            margin-bottom: 2rem;
        }
        
        @media (max-width: 768px) {
            .dashboard-header .container {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .tab-nav {
                flex-direction: column;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="seller-dashboard">
    
<?php include '../includes/header.php'; ?>

<header class="dashboard-header">
    <div class="container">
        <div>
            <h1>My Phone Ads</h1>
            <p>Manage your phone listings and track sales performance</p>
        </div>
        <a href="add_product.php" class="post-ad-btn">
            <i class="fas fa-plus"></i> Post New Ad
        </a>
    </div>
</header>

<div class="dashboard-content">
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon products">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <div class="stat-number"><?php echo $stats['total_products']; ?></div>
            <div class="stat-label">Total Ads</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon active">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number"><?php echo $stats['active_products']; ?></div>
            <div class="stat-label">Active</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon sold">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-number"><?php echo $stats['sold_products']; ?></div>
            <div class="stat-label">Sold</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon revenue">
                <i class="fas fa-birr"></i>
            </div>
            <div class="stat-number"><?php echo number_format($stats['total_revenue']); ?></div>
            <div class="stat-label">Revenue (ETB)</div>
        </div>
    </div>

    <!-- Products Tabs -->
    <div class="dashboard-tabs">
        <div class="tab-nav">
            <button class="tab-btn active" onclick="showTab('active')">Active Ads</button>
            <button class="tab-btn" onclick="showTab('sold')">Sold</button>
            <button class="tab-btn" onclick="showTab('all')">All Ads</button>
        </div>
        
        <div class="tab-content">
            <div id="active-tab">
                <?php if(mysqli_num_rows($recent_products) > 0): ?>
                    <div class="products-grid">
                        <?php while($product = mysqli_fetch_assoc($recent_products)): ?>
                            <?php if($product['status'] == 'available'): ?>
                            <div class="product-card">
                                <img src="../assets/uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-img">
                                <div class="product-info">
                                    <h3 class="product-title"><?php echo $product['name']; ?></h3>
                                    <div class="product-price"><?php echo number_format($product['price']); ?> ETB</div>
                                    <span class="product-status status-active">Active</span>
                                    <div class="product-actions">
                                        <a href="update_product.php?id=<?php echo $product['id']; ?>" class="btn-small btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn-small btn-delete" onclick="return confirm('Delete this ad?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-mobile-alt"></i>
                        <h3>No Active Ads</h3>
                        <p>Start selling by posting your first phone ad</p>
                        <a href="add_product.php" class="post-ad-btn">Post Your First Ad</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Remove active class from all tabs
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    
    // Add active class to clicked tab
    event.target.classList.add('active');
    
    // Here you would typically load different content via AJAX
    // For now, we'll just show a message
    const tabContent = document.querySelector('.tab-content');
    if(tabName === 'sold') {
        tabContent.innerHTML = '<div class="empty-state"><i class="fas fa-shopping-cart"></i><h3>Sold Items</h3><p>Items you have sold will appear here</p></div>';
    } else if(tabName === 'all') {
        tabContent.innerHTML = '<div class="empty-state"><i class="fas fa-list"></i><h3>All Ads</h3><p>Complete ad history will appear here</p></div>';
    } else {
        location.reload(); // Reload to show active ads
    }
}
</script>

</body>
</html>
