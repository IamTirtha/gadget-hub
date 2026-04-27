<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /GadgetHub/loginform.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="../styles/tailwind.css">
    <!-- Glow effect -->
    <style>
        .glow {
        text-shadow: 0 0 8px #3b82f6, 0 0 16px #3b82f6;
        }
    </style>
</head>

    <body class="bg-gray-900 text-white">

    <!-- ================= NAVBAR ================= -->
    <nav class="bg-gray-800 p-4 flex items-center justify-between flex-wrap">

        <!-- Logo -->
        <div class="text-xl font-bold leading-tight">
        Gadget <br>
        <span class="glow text-blue-400">HUB</span>
        </div>

        <!-- Search Bar -->
        <div class="w-full md:w-1/2 my-3 md:my-0">
        <input type="text" placeholder="Search gadgets..."
            class="w-full px-4 py-2 rounded-lg bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Right Section -->
        <div class="flex items-center gap-4">

        <!-- Cart -->
        <button id="cart-btn" class="text-white text-xl cursor-pointer">
            🛒
        </button>

        <div id="cart-panel" 
            class="fixed right-0 top-0 w-80 h-full bg-gray-900 text-white p-4 hidden overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl">Your Cart</h2>
                <button id="cart-close-btn" class="text-white text-xl leading-none hover:text-red-400" aria-label="Close cart">
                    x
                </button>
            </div>
            <div id="cart-container" class="space-y-4 border border-gray-600 p-2">
                <!-- Items will come here -->
            </div>
            <a href="/GadgetHub/checkout.php"
               class="mt-4 inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                Proceed to Checkout
            </a>
        </div>

        <a href="../profile/profile.php" 
                class="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg inline-block">
                👤 Profile
        </a>

        <!-- Logout -->
        <a href="../auth/logout.php" id="logout-btn" class="bg-red-500 hover:bg-red-700 px-4 py-2 rounded-lg inline-block">
            Logout
        </a>
        </div>
    </nav>


    <!-- ================= Category button ================= -->
    <section class="p-6 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <div class="flex gap-3 p-4 ">
            <button id="allbtn" class="filter-btn bg-gray-700 px-4 py-2 rounded" onclick="filterProducts('all')">All</button>
            <button id="beautybtn" class="filter-btn bg-gray-700 px-4 py-2 rounded" onclick="filterProducts('beauty')">Beauty</button>
            <button id="fragrancesbtn" class="filter-btn bg-gray-700 px-4 py-2 rounded" onclick="filterProducts('fragrances')">Fragnance</button>
            <button id="furniturebtn" class="filter-btn bg-gray-700 px-4 py-2 rounded" onclick="filterProducts('furniture')">Furniture</button>
            <button id="groceriesbtn"class="filter-btn bg-gray-700 px-4 py-2 rounded" onclick="filterProducts('groceries')">Groceries</button>
        </div>
    </section>


    <!-- ================= PRODUCTS ================= -->
    <div id="product-container"
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-4">

    </div>

    <!-- ================= MORE SECTION ================= -->
    <section class="p-6 bg-gray-800 mt-6">

        <h2 class="text-xl font-bold mb-4">Why Shop With Us?</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="p-4 bg-gray-700 rounded-lg text-center">
            ⚡ Fast Delivery
        </div>

        <div class="p-4 bg-gray-700 rounded-lg text-center">
            🔒 Secure Payment
        </div>

        <div class="p-4 bg-gray-700 rounded-lg text-center">
            ⭐ Top Quality Products
        </div>

        </div>

    </section>
        
    <!-- Modal Container -->
<!-- Modal Background -->
<div id="modal-container" class="fixed inset-0 bg-black/60 hidden justify-center items-center z-50">
    <!-- Modal Box -->
    <div class="bg-[#111] text-white w-[90%] md:w-[70%] lg:w-[50%] rounded-2xl overflow-hidden shadow-xl relative">

        <!-- Close Button -->
            <button id="close-modal"
                class="absolute top-3 right-3 text-white text-xl">
            ✖
            </button>

        <!-- Content -->
        <div id="modal-box" class="grid md:grid-cols-2 gap-4 p-4">
            <!-- Dynamic content will come here -->
        </div>

    </div>
</div>

    

    <!-- ================= FOOTER ================= -->
    <footer class="bg-gray-900 p-6 text-center text-gray-400">
        © 2026 Gadget Hub. All rights reserved.
    </footer>

    <script src="../js/api.js?v=20260427-2"></script>
    <script src="../js/ui.js?v=20260427-2"></script>
    <script src="../js/filter.js"></script>
    <script src="../js/buttonfunctionality.js"></script>
    <script src="../js/wishlist.js"></script>
    <script src="../js/cart.js?v=20260427-2"></script> 
    <!-- <script src="../js/addtocart.js"></script> -->
    </body>
</html>

