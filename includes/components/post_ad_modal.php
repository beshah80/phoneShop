<?php
// Global Jiji-Style Post Ad Modal
$base_url = '/phoneShop/';
?>

<div id="postAdModal" class="post-ad-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 100000; overflow-y: auto;">
    <div class="modal-card" style="background: #fff; max-width: 600px; margin: 5rem auto; border-radius: 12px; overflow: hidden; position: relative; animation: slideDown 0.3s ease;">
        <div class="modal-header" style="background: #3db83a; color: #fff; padding: 2rem; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 2rem; font-weight: 800;">Post New Phone Ad</h2>
            <button onclick="closePostAdModal()" style="background: none; border: none; color: #fff; font-size: 2.5rem; cursor: pointer;">&times;</button>
        </div>
        
        <form id="globalPostAdForm" style="padding: 3rem;" enctype="multipart/form-data">
            <div class="form-group" style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.8rem; font-weight: 700; font-size: 1.4rem;">Phone Model Name *</label>
                <input type="text" name="name" placeholder="e.g. iPhone 14 Pro Max" required style="width: 100%; padding: 1.2rem; border: 1px solid #ddd; border-radius: 6px; font-size: 1.4rem;">
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.8rem; font-weight: 700; font-size: 1.4rem;">Category *</label>
                <select name="category" required style="width: 100%; padding: 1.2rem; border: 1px solid #ddd; border-radius: 6px; font-size: 1.4rem;">
                    <option value="">Select Brand</option>
                    <option value="apple">Apple iPhone</option>
                    <option value="samsung">Samsung Galaxy</option>
                    <option value="google">Google Pixel</option>
                    <option value="accessories">Accessories</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.8rem; font-weight: 700; font-size: 1.4rem;">Price (ETB) *</label>
                <input type="number" name="price" placeholder="Price" required style="width: 100%; padding: 1.2rem; border: 1px solid #ddd; border-radius: 6px; font-size: 1.4rem;">
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.8rem; font-weight: 700; font-size: 1.4rem;">Description *</label>
                <textarea name="details" rows="4" placeholder="Condition, battery, storage..." required style="width: 100%; padding: 1.2rem; border: 1px solid #ddd; border-radius: 6px; font-size: 1.4rem; resize: none;"></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 3rem;">
                <label style="display: block; margin-bottom: 0.8rem; font-weight: 700; font-size: 1.4rem;">Photo *</label>
                <input type="file" name="image" accept="image/*" required style="font-size: 1.3rem;">
            </div>

            <button type="submit" id="postSubmitBtn" style="width: 100%; background: #3db83a; color: #fff; padding: 1.5rem; border: none; border-radius: 6px; font-weight: 800; font-size: 1.6rem; cursor: pointer;">
                POST AD NOW
            </button>
        </form>
    </div>
</div>

<script>
function openPostAdModal() {
    document.getElementById('postAdModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closePostAdModal() {
    document.getElementById('postAdModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

document.getElementById('globalPostAdForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('postSubmitBtn');
    btn.innerText = 'POSTING...';
    btn.disabled = true;

    const formData = new FormData(this);
    try {
        const response = await fetch('<?php echo $base_url; ?>api/post_ad.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
            btn.innerText = 'POST AD NOW';
            btn.disabled = false;
        }
    } catch (error) {
        alert("Error posting ad. Try again.");
        btn.innerText = 'POST AD NOW';
        btn.disabled = false;
    }
});

// Animations
const style = document.createElement('style');
style.innerHTML = `
    @keyframes slideDown {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
`;
document.head.appendChild(style);
</script>
