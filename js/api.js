const PRODUCTS_API_URL = "https://dummyjson.com/products";

const loadProducts = async () => {
    const container = document.getElementById("product-container");

    if (!container) {
        return;
    }

    try {
        const res = await fetch(PRODUCTS_API_URL);
        const data = await res.json();

        allProducts = Array.isArray(data.products) ? data.products : [];
        displayAllProducts(allProducts);

    } catch (err) {
        container.innerHTML = `<p class="col-span-full text-center text-red-400">Failed to load products</p>`;
        console.error(err);
    }
};

// Arrow function inside event listener
document.addEventListener("DOMContentLoaded", () => loadProducts());
