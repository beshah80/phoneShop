<?php
include_once __DIR__ . '/config.php';
$user_id = $_SESSION['user_id'] ?? null;

if (isset($messages)) {
    $message_list = is_array($messages) ? $messages : [$messages];
    foreach ($message_list as $msg) {
        if (!empty(trim($msg))) {
            echo '
            <div class="message">
                <span>' . htmlspecialchars($msg) . '</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>
            ';
        }
    }
}
?>

<header class="header">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <div class="main-header">
        <div class="container">
            <div class="flex">
                <a href="home.php" class="logo">
                    <span>PhoneSell</span>
                </a>

                <nav class="navbar">
                    <ul>
                        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                            <li><a href="admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                            <li><a href="admin/products.php"><i class="fas fa-box"></i> Products</a></li>
                            <li><a href="admin/orders.php"><i class="fas fa-shopping-bag"></i> Orders</a></li>
                            <li><a href="admin/users.php"><i class="fas fa-users"></i> Users</a></li>
                            <li><a href="admin/contacts.php"><i class="fas fa-envelope"></i> Messages</a></li>
                        <?php else: ?>
                            <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
                            <li><a href="shop.php"><i class="fas fa-store"></i> Shop</a></li>
                            <li><a href="#"><i class="fas fa-info-circle"></i> Info</a>
                                <ul>
                                    <li><a href="about.php">About Us</a></li>
                                    <li><a href="contact.php">Contact</a></li>
                                    <li><a href="faq.php">FAQs</a></li>
                                    <li><a href="warranty.php">Warranty</a></li>
                                </ul>
                            </li>
                            <li><a href="admin/orders.php"><i class="fas fa-shopping-bag"></i> Orders</a></li>
                            <?php if (!$user_id): ?>
                                <li>
                                    <a href="#"><i class="fas fa-user"></i> Account</a>
                                    <ul>
                                        <li><a href="login.php">Login</a></li>
                                        <li><a href="register.php">Register</a></li>
                                        <li><a href="seller/apply.php">Become Seller</a></li>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <?php if (!isset($_SESSION['is_approved_seller']) || !$_SESSION['is_approved_seller']): ?>
                                    <li><a href="seller/apply.php">Become Seller</a></li>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                </nav>

                <div class="icons">
                    <button id="menu-btn" class="hamburger" aria-label="Open menu" aria-expanded="false" tabindex="0">
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </button>
                    <a href="search_page.php" class="fas fa-search"></a>
                    <?php if ($user_id): ?>
                        <div id="user-btn" class="fas fa-user"></div>
                    <?php endif; ?>
                    <?php
                    $cart_count = 0;
                    
                    if ($user_id) {
                        // Count cart items for logged-in users
                        $stmt_cart = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM `cart` WHERE user_id = ?");
                        mysqli_stmt_bind_param($stmt_cart, "i", $user_id);
                        mysqli_stmt_execute($stmt_cart);
                        $cart_result = mysqli_stmt_get_result($stmt_cart);
                        $cart_count = mysqli_fetch_assoc($cart_result)['count'];
                        mysqli_stmt_close($stmt_cart);
                    } else {
                        // Count cart items for non-logged-in users from session
                        $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                    }
                    ?>
                    <a href="cart.php" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="count"><?php echo $cart_count; ?></span>
                    </a>
                    <?php if ($user_id): ?>
                        <a href="seller/dashboard.php" class="seller-icon <?php echo (isset($_SESSION['is_approved_seller']) && $_SESSION['is_approved_seller']) ? '' : 'disabled'; ?>" title="Seller Dashboard">
                            <i class="fas fa-store"></i>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="account-box">
                    <?php if ($user_id): ?>
                        <div class="profile-card">
                            <div class="profile-avatar"><i class="fas fa-user-circle"></i></div>
                            <div class="profile-info">
                                <div class="profile-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></div>
                                <div class="profile-email"><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></div>
                            </div>
                        </div>
                        <a href="my_profile.php" class="profile-link">My Profile</a>
                        <a href="logout.php" class="profile-link logout-link">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn">Login</a>
                        <a href="register.php" class="btn">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
.header {
    background: #1a1a1a;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.main-header {
    padding: 1.5rem 0;
}

.flex {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
}

.logo {
    font-size: 2.5rem;
    font-weight: bold;
    color: #ffffff;
    text-decoration: none;
}

.navbar {
    flex: 1;
}

.navbar ul {
    display: flex;
    align-items: center;
    gap: 2rem;
    list-style: none;
}

.navbar ul li {
    position: relative;
}

.navbar ul li a {
    font-size: 1.6rem;
    color: #ffffff;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.navbar ul li a i {
    font-size: 1.8rem;
}

.navbar ul li a:hover {
    color: #ffd700;
}

.navbar ul li ul {
    position: absolute;
    top: 100%;
    left: 0;
    background: #2a2a2a;
    padding: 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    display: none;
    flex-direction: column;
    gap: 1rem;
    min-width: 200px;
}

.navbar ul li:hover ul {
    display: flex;
}

.icons {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.icons a,
.icons div {
    font-size: 2rem;
    color: #ffffff;
    cursor: pointer;
    position: relative;
}

.icons a:hover,
.icons div:hover {
    color: #ffd700;
}

.count {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #ffd700;
    color: #1a1a1a;
    font-size: 1.2rem;
    padding: 0.2rem 0.5rem;
    border-radius: 50%;
}

.account-box {
    position: fixed;
    top: 0;
    right: -100%;
    width: 300px;
    height: 100vh;
    background: #2a2a2a;
    padding: 2rem;
    transition: 0.3s ease;
    z-index: 1000;
}

.account-box.active {
    right: 0;
}

.account-box .btn,
.account-box .delete-btn {
    width: 100%;
    margin-bottom: 1rem;
}

#menu-btn {
    display: none;
}

@media (max-width: 991px) {
    #menu-btn {
        display: block;
    }

    .navbar {
        position: fixed;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100vh;
        background: #2a2a2a;
        padding: 2rem;
        transition: 0.3s ease;
    }

    .navbar.active {
        left: 0;
    }

    .navbar ul {
        flex-direction: column;
        gap: 2rem;
    }

    .navbar ul li ul {
        position: static;
        display: none;
        padding-left: 2rem;
        background: #333333;
    }

    .navbar ul li.active ul {
        display: flex;
    }
}

@media (max-width: 768px) {
    .flex {
        gap: 1rem;
    }

    .logo {
        font-size: 2rem;
    }

    .icons {
        gap: 1rem;
    }

    .icons a,
    .icons div {
        font-size: 1.8rem;
    }
}
</style>

<script>
// Simple toggle function
function toggleElement(element) {
    element.classList.toggle('active');
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Hamburger menu
    const menuBtn = document.getElementById('menu-btn');
    const navbar = document.querySelector('.navbar');
    const accountBox = document.querySelector('.account-box');
    
    function openMenu() {
        navbar.classList.add('active');
        menuBtn.classList.add('active');
        menuBtn.setAttribute('aria-expanded', 'true');
    }
    function closeMenu() {
        navbar.classList.remove('active');
        menuBtn.classList.remove('active');
        menuBtn.setAttribute('aria-expanded', 'false');
    }
    function toggleMenu() {
        if (navbar.classList.contains('active')) {
            closeMenu();
        } else {
            openMenu();
            accountBox.classList.remove('active');
        }
    }
    if (menuBtn) {
        menuBtn.onclick = toggleMenu;
        menuBtn.onkeydown = function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleMenu();
            }
        };
    }

    // Profile dropdown
    const userBtn = document.getElementById('user-btn');
    if (userBtn) {
        userBtn.onclick = function() {
            toggleElement(accountBox);
            closeMenu();
        };
    }

    // Close menus when clicking outside
    document.onclick = function(e) {
        if (!e.target.closest('.navbar') && !e.target.closest('#menu-btn')) {
            closeMenu();
        }
        if (!e.target.closest('.account-box') && !e.target.closest('#user-btn')) {
            accountBox.classList.remove('active');
        }
    };

    // Mobile dropdowns
    const dropdownLinks = document.querySelectorAll('.navbar ul li > a');
    dropdownLinks.forEach(link => {
        link.onclick = function(e) {
            if (window.innerWidth <= 991 && this.nextElementSibling && this.nextElementSibling.tagName === 'UL') {
                e.preventDefault();
                this.parentElement.classList.toggle('active');
            }
        };
    });
});
</script>