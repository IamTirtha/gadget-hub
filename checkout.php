<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: /GadgetHub/loginform.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - GadgetHub</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-gray-950 text-white">
    <div class="mx-auto max-w-6xl px-4 py-8">
        <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-400">Welcome, <?php echo htmlspecialchars($_SESSION["user_name"] ?? "User"); ?></p>
                <h1 class="text-3xl font-bold">Checkout</h1>
            </div>
            <a href="/GadgetHub/dashboard/dashboard.php" class="inline-flex items-center justify-center rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium hover:bg-gray-700">
                Back to Dashboard
            </a>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.4fr_0.9fr]">
            <section class="rounded-2xl border border-gray-800 bg-gray-900 p-6">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-semibold">Cart Items</h2>
                    <span id="cart-count" class="rounded-full bg-blue-600 px-3 py-1 text-xs font-semibold">0 items</span>
                </div>

                <div id="checkout-message" class="mb-4 hidden rounded-lg px-4 py-3 text-sm"></div>

                <div id="cart-items" class="space-y-4">
                    <div class="rounded-xl border border-dashed border-gray-700 p-6 text-center text-gray-400">
                        Loading cart...
                    </div>
                </div>
            </section>

            <aside class="rounded-2xl border border-gray-800 bg-gray-900 p-6">
                <h2 class="mb-6 text-xl font-semibold">Order Summary</h2>

                <div class="space-y-4">
                    <div>
                        <label for="pickup" class="mb-2 block text-sm font-medium text-gray-300">Pickup Location</label>
                        <select id="pickup" class="w-full rounded-lg border border-gray-700 bg-gray-950 px-4 py-3 outline-none focus:border-blue-500">
                            <option value="Bangalore">Bangalore</option>
                            <option value="Chennai">Chennai</option>
                            <option value="Mysore">Mysore</option>
                        </select>
                    </div>

                    <div>
                        <label for="destination" class="mb-2 block text-sm font-medium text-gray-300">Destination</label>
                        <select id="destination" class="w-full rounded-lg border border-gray-700 bg-gray-950 px-4 py-3 outline-none focus:border-blue-500">
                            <option value="Delhi">Delhi</option>
                            <option value="Mumbai">Mumbai</option>
                            <option value="Kolkata">Kolkata</option>
                        </select>
                    </div>
                </div>

                <div class="my-6 border-t border-gray-800 pt-6">
                    <div class="mb-2 flex items-center justify-between text-sm text-gray-400">
                        <span>Items Total</span>
                        <span id="summary-subtotal">Rs. 0.00</span>
                    </div>
                    <div class="flex items-center justify-between text-xl font-bold">
                        <span>Total</span>
                        <span id="total">Rs. 0.00</span>
                    </div>
                </div>

                <button id="place-order-btn" class="w-full rounded-lg bg-blue-600 px-4 py-3 font-semibold text-white transition hover:bg-blue-700">
                    Place Order
                </button>
            </aside>
        </div>
    </div>

    <script src="/GadgetHub/js/api.js?v=20260427-2"></script>
</body>
</html>
