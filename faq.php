<?php
@include 'includes/header.php';
?>

<div class="heading">
    <h3>Frequently Asked Questions</h3>
    <p><a href="home.php">Home</a> / FAQ</p>
</div>

<section class="faq">
    <div class="container">
        <div class="faq-container">
            <div class="faq-item">
                <div class="faq-question">
                    <h3>What payment methods do you accept?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>We accept various payment methods including:</p>
                    <ul>
                        <li>Credit/Debit Cards (Visa, MasterCard)</li>
                        <li>PayPal</li>
                        <li>Cash on Delivery</li>
                        <li>Mobile Money (Telebirr, M-Pesa)</li>
                    </ul>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <h3>What is your return policy?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>We offer a 14-day return policy for all products. The item must be:</p>
                    <ul>
                        <li>In its original condition</li>
                        <li>With all original packaging and accessories</li>
                        <li>With proof of purchase</li>
                    </ul>
                    <p>Please contact our customer service to initiate a return.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <h3>Do you offer warranty on your products?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Yes, all our products come with:</p>
                    <ul>
                        <li>Manufacturer's warranty (typically 12 months)</li>
                        <li>Extended warranty options available</li>
                        <li>Free technical support</li>
                    </ul>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <h3>How long does delivery take?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Delivery times vary by location:</p>
                    <ul>
                        <li>Addis Ababa: 1-2 business days</li>
                        <li>Major cities: 2-4 business days</li>
                        <li>Other locations: 4-7 business days</li>
                    </ul>
                    <p>Express delivery options are available for an additional fee.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <h3>Are your products original and authentic?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Yes, we guarantee that all our products are:</p>
                    <ul>
                        <li>100% original and authentic</li>
                        <li>From authorized dealers</li>
                        <li>With valid warranty</li>
                        <li>With original packaging</li>
                    </ul>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <h3>Do you offer after-sales support?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Yes, we provide comprehensive after-sales support:</p>
                    <ul>
                        <li>Technical support via phone and email</li>
                        <li>Repair services at authorized centers</li>
                        <li>Software updates and troubleshooting</li>
                        <li>Accessory recommendations</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.faq {
    padding: 4rem 0;
}

.faq-container {
    max-width: 800px;
    margin: 0 auto;
}

.faq-item {
    background: var(--ivory-cream);
    border-radius: 0.8rem;
    margin-bottom: 2rem;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.faq-question {
    padding: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    background: var(--dusk-navy);
    color: var(--snow-glow);
}

.faq-question h3 {
    font-size: 1.8rem;
    margin: 0;
}

.faq-question i {
    font-size: 1.6rem;
    transition: transform 0.3s ease;
}

.faq-item.active .faq-question i {
    transform: rotate(180deg);
}

.faq-answer {
    padding: 0;
    max-height: 0;
    overflow: hidden;
    transition: all 0.3s ease;
}

.faq-item.active .faq-answer {
    padding: 2rem;
    max-height: 1000px;
}

.faq-answer p {
    font-size: 1.6rem;
    color: var(--deep-obsidian);
    margin-bottom: 1.5rem;
}

.faq-answer ul {
    list-style: disc;
    margin-left: 2rem;
    margin-bottom: 1.5rem;
}

.faq-answer ul li {
    font-size: 1.6rem;
    color: var(--deep-obsidian);
    margin-bottom: 0.8rem;
}

@media (max-width: 768px) {
    .faq-question h3 {
        font-size: 1.6rem;
    }
    
    .faq-answer p,
    .faq-answer ul li {
        font-size: 1.4rem;
    }
}
</style>

<script>
document.querySelectorAll('.faq-question').forEach(question => {
    question.addEventListener('click', () => {
        const faqItem = question.parentElement;
        faqItem.classList.toggle('active');
    });
});
</script>

<?php
@include 'includes/footer.php';
?> 