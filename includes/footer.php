<?php
include_once 'includes/config.php';
?>

<footer class="footer">
    <div class="container jiji-footer-grid">
        <div class="footer-col">
            <h4>About PhoneSell</h4>
            <ul>
                <li><a href="about.php">About Us</a></li>
                <li><a href="#">Our Blog</a></li>
                <li><a href="#">Terms & Conditions</a></li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Support</h4>
            <ul>
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="faq.php">FAQs</a></li>
                <li><a href="warranty.php">Warranty Policy</a></li>
                <li><a href="#">Safety Tips</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Our App</h4>
            <p>Download our mobile app for the best experience!</p>
            <div class="app-links">
                <a href="#"><i class="fab fa-apple"></i> App Store</a>
                <a href="#"><i class="fab fa-google-play"></i> Google Play</a>
            </div>
        </div>
        <div class="footer-col">
            <h4>Follow Us</h4>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
            <p class="copyright">&copy; <?php echo date('Y'); ?> <span>PhoneSell Ethiopia</span>. All rights reserved.</p>
        </div>
    </div>
</footer>

<style>
.footer {
    background: #fff;
    padding: 5rem 7% 3rem 7%;
    border-top: 1px solid #eee;
    margin-top: 5rem;
}

.jiji-footer-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 3rem;
}

.footer-col h4 {
    font-size: 1.6rem;
    color: var(--text-black);
    margin-bottom: 2rem;
    text-transform: uppercase;
}

.footer-col ul li {
    list-style: none;
    margin-bottom: 1.2rem;
}

.footer-col ul li a {
    font-size: 1.4rem;
    color: var(--text-gray);
    transition: color 0.3s;
}

.footer-col ul li a:hover {
    color: var(--jiji-green);
}

.app-links {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 1.5rem;
}

.app-links a {
    background: #000;
    color: #fff;
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 1.4rem;
}

.social-links {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.social-links a {
    font-size: 2rem;
    color: var(--text-gray);
    transition: color 0.3s;
}

.social-links a:hover {
    color: var(--jiji-green);
}

.copyright {
    font-size: 1.2rem;
    color: var(--text-gray);
    margin-top: 1rem;
}

.copyright span {
    color: var(--jiji-green);
    font-weight: 700;
}
</style>

<!-- Unified Script Inclusion with Cache Buster -->
<!-- Global Components -->
<?php include_once 'includes/components/cart_modal.php'; ?>

<!-- Unified Script Inclusion with Cache Buster -->
<script src="/phoneShop/assets/js/script.js?v=1.7"></script>