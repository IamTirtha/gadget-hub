const cartItems = [];
const cartPanel = document.getElementById("cart-panel");
const cartContainer = document.getElementById("cart-container");
const cartBtn = document.getElementById("cart-btn");
const cartCloseBtn = document.getElementById("cart-close-btn");

const getProductImage = (product) => product.thumbnail || product.images?.[0] || "";

const renderCartItems = () => {
    cartContainer.innerHTML = "";

    if (cartItems.length === 0) {
        cartContainer.innerHTML = `
            <p class="text-sm text-gray-400 text-center py-6">
                Your cart is empty.
            </p>
        `;
        return;
    }

    cartItems.forEach((product) => {
        const card = document.createElement("div");
        card.className = "flex items-center gap-4 bg-gray-800 p-3 rounded-xl";
        card.innerHTML = `
            <img src="${getProductImage(product)}" alt="${product.title}" class="w-14 h-14 object-cover rounded-lg bg-white p-1">
            <div class="min-w-0">
                <h3 class="text-sm font-bold line-clamp-2">${product.title}</h3>
                <p class="text-xs text-gray-400 capitalize">${product.category}</p>
                <p class="text-sm text-green-400 font-semibold">$${product.price}</p>
            </div>
        `;
        cartContainer.appendChild(card);
    });
};

const addToCart = (product) => {
    cartItems.push(product);
    renderCartItems();
    cartPanel.classList.remove("hidden");
};

const displayAllProducts = (products) => {
    const emptyContainer = document.getElementById("product-container");
    emptyContainer.innerHTML = "";

    products.forEach((product) => {
        const card = document.createElement("div");
        card.className = "bg-[#111] text-white rounded-2xl shadow-lg overflow-hidden hover:scale-105 transition duration-300 cursor-pointer";
        card.innerHTML = `
            <div class="bg-white p-4 flex justify-center items-center h-48">
                <img src="${getProductImage(product)}" alt="product" class="h-full object-contain">
            </div>

            <div class="p-4 space-y-2">
                <h2 class="text-sm md:text-base font-semibold line-clamp-2">
                    ${product.title}
                </h2>

                <p class="text-xs text-gray-400 capitalize">
                    ${product.category}
                </p>

                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-green-400">
                        $${product.price}
                    </span>
                    <span class="text-yellow-400 text-sm">
                        ${product.rating}
                    </span>
                </div>

                <button class="add-to-cart w-full mt-3 bg-blue-600 hover:bg-blue-700 py-2 rounded-xl text-sm font-medium transition">
                    Add to Cart
                </button>
            </div>
        `;

        const addToCartBtn = card.querySelector(".add-to-cart");
        addToCartBtn.addEventListener("click", (event) => {
            event.stopPropagation();
            addToCart(product);
        });

        card.addEventListener("click", () => {
            displayModal(product);
        });

        emptyContainer.appendChild(card);
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

            <p class="text-gray-400 text-sm">
                ${product.description}
            </p>

            <p class="text-xs text-gray-500 capitalize">
                Category: ${product.category}
            </p>

            <div class="flex justify-between items-center">
                <span class="text-2xl font-bold text-green-400">
                    $${product.price}
                </span>
                <span class="text-yellow-400">
                    ${product.rating}
                </span>
            </div>

            <button id="modal-add-to-cart" class="w-full bg-blue-600 hover:bg-blue-700 py-2 rounded-xl mt-3">
                Add to Cart
            </button>
        </div>
    `;

    document.getElementById("modal-add-to-cart").addEventListener("click", () => {
        addToCart(product);
    });
};

document.getElementById("close-modal").addEventListener("click", () => {
    document.getElementById("modal-container").classList.add("hidden");
});

cartBtn.addEventListener("click", () => {
    cartPanel.classList.toggle("hidden");
});

cartCloseBtn.addEventListener("click", () => {
    cartPanel.classList.add("hidden");
});

renderCartItems();
