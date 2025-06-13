<?php
$current_year = date('Y');
?>

<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section about">
                <h3>About PhoneSell</h3>
                <p>Your trusted destination for quality smartphones in Ethiopia. We offer the latest models from top brands with competitive prices and excellent customer service.</p>
                <div class="contact">
                    <p><i class="fas fa-phone"></i> +251 911 123 456</p>
                    <p><i class="fas fa-envelope"></i> support@phonesell.com</p>
                    <p><i class="fas fa-map-marker-alt"></i> Addis Ababa, Ethiopia</p>
                </div>
                <div class="socials">
                    <a href="#" target="_blank"><i class="fab fa-facebook"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>

            <div class="footer-section links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="shop.php">Shop</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="faq.php">FAQs</a></li>
                    <li><a href="warranty.php">Warranty</a></li>
                </ul>
            </div>

            <div class="footer-section categories">
                <h3>Categories</h3>
                <ul>
                    <li><a href="shop.php?category=apple">Apple</a></li>
                    <li><a href="shop.php?category=samsung">Samsung</a></li>
                    <li><a href="shop.php?category=xiaomi">Xiaomi</a></li>
                    <li><a href="shop.php?category=tecno">Tecno</a></li>
                    <li><a href="shop.php?category=infinix">Infinix</a></li>
                    <li><a href="shop.php?category=accessories">Accessories</a></li>
                </ul>
            </div>

            <div class="footer-section newsletter">
                <h3>Newsletter</h3>
                <p>Subscribe to our newsletter for the latest updates and offers.</p>
                <form action="subscribe.php" method="POST" class="newsletter-form">
                    <input type="email" name="email" placeholder="Enter your email" required>
                    <button type="submit" class="btn">Subscribe</button>
                </form>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="payment-methods">
                <h4>Payment Methods</h4>
                <div class="payment-icons modern-payment-icons">
                    <div class="payment-item">
                        <i class="fab fa-telegram"></i>
                        <span>Telebirr</span>
                    </div>
                    <div class="payment-item">
                        <i class="fas fa-wallet"></i>
                        <span>Amole</span>
                    </div>
                    <div class="payment-item">
                        <i class="fas fa-university"></i>
                        <span>CBE Birr</span>
                    </div>
                    <div class="payment-item">
                        <i class="fas fa-university"></i>
                        <span>Dashen Bank</span>
                    </div>
                    <div class="payment-item">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Cash on Delivery</span>
                    </div>
                    <div class="payment-item">
                        <i class="far fa-credit-card"></i>
                        <span>Credit Card</span>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; <?php echo $current_year; ?> PhoneSell. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<style>
.footer {
    background: var(--dusk-navy);
    color: var(--snow-glow);
    padding: 4rem 0 2rem;
    margin-top: 4rem;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 3rem;
    margin-bottom: 3rem;
}

.footer-section h3 {
    color: var(--sunlit-gold);
    font-size: 2rem;
    margin-bottom: 1.5rem;
    text-transform: uppercase;
}

.footer-section p {
    font-size: 1.4rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.contact p {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.contact i {
    color: var(--sunlit-gold);
}

.socials {
    display: flex;
    gap: 1.5rem;
    margin-top: 2rem;
}

.socials a {
    color: var(--snow-glow);
    font-size: 2rem;
    transition: color 0.3s ease;
}

.socials a:hover {
    color: var(--sunlit-gold);
}

.footer-section ul {
    list-style: none;
}

.footer-section ul li {
    margin-bottom: 1rem;
}

.footer-section ul li a {
    color: var(--snow-glow);
    font-size: 1.4rem;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-section ul li a:hover {
    color: var(--sunlit-gold);
}

.newsletter-form {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.newsletter-form input {
    flex: 1;
    padding: 1rem;
    border: none;
    border-radius: 0.5rem;
    font-size: 1.4rem;
    background: var(--ivory-cream);
}

.newsletter-form .btn {
    padding: 1rem 2rem;
    font-size: 1.4rem;
    background: var(--mystic-blue);
    color: var(--snow-glow);
    border: none;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: background 0.3s ease;
}

.newsletter-form .btn:hover {
    background: var(--sunlit-gold);
}

.footer-bottom {
    border-top: 1px solid var(--mist-silver);
    padding-top: 2rem;
    text-align: center;
}

.payment-methods {
    margin-bottom: 2rem;
}

.payment-methods h4 {
    font-size: 1.6rem;
    color: var(--snow-glow);
    margin-bottom: 1rem;
}

.payment-icons.modern-payment-icons {
    display: flex;
    justify-content: center;
    gap: 2.5rem;
    font-size: 2.3rem;
    color: var(--snow-glow);
    flex-wrap: wrap;
    margin-top: 1rem;
}

.payment-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    min-width: 90px;
}

.payment-item i {
    font-size: 2.3rem;
    color: var(--sunlit-gold);
    margin-bottom: 0.2rem;
}

.payment-item span {
    font-size: 1.1rem;
    color: var(--snow-glow);
    margin-top: 0.1rem;
    letter-spacing: 0.01em;
}

.copyright {
    font-size: 1.4rem;
    color: var(--mist-silver);
}

@media (max-width: 768px) {
    .footer-content {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .contact p {
        justify-content: center;
    }

    .socials {
        justify-content: center;
    }

    .newsletter-form {
        flex-direction: column;
    }

    .payment-icons.modern-payment-icons {
        gap: 1.2rem;
        font-size: 1.5rem;
    }

    .payment-item {
        min-width: 70px;
    }

    .payment-item span {
        font-size: 0.95rem;
    }
}
</style>