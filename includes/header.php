<?php
include_once __DIR__ . '/config.php';
$user_id = $_SESSION['user_id'] ?? null;
$user_type = $_SESSION['user_type'] ?? 'user';

// Dynamic base path
$base_url = '/phoneShop/';
?>

<link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css?v=1.6">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<header class="header">
    <div class="container">
        <a href="<?php echo $base_url; ?>home.php" class="logo">
            <i class="fas fa-mobile-alt"></i> PhoneSell
        </a>

        <div class="header-right">
            <?php if ($user_id): ?>
                <div class="dropdown-container" style="position: relative;">
                    <a href="javascript:void(0)" id="user-btn" style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #333; font-size: 1.5rem;">
                        <i class="far fa-user"></i> Account <i class="fas fa-chevron-down" style="font-size: 1rem;"></i>
                    </a>
                    
                    <div class="account-dropdown" id="account-dropdown" style="display: none; position: absolute; top: 100%; right: 0; background: #fff; width: 260px; border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); z-index: 99999; border: 1px solid #eee; padding: 10px 0;">
                        <div class="dropdown-header" style="padding: 15px; background: #f8f9fa; border-radius: 8px 8px 0 0;">
                            <span style="display: block; font-weight: 800; color: #3db83a; font-size: 1.5rem;"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
                            <small style="color: #777;"><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></small>
                        </div>
                        <hr style="border: none; border-top: 1px solid #eee; margin: 0;">
                        
                        <?php if($user_type === 'admin'): ?>
                            <a href="<?php echo $base_url; ?>admin/dashboard.php" class="dropdown-item" style="display: flex; align-items: center; gap: 10px; padding: 12px 20px; color: #3db83a; font-weight: 800; font-size: 1.4rem;">
                                <i class="fas fa-user-shield"></i> Admin Panel
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?php echo $base_url; ?>seller/dashboard.php" class="dropdown-item" style="display: flex; align-items: center; gap: 10px; padding: 12px 20px; color: #333; font-size: 1.4rem;"><i class="fas fa-store"></i> My Shop</a>
                        <a href="<?php echo $base_url; ?>orders.php" class="dropdown-item" style="display: flex; align-items: center; gap: 10px; padding: 12px 20px; color: #333; font-size: 1.4rem;"><i class="fas fa-shopping-bag"></i> My Orders</a>
                        <a href="<?php echo $base_url; ?>my_profile.php" class="dropdown-item" style="display: flex; align-items: center; gap: 10px; padding: 12px 20px; color: #333; font-size: 1.4rem;"><i class="fas fa-cog"></i> Settings</a>
                        <hr style="border: none; border-top: 1px solid #eee; margin: 0;">
                        <a href="<?php echo $base_url; ?>logout.php" class="dropdown-item" style="display: flex; align-items: center; gap: 10px; padding: 12px 20px; color: #d00000; font-size: 1.4rem;"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>

                <script>
                (function() {
                    var btn = document.getElementById('user-btn');
                    var menu = document.getElementById('account-dropdown');
                    if (btn && menu) {
                        btn.onclick = function(e) {
                            e.preventDefault(); e.stopPropagation();
                            menu.style.display = (menu.style.display === 'none' || menu.style.display === '') ? 'block' : 'none';
                        };
                        document.onclick = function(e) {
                            if (!menu.contains(e.target) && e.target !== btn) menu.style.display = 'none';
                        };
                    }
                })();
                </script>
            <?php else: ?>
                <a href="<?php echo $base_url; ?>login.php" class="login-link"><i class="far fa-user"></i> Sign In</a>
            <?php endif; ?>

            <a href="<?php echo $base_url; ?>cart.php" class="cart-link" style="display: flex; align-items: center; gap: 8px; font-size: 1.5rem; color: #333; font-weight: 600;">
                <i class="fas fa-shopping-cart"></i> <span>Cart</span>
                <span class="cart-count" style="background: #3db83a; color: #fff; padding: 2px 8px; border-radius: 20px; font-size: 1.2rem;">
                    <?php 
                        if ($user_id) {
                            $stmt_cart = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM `cart` WHERE user_id = ?");
                            mysqli_stmt_bind_param($stmt_cart, "i", $user_id);
                            mysqli_stmt_execute($stmt_cart);
                            $cart_result = mysqli_stmt_get_result($stmt_cart);
                            echo mysqli_fetch_assoc($cart_result)['count'];
                        } else {
                            echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                        }
                    ?>
                </span>
            </a>
            
            <!-- Global SELL Button triggers Modal -->
            <button onclick="<?php echo $user_id ? 'openPostAdModal()' : "window.location.href='{$base_url}login.php'"; ?>" class="sell-btn" style="background: #ff8500; color: #fff; padding: 10px 25px; border-radius: 5px; font-weight: 800; text-transform: uppercase; border: none; cursor: pointer;">SELL</button>
        </div>
    </div>
</header>

<!-- Global Post Ad Modal included here -->
<?php include_once __DIR__ . '/components/post_ad_modal.php'; ?>