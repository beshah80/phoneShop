<?php
include_once 'includes/config.php';
include_once 'core/functions.php';

$user_id = $_SESSION['user_id'] ?? null;
$msg = handle_add_to_cart($conn, $user_id);
if($msg) $message[] = $msg;

// Category Filter Logic
$category_filter = $_GET['category'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PhoneSell - Best Phones in Ethiopia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background: var(--jiji-bg);">
    
<?php include 'includes/header.php'; ?>

<section class="jiji-hero">
    <div class="container">
        <h2>Find Your Next Phone</h2>
        <form action="search_page.php" method="GET" class="hero-search-box">
            <div class="region-select"><i class="fas fa-map-marker-alt"></i> <span>Ethiopia</span></div>
            <input type="text" name="search" placeholder="Search for iPhone, Samsung, Pixel..." required>
            <button type="submit" class="hero-search-btn"><i class="fas fa-search"></i></button>
        </form>
    </div>
</section>

<div class="main-content">
    <!-- Sidebar Component (Functional Filters) -->
    <aside class="jiji-sidebar">
        <?php 
            $cats = [
                ['name'=>'Apple iPhone', 'icon'=>'fab fa-apple', 'id'=>'apple'],
                ['name'=>'Samsung Galaxy', 'icon'=>'fas fa-mobile-alt', 'id'=>'samsung'],
                ['name'=>'Google Pixel', 'icon'=>'fab fa-google', 'id'=>'google'],
                ['name'=>'Accessories', 'icon'=>'fas fa-headphones', 'id'=>'accessories'],
                ['name'=>'Tablets', 'icon'=>'fas fa-tablet-alt', 'id'=>'tablets']
            ];
            foreach($cats as $cat): 
        ?>
        <a href="home.php?category=<?php echo $cat['id']; ?>" class="sidebar-item <?php echo ($category_filter == $cat['id']) ? 'active' : ''; ?>">
            <i class="<?php echo $cat['icon']; ?>"></i>
            <div class="sidebar-item-info">
                <h4><?php echo $cat['name']; ?></h4>
                <p>New & Used</p>
            </div>
        </a>
        <?php endforeach; ?>
        <hr style="margin: 1rem 0; border: none; border-top: 1px solid #eee;">
        <a href="home.php" class="sidebar-item">
            <i class="fas fa-th-list"></i>
            <div class="sidebar-item-info">
                <h4>All Brands</h4>
                <p>View Everything</p>
            </div>
        </a>
    </aside>

    <!-- Main Grid -->
    <main>
        <h3 style="font-size: 1.8rem; margin-bottom: 2rem; color: #555;">
            <?php echo $category_filter ? "Filtering by: " . ucfirst($category_filter) : "Recommended for You"; ?>
        </h3>
        <div class="jiji-grid">
            <?php
                $sql = "SELECT * FROM `products` WHERE 1=1";
                if($category_filter) {
                    $sql .= " AND (name LIKE '%$category_filter%' OR details LIKE '%$category_filter%')";
                }
                $sql .= " ORDER BY is_premium DESC, id DESC LIMIT 12";
                
                $select_products = mysqli_query($conn, $sql) or die('query failed');
                if(mysqli_num_rows($select_products) > 0){
                    while($product = mysqli_fetch_assoc($select_products)){
                        include 'includes/components/product_card.php';
                    }
                }else{
                    echo '<p class="empty">No products found in this category!</p>';
                }
            ?>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>