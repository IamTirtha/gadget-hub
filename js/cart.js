// Load cart from DB on page load
const loadCart = async () => {
    try {
        console.log("Loading dashboard cart from ../api/cart_get.php");
        const res = await fetch("../api/cart_get.php", {
            credentials: "same-origin"
        });
        const data = await res.json();
        console.log("Dashboard cart response:", data);

        const items = Array.isArray(data) ? data : (data.items || []);

        cartItems.length = 0;
        items.forEach(item => cartItems.push(item));
        renderCartItems();
    } catch (error) {
        console.error("Dashboard cart load error:", error);
        cartItems.length = 0;
        renderCartItems();
    }
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

    console.log("Adding product to cart:", payload);

    const res = await fetch("../api/cart_add.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        credentials: "same-origin",
        body: JSON.stringify(payload)
    });

    const data = await res.json();
    console.log("Add to cart response:", data);

    if (data.success) {
        cartItems.unshift({
            ...payload,
            quantity: 1
        });
        renderCartItems();
        cartPanel.classList.remove("hidden");
    } else {
        alert(data.message || "Could not add to cart.");
    }
};

// Remove from cart
const removeFromCart = async (product_id) => {
    console.log("Removing product from cart:", product_id);

    const res = await fetch("../api/cart_remove.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        credentials: "same-origin",
        body: JSON.stringify({ product_id })
    });

    const data = await res.json();
    console.log("Remove from cart response:", data);
    if (data.success) {
        const index = cartItems.findIndex(i => i.product_id == product_id);
        if (index !== -1) cartItems.splice(index, 1);
        renderCartItems();
    }
};

loadCart();
