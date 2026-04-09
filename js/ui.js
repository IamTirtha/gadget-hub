const cartItems = [];
const cartPanel = document.getElementById("cart-panel");
const cartContainer = document.getElementById("cart-container");
const cartBtn = document.getElementById("cart-btn");
const cartCloseBtn = document.getElementById("cart-close-btn");

const getProductImage = (product) => product.thumbnail || product.images?.[0] || product.product_image || "";

const renderCartItems = () => {
    cartContainer.innerHTML = "";

    if (cartItems.length === 0) {
        cartContainer.innerHTML = `<p class="text-sm text-gray-400 text-center py-6">Your cart is empty.</p>`;
        return;
    }

    cartItems.forEach((product) => {
        const card = document.createElement("div");
        card.className = "flex items-center gap-3 bg-gray-800 p-3 rounded-xl";
        card.innerHTML = `
            <img src="${product.product_image || product.thumbnail}" alt="${product.product_title || product.title}" 
                class="w-14 h-14 object-cover rounded-lg bg-white p-1">
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-bold line-clamp-2">${product.product_title || product.title}</h3>
                <p class="text-sm text-green-400 font-semibold">$${product.product_price || product.price}</p>
            </div>
            <button class="remove-btn text-red-400 hover:text-red-600 text-xs" data-id="${product.product_id || product.id}">
                ✕
            </button>
        `;
        card.querySelector(".remove-btn").addEventListener("click", (e) => {
            removeFromCart(e.target.dataset.id);
        });
        cartContainer.appendChild(card);
    });
};

const displayAllProducts = (products) => {
    const container = document.getElementById("product-container");
    container.innerHTML = "";

    products.forEach((product) => {
        const card = document.createElement("div");
        card.className = "bg-[#111] text-white rounded-2xl shadow-lg overflow-hidden hover:scale-105 transition duration-300 cursor-pointer";
        card.innerHTML = `
            <div class="bg-white p-4 flex justify-center items-center h-48">
                <img src="${getProductImage(product)}" alt="product" class="h-full object-contain">
            </div>
            <div class="p-4 space-y-2">
                <h2 class="text-sm md:text-base font-semibold line-clamp-2">${product.title}</h2>
                <p class="text-xs text-gray-400 capitalize">${product.category}</p>
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-green-400">$${product.price}</span>
                    <span class="text-yellow-400 text-sm">⭐ ${product.rating}</span>
                </div>
                <div class="flex gap-2 mt-3">
                    <button class="add-to-cart flex-1 bg-blue-600 hover:bg-blue-700 py-2 rounded-xl text-sm font-medium transition">
                        Add to Cart
                    </button>
                    <button class="add-to-wishlist bg-gray-700 hover:bg-pink-600 py-2 px-3 rounded-xl text-sm transition">
                        ♡
                    </button>
                </div>
            </div>
        `;

        card.querySelector(".add-to-cart").addEventListener("click", (e) => {
            e.stopPropagation();
            addToCart(product);
        });

        card.querySelector(".add-to-wishlist").addEventListener("click", (e) => {
            e.stopPropagation();
            addToWishlist(product);
        });

        card.addEventListener("click", () => displayModal(product));
        container.appendChild(card);
    });
};

const displayModal = (product) => {
    const modal = document.getElementById("modal-container");
    const content = document.getElementById("modal-box");
    modal.classList.remove("hidden");
    content.innerHTML = `
        <div class="bg-white p-4 flex justify-center items-center rounded-xl">
            <img src="${getProductImage(product)}" alt="${product.title}" class="h-60 object-contain">
        </div>
        <div class="space-y-3">
            <h2 class="text-xl font-semibold">${product.title}</h2>
            <p class="text-gray-400 text-sm">${product.description}</p>
            <p class="text-xs text-gray-500 capitalize">Category: ${product.category}</p>
            <div class="flex justify-between items-center">
                <span class="text-2xl font-bold text-green-400">$${product.price}</span>
                <span class="text-yellow-400">⭐ ${product.rating}</span>
            </div>
            <div class="flex gap-2">
                <button id="modal-add-to-cart" class="flex-1 bg-blue-600 hover:bg-blue-700 py-2 rounded-xl">Add to Cart</button>
                <button id="modal-add-to-wishlist" class="bg-gray-700 hover:bg-pink-600 py-2 px-4 rounded-xl">♡ Wishlist</button>
            </div>
        </div>
    `;
    document.getElementById("modal-add-to-cart").addEventListener("click", () => addToCart(product));
    document.getElementById("modal-add-to-wishlist").addEventListener("click", () => addToWishlist(product));
};

document.getElementById("close-modal").addEventListener("click", () => {
    document.getElementById("modal-container").classList.add("hidden");
});

cartBtn.addEventListener("click", () => cartPanel.classList.toggle("hidden"));
cartCloseBtn.addEventListener("click", () => cartPanel.classList.add("hidden"));

renderCartItems();