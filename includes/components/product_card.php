<?php
// Reusable Jiji-style Product Card Component (AJAX Enabled)
?>

<div class="jiji-card">
    <div class="jiji-card-img">
        <a href="view_page.php?pid=<?php echo $product['id']; ?>">
            <img src="assets/uploads/<?php echo $product['image']; ?>" alt="">
        </a>
        <div class="jiji-price"><?php echo number_format($product['price']); ?> ETB</div>
        <?php if(isset($product['is_premium']) && $product['is_premium']): ?>
            <div style="position: absolute; top: 1rem; right: 1rem; background: var(--jiji-orange); color: #fff; padding: 0.2rem 0.8rem; border-radius: 0.3rem; font-size: 1rem; font-weight: 800;">
                <i class="fas fa-crown"></i> PREMIUM
            </div>
        <?php endif; ?>
    </div>
    <div class="jiji-card-content">
        <a href="view_page.php?pid=<?php echo $product['id']; ?>" class="jiji-card-title">
            <?php echo $product['name']; ?>
        </a>
        <div class="jiji-card-footer">
            <span><i class="fas fa-map-marker-alt"></i> Addis Ababa</span>
            <span><?php echo ($product['status'] == 'available') ? 'Verified' : 'Sold'; ?></span>
        </div>
        
        <!-- Modern AJAX Add to Cart -->
        <button type="button" 
                onclick="addToCart('<?php echo $product['id']; ?>', '<?php echo addslashes($product['name']); ?>', '<?php echo $product['price']; ?>', '<?php echo $product['image']; ?>')"
                class="btn" 
                style="width: 100%; margin: 1rem 0 0 0; background: var(--jiji-green); border-radius: 0.4rem; padding: 0.8rem; font-size: 1.4rem; color: #fff; font-weight: 600; cursor: pointer;">
            Add to Cart
        </button>
    </div>
</div>
