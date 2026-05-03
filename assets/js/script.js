// PhoneSell Modern E-commerce Script (Jiji-Style)

document.addEventListener('DOMContentLoaded', () => {
    // Handle Jiji-style Account dropdown
    const userBtn = document.querySelector('#user-btn');
    const accountDropdown = document.querySelector('#account-dropdown');

    if (userBtn && accountDropdown) {
        console.log("Dropdown initialized"); // Debug log
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            accountDropdown.classList.toggle('active');
            console.log("Dropdown toggled"); // Debug log
        });

        // Close dropdown on clicking outside
        document.addEventListener('click', (e) => {
            if (!accountDropdown.contains(e.target) && !userBtn.contains(e.target)) {
                accountDropdown.classList.remove('active');
            }
        });
    } else {
        console.error("Dropdown elements not found!");
    }

    // Global function to add to cart via API
    window.addToCart = async function(productId, productName, productPrice, productImage) {
        const formData = new FormData();
        formData.append('add_to_cart', true);
        formData.append('product_id', productId);
        formData.append('product_name', productName);
        formData.append('product_price', productPrice);
        formData.append('product_image', productImage);

        try {
            const response = await fetch('/phoneShop/api/cart.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            const cartBadge = document.querySelector('.cart-count');
            if (cartBadge) cartBadge.innerText = data.cart_count;
            alert(data.message);
        } catch (error) {
            console.error('Error adding to cart:', error);
        }
    };
});
