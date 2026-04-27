(function () {
    const APP_BASE_PATH = "/GadgetHub";
    const PRODUCTS_API_URL = "https://dummyjson.com/products";
    const CART_API_URL = APP_BASE_PATH + "/api/cart_get.php";
    const PLACE_ORDER_API_URL = APP_BASE_PATH + "/orders/place_order.php";

    const formatCurrency = (value) => `Rs. ${Number(value || 0).toFixed(2)}`;
    const calculateCartTotal = (items) => items.reduce((sum, item) => {
        const price = Number(item.product_price || item.price || 0);
        const quantity = Number(item.quantity || 1);
        return sum + (price * quantity);
    }, 0);

    const showCheckoutMessage = (message, type = "error") => {
        const messageBox = document.getElementById("checkout-message");

        if (!messageBox) {
            return;
        }

        const className = type === "success"
            ? "mb-4 rounded-lg bg-green-500/10 px-4 py-3 text-sm text-green-300"
            : "mb-4 rounded-lg bg-red-500/10 px-4 py-3 text-sm text-red-300";

        messageBox.className = className;
        messageBox.textContent = message;
        messageBox.classList.remove("hidden");
    };

    const renderCheckoutCart = (items, total) => {
        const cartItemsContainer = document.getElementById("cart-items");
        const totalElement = document.getElementById("total");
        const subtotalElement = document.getElementById("summary-subtotal");
        const countElement = document.getElementById("cart-count");
        const placeOrderButton = document.getElementById("place-order-btn");

        if (!cartItemsContainer) {
            return;
        }

        cartItemsContainer.innerHTML = "";

        if (!Array.isArray(items) || items.length === 0) {
            cartItemsContainer.innerHTML = `
                <div class="rounded-xl border border-dashed border-gray-700 p-6 text-center text-gray-400">
                    Your cart is empty.
                </div>
            `;

            if (countElement) {
                countElement.textContent = "0 items";
            }

            if (totalElement) {
                totalElement.textContent = formatCurrency(0);
            }

            if (subtotalElement) {
                subtotalElement.textContent = formatCurrency(0);
            }

            if (placeOrderButton) {
                placeOrderButton.disabled = true;
                placeOrderButton.classList.add("cursor-not-allowed", "opacity-60");
            }

            return;
        }

        const finalTotal = Number(total || calculateCartTotal(items));

        items.forEach((item) => {
            const quantity = Number(item.quantity || 1);
            const price = Number(item.product_price || item.price || 0);
            const itemTotal = quantity * price;

            cartItemsContainer.insertAdjacentHTML(
                "beforeend",
                `
                <div class="flex items-center gap-4 rounded-xl border border-gray-800 bg-gray-950 p-4">
                    <img
                        src="${item.product_image || ""}"
                        alt="${item.product_title || "Product"}"
                        class="h-20 w-20 rounded-lg bg-white object-contain p-2"
                    >
                    <div class="min-w-0 flex-1">
                        <h3 class="truncate text-base font-semibold">${item.product_title || "Product"}</h3>
                        <p class="text-sm text-gray-400">${item.product_category || "Gadget"}</p>
                        <p class="mt-1 text-sm text-gray-300">Qty: ${quantity}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-400">${formatCurrency(price)} each</p>
                        <p class="text-lg font-semibold text-green-400">${formatCurrency(itemTotal)}</p>
                    </div>
                </div>
                `
            );
        });

        if (countElement) {
            countElement.textContent = `${items.length} item${items.length === 1 ? "" : "s"}`;
        }

        if (totalElement) {
            totalElement.textContent = formatCurrency(finalTotal);
        }

        if (subtotalElement) {
            subtotalElement.textContent = formatCurrency(finalTotal);
        }

        if (placeOrderButton) {
            placeOrderButton.disabled = false;
            placeOrderButton.classList.remove("cursor-not-allowed", "opacity-60");
        }
    };

    const loadCheckoutCart = async () => {
        const cartItemsContainer = document.getElementById("cart-items");

        if (!cartItemsContainer) {
            return null;
        }

        console.log("Loading checkout cart from:", CART_API_URL);

        try {
            const response = await fetch(CART_API_URL, {
                method: "GET",
                credentials: "same-origin"
            });

            const data = await response.json();
            console.log("Cart response:", data);

            const items = Array.isArray(data) ? data : (data.items || []);
            const total = Number(data.total || calculateCartTotal(items));

            if (!response.ok || (typeof data.success !== "undefined" && !data.success)) {
                throw new Error(data.message || "Unable to load cart.");
            }

            renderCheckoutCart(items, total);
            return { items, total };
        } catch (error) {
            console.error("Checkout cart load error:", error);
            renderCheckoutCart([], 0);
            showCheckoutMessage(error.message || "Unable to load cart.");
            return null;
        }
    };

    const placeCheckoutOrder = async () => {
        const placeOrderButton = document.getElementById("place-order-btn");
        const pickupSelect = document.getElementById("pickup");
        const destinationSelect = document.getElementById("destination");
        let orderPlaced = false;

        if (!placeOrderButton || !pickupSelect || !destinationSelect) {
            return;
        }

        placeOrderButton.disabled = true;
        placeOrderButton.textContent = "Placing Order...";
        showCheckoutMessage("", "success");
        const checkoutMessage = document.getElementById("checkout-message");
        if (checkoutMessage) {
            checkoutMessage.classList.add("hidden");
        }

        const formData = new FormData();
        formData.append("pickup", pickupSelect.value);
        formData.append("destination", destinationSelect.value);

        console.log("Placing order with:", {
            pickup: pickupSelect.value,
            destination: destinationSelect.value
        });

        try {
            const response = await fetch(PLACE_ORDER_API_URL, {
                method: "POST",
                body: formData,
                credentials: "same-origin"
            });

            const data = await response.json();
            console.log("Place order response:", data);

            if (!response.ok || !data.success) {
                throw new Error(data.message || "Order placement failed.");
            }

            orderPlaced = true;
            showCheckoutMessage(data.message || "Order placed successfully.", "success");
            renderCheckoutCart([], 0);

            setTimeout(() => {
                window.location.href = data.redirect || APP_BASE_PATH + "/dashboard/dashboard.php";
            }, 1200);
        } catch (error) {
            console.error("Place order error:", error);
            showCheckoutMessage(error.message || "Unable to place order.");
        } finally {
            placeOrderButton.textContent = "Place Order";
            if (!orderPlaced) {
                placeOrderButton.disabled = false;
            }
        }
    };

    const loadProductsForDashboard = async () => {
        const productContainer = document.getElementById("product-container");

        if (!productContainer) {
            return;
        }

        console.log("Loading products from:", PRODUCTS_API_URL);

        try {
            const response = await fetch(PRODUCTS_API_URL);
            const data = await response.json();
            const productCount = Array.isArray(data.products) ? data.products.length : 0;
            console.log("Products loaded:", productCount);

            window.allProducts = Array.isArray(data.products) ? data.products : [];

            if (typeof displayAllProducts === "function") {
                displayAllProducts(window.allProducts);
            }
        } catch (error) {
            console.error("Product load error:", error);
            productContainer.innerHTML = `
                <div class="col-span-full rounded-xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-300">
                    Unable to load products right now.
                </div>
            `;
        }
    };

    document.addEventListener("DOMContentLoaded", () => {
        loadProductsForDashboard();

        if (document.getElementById("cart-items")) {
            loadCheckoutCart();
        }

        const placeOrderButton = document.getElementById("place-order-btn");
        if (placeOrderButton) {
            placeOrderButton.addEventListener("click", placeCheckoutOrder);
        }
    });
})();
