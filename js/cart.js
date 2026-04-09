// Load cart from DB on page load
const loadCart = async () => {
    const res = await fetch("../api/cart_get.php");
    const items = await res.json();
    cartItems.length = 0;
    items.forEach(item => cartItems.push(item));
    renderCartItems();
};

// Add to cart - saves to DB
const addToCart = async (product) => {
    const payload = {
        product_id: product.id,
        product_title: product.title,
        product_price: product.price,
        product_image: product.thumbnail || product.images?.[0] || "",
        product_category: product.category
    };

    const res = await fetch("../api/cart_add.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
    });

    const data = await res.json();

    if (data.success) {
        cartItems.push(payload);
        renderCartItems();
        cartPanel.classList.remove("hidden");
    } else {
        alert(data.message || "Could not add to cart.");
    }
};

// Remove from cart
const removeFromCart = async (product_id) => {
    const res = await fetch("../api/cart_remove.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ product_id })
    });

    const data = await res.json();
    if (data.success) {
        const index = cartItems.findIndex(i => i.product_id == product_id);
        if (index !== -1) cartItems.splice(index, 1);
        renderCartItems();
    }
};

loadCart();