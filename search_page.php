<?php

include_once 'includes/config.php';
include_once 'core/functions.php';

$user_id = $_SESSION['user_id'] ?? null;
$msg = handle_add_to_cart($conn, $user_id);
if($msg) $message[] = $msg;

// Enhanced search functionality
$search_query = $_GET['search'] ?? '';
$category_filter = $_GET['category'] ?? '';
$price_min = $_GET['price_min'] ?? '';
$price_max = $_GET['price_max'] ?? '';
$sort_by = $_GET['sort'] ?? 'newest';

// Build SQL query
$sql = "SELECT * FROM `products` WHERE 1=1";
$params = [];
$types = '';

if (!empty($search_query)) {
    $sql .= " AND (name LIKE ? OR details LIKE ?)";
    $search_term = "%$search_query%";
    $params[] = $search_term;
    $params[] = $search_term;
    $types .= 'ss';
}

if (!empty($category_filter)) {
    $sql .= " AND (name LIKE ? OR details LIKE ?)";
    $category_term = "%$category_filter%";
    $params[] = $category_term;
    $params[] = $category_term;
    $types .= 'ss';
}

if (!empty($price_min)) {
    $sql .= " AND price >= ?";
    $params[] = $price_min;
    $types .= 'i';
}

if (!empty($price_max)) {
    $sql .= " AND price <= ?";
    $params[] = $price_max;
    $types .= 'i';
}

// Sorting
switch($sort_by) {
    case 'price_low':
        $sql .= " ORDER BY price ASC";
        break;
    case 'price_high':
        $sql .= " ORDER BY price DESC";
        break;
    case 'name':
        $sql .= " ORDER BY name ASC";
        break;
    default:
        $sql .= " ORDER BY is_premium DESC, id DESC";
}

// Execute query
if (!empty($params)) {
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $select_products = mysqli_stmt_get_result($stmt);
} else {
    $select_products = mysqli_query($conn, $sql);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - PhoneSell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background: var(--jiji-bg);">
    
<?php include 'includes/header.php'; ?>

<section class="jiji-hero" style="padding: 2rem 7%;">
    <div class="container">
        <form action="search_page.php" method="GET" class="hero-search-box">
            <div class="region-select">
                <i class="fas fa-map-marker-alt"></i>
                <span>Ethiopia</span>
            </div>
            <input type="text" name="search" placeholder="Search for another phone..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" required>
            <button type="submit" class="hero-search-btn">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</section>

<div class="container" style="padding: 3rem 7%;">
    <h1 class="title" style="text-align: left; font-size: 2.5rem; color: var(--text-black); text-shadow: none; margin-bottom: 2rem;">
        Search results for: "<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
    </h1>

    <div class="jiji-grid">
        <?php
            if(isset($_GET['search'])){
                $search_box = mysqli_real_escape_with_like_wildcards($_GET['search'], $conn);
                $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%{$search_box}%' OR details LIKE '%{$search_box}%'") or die('query failed');
                if(mysqli_num_rows($select_products) > 0){
                    while($fetch_products = mysqli_fetch_assoc($select_products)){
        ?>
        <div class="jiji-card">
            <div class="jiji-card-img">
                <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>">
                    <img src="assets/uploads/<?php echo $fetch_products['image']; ?>" alt="">
                </a>
                <div class="jiji-price"><?php echo number_format($fetch_products['price']); ?> ETB</div>
            </div>
            <div class="jiji-card-content">
                <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="jiji-card-title">
                    <?php echo $fetch_products['name']; ?>
                </a>
                <div class="jiji-card-footer">
                    <span><i class="fas fa-map-marker-alt"></i> Addis Ababa</span>
                    <span>Verified</span>
                </div>
                <form action="" method="POST" style="margin-top: 1rem;">
                    <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                    <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                    <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                    <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                    <input type="submit" value="Add to Cart" name="add_to_cart" class="btn" style="width: 100%; margin: 0; background: var(--jiji-green); border-radius: 0.4rem; padding: 0.8rem; font-size: 1.4rem;">
                </form>
            </div>
        </div>
        <?php
                }
            }else{
                echo '<p class="empty" style="grid-column: 1/-1;">No products match your search!</p>';
            }
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>


</body>
</html>