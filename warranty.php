<?php
@include 'header.php';
?>

<div class="heading">
    <h3>Warranty Information</h3>
    <p><a href="home.php">Home</a> / Warranty</p>
</div>

<section class="warranty">
    <div class="container">
        <div class="warranty-content">
            <div class="warranty-section">
                <h2>Our Warranty Policy</h2>
                <p>At PhoneSell, we stand behind the quality of our products. All our smartphones come with comprehensive warranty coverage to ensure your peace of mind.</p>
                
                <div class="warranty-features">
                    <div class="feature">
                        <i class="fas fa-shield-alt"></i>
                        <h3>Standard Warranty</h3>
                        <p>12 months manufacturer warranty covering hardware defects and manufacturing faults.</p>
                    </div>
                    
                    <div class="feature">
                        <i class="fas fa-clock"></i>
                        <h3>Extended Warranty</h3>
                        <p>Optional extended warranty available for up to 24 months of additional coverage.</p>
                    </div>
                    
                    <div class="feature">
                        <i class="fas fa-tools"></i>
                        <h3>Repair Service</h3>
                        <p>Authorized service centers for professional repairs and maintenance.</p>
                    </div>
                </div>
            </div>

            <div class="warranty-section">
                <h2>What's Covered</h2>
                <div class="coverage-list">
                    <div class="coverage-item">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <h3>Hardware Defects</h3>
                            <p>Manufacturing defects in components and materials.</p>
                        </div>
                    </div>
                    
                    <div class="coverage-item">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <h3>Battery Issues</h3>
                            <p>Battery defects and abnormal performance.</p>
                        </div>
                    </div>
                    
                    <div class="coverage-item">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <h3>Display Problems</h3>
                            <p>Screen defects and touch functionality issues.</p>
                        </div>
                    </div>
                    
                    <div class="coverage-item">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <h3>Software Support</h3>
                            <p>Operating system updates and technical assistance.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="warranty-section">
                <h2>What's Not Covered</h2>
                <div class="exclusions-list">
                    <div class="exclusion-item">
                        <i class="fas fa-times-circle"></i>
                        <div>
                            <h3>Physical Damage</h3>
                            <p>Accidental drops, water damage, or physical abuse.</p>
                        </div>
                    </div>
                    
                    <div class="exclusion-item">
                        <i class="fas fa-times-circle"></i>
                        <div>
                            <h3>Unauthorized Modifications</h3>
                            <p>Custom ROMs, rooting, or unauthorized repairs.</p>
                        </div>
                    </div>
                    
                    <div class="exclusion-item">
                        <i class="fas fa-times-circle"></i>
                        <div>
                            <h3>Normal Wear and Tear</h3>
                            <p>Battery degradation or cosmetic wear over time.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="warranty-section">
                <h2>How to Claim Warranty</h2>
                <div class="claim-steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <h3>Contact Support</h3>
                        <p>Reach out to our customer service team with your issue.</p>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">2</div>
                        <h3>Provide Details</h3>
                        <p>Share your purchase details and describe the problem.</p>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">3</div>
                        <h3>Service Center Visit</h3>
                        <p>Visit our authorized service center for assessment.</p>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">4</div>
                        <h3>Repair or Replacement</h3>
                        <p>We'll repair or replace your device as needed.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.warranty {
    padding: 4rem 0;
}

.warranty-content {
    max-width: 1200px;
    margin: 0 auto;
}

.warranty-section {
    margin-bottom: 4rem;
    padding: 2rem;
    background: var(--ivory-cream);
    border-radius: 1rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.warranty-section h2 {
    color: var(--dusk-navy);
    font-size: 2.4rem;
    margin-bottom: 2rem;
    text-align: center;
}

.warranty-section > p {
    font-size: 1.6rem;
    color: var(--deep-obsidian);
    text-align: center;
    margin-bottom: 3rem;
}

.warranty-features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.feature {
    text-align: center;
    padding: 2rem;
    background: var(--snow-glow);
    border-radius: 0.8rem;
    transition: transform 0.3s ease;
}

.feature:hover {
    transform: translateY(-5px);
}

.feature i {
    font-size: 3rem;
    color: var(--mystic-blue);
    margin-bottom: 1.5rem;
}

.feature h3 {
    font-size: 1.8rem;
    color: var(--dusk-navy);
    margin-bottom: 1rem;
}

.feature p {
    font-size: 1.4rem;
    color: var(--deep-obsidian);
}

.coverage-list,
.exclusions-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.coverage-item,
.exclusion-item {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
    padding: 1.5rem;
    background: var(--snow-glow);
    border-radius: 0.8rem;
}

.coverage-item i {
    font-size: 2rem;
    color: var(--lime-green);
}

.exclusion-item i {
    font-size: 2rem;
    color: var(--warning-red);
}

.coverage-item h3,
.exclusion-item h3 {
    font-size: 1.6rem;
    color: var(--dusk-navy);
    margin-bottom: 0.5rem;
}

.coverage-item p,
.exclusion-item p {
    font-size: 1.4rem;
    color: var(--deep-obsidian);
}

.claim-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
}

.step {
    text-align: center;
    padding: 2rem;
    background: var(--snow-glow);
    border-radius: 0.8rem;
    position: relative;
}

.step-number {
    width: 3rem;
    height: 3rem;
    background: var(--mystic-blue);
    color: var(--snow-glow);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    font-weight: bold;
    margin: 0 auto 1.5rem;
}

.step h3 {
    font-size: 1.8rem;
    color: var(--dusk-navy);
    margin-bottom: 1rem;
}

.step p {
    font-size: 1.4rem;
    color: var(--deep-obsidian);
}

@media (max-width: 768px) {
    .warranty-section {
        padding: 1.5rem;
    }

    .warranty-section h2 {
        font-size: 2rem;
    }

    .feature,
    .coverage-item,
    .exclusion-item,
    .step {
        padding: 1.5rem;
    }

    .feature h3,
    .coverage-item h3,
    .exclusion-item h3,
    .step h3 {
        font-size: 1.6rem;
    }

    .feature p,
    .coverage-item p,
    .exclusion-item p,
    .step p {
        font-size: 1.3rem;
    }
}
</style>

<?php
@include 'footer.php';
?> 