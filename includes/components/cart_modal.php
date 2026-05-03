<!-- Global Cart Confirmation Modal -->
<div id="cartModal" class="cart-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 100001; align-items: center; justify-content: center;">
    <div class="cart-modal-card" style="background: #fff; width: 400px; padding: 3rem; border-radius: 12px; text-align: center; animation: modalPop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
        <div class="cart-success-icon" style="width: 70px; height: 70px; background: #f0fdf4; color: #3db83a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; margin: 0 auto 2rem;">
            <i class="fas fa-check"></i>
        </div>
        
        <h2 style="font-size: 2.2rem; font-weight: 800; color: #333; margin-bottom: 1rem;">Item Added!</h2>
        <p style="font-size: 1.5rem; color: #666; margin-bottom: 3rem;">This phone has been added to your shopping cart successfully.</p>
        
        <div style="display: flex; gap: 1.5rem; justify-content: center;">
            <button onclick="closeCartModal()" style="flex: 1; padding: 1.2rem; border-radius: 6px; border: 1px solid #ddd; background: #fff; color: #555; font-weight: 700; font-size: 1.4rem; cursor: pointer; transition: 0.2s;">CANCEL</button>
            <button onclick="window.location.href='/phoneShop/cart.php'" style="flex: 1; padding: 1.2rem; border-radius: 6px; border: none; background: #3db83a; color: #fff; font-weight: 800; font-size: 1.4rem; cursor: pointer; transition: 0.2s;">OK (GO TO CART)</button>
        </div>
    </div>
</div>

<style>
    @keyframes modalPop {
        0% { transform: scale(0.8); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    .cart-modal { display: none !important; }
    .cart-modal.active { display: flex !important; }
</style>

<script>
    function closeCartModal() {
        document.getElementById('cartModal').classList.remove('active');
    }
</script>
