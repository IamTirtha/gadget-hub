const displayAllProducts = (products) => {
    const emptyContainer = document.getElementById("product-container");
    emptyContainer.innerHTML = "";
    products.forEach((product) => {
    const card = document.createElement("div");
    card.innerHTML = `
                <div class="bg-[#111] text-white rounded-2xl shadow-lg overflow-hidden hover:scale-105 transition duration-300">

                    <!-- Product Image -->
                    <div class="bg-white p-4 flex justify-center items-center h-48">
                        <img src="${product.images}" 
                            alt="product" 
                            class="h-full object-contain">
                    </div>

                    <!-- Product Details -->
                    <div class="p-4 space-y-2">
                        
                        <!-- Title -->
                        <h2 class="text-sm md:text-base font-semibold line-clamp-2">
                            ${product.title}
                        </h2>

                        <!-- Category -->
                        <p class="text-xs text-gray-400 capitalize">
                            ${product.category}
                        </p>

                        <!-- Price + Rating -->
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-green-400">
                                ${product.price}
                            </span>
                            <span class="text-yellow-400 text-sm">
                                ${product.rating}
                            </span>
                        </div>

                        <!-- Button -->
                        <button class="w-full mt-3 bg-blue-600 hover:bg-blue-700 py-2 rounded-xl text-sm font-medium transition">
                            Add to Cart
                        </button>

                    </div>
                </div>
        `;

        // To show card info by clicking on It.
        card.addEventListener("click", () => {
        displayModal(product);
        });

        emptyContainer.appendChild(card)
    });
};

const displayModal=(modals)=>{
    const modal = document.getElementById("modal-container");
    const content = document.getElementById("modal-box");

    modal.classList.remove("hidden");
    content.innerHTML=`
        <!-- Image -->
        <div class="bg-white p-4 flex justify-center items-center rounded-xl">
            <img src="${modals.thumbnail}" 
                class="h-60 object-contain">
        </div>

        <!-- Details -->
        <div class="space-y-3">
            <h2 class="text-xl font-semibold">${modals.title}</h2>

            <p class="text-gray-400 text-sm">
                ${modals.description}
            </p>

            <p class="text-xs text-gray-500 capitalize">
                Category: ${modals.category}
            </p>

            <div class="flex justify-between items-center">
                <span class="text-2xl font-bold text-green-400">
                    $${modals.price}
                </span>
                <span class="text-yellow-400">
                    ⭐ ${modals.rating}
                </span>
            </div>

            <button class="w-full bg-blue-600 hover:bg-blue-700 py-2 rounded-xl mt-3">
                Add to Cart
            </button>
        </div>
    `
}

document.getElementById("close-modal").addEventListener("click", () => {
    document.getElementById("modal-container").classList.add("hidden");
});

