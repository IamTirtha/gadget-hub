const addToWishlist = async (product) => {
    const payload = {
        product_id: product.id,
        product_title: product.title,
        product_price: product.price,
        product_image: product.thumbnail || product.images?.[0] || "",
        product_category: product.category
    };

    const res = await fetch("../api/wishlist_add.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
    });

    const data = await res.json();
    alert(data.success ? "Added to wishlist! ♡" : (data.message || "Could not add."));
};