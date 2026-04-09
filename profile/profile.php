<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: /GadgetHub/loginform.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Fetch orders
$orderStmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY ordered_at DESC");
$orderStmt->bind_param("i", $user_id);
$orderStmt->execute();
$orders = $orderStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch wishlist
$wishStmt = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ?");
$wishStmt->bind_param("i", $user_id);
$wishStmt->execute();
$wishlist = $wishStmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - Gadget Hub</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen p-6">

    <!-- Back button -->
    <a href="../dashboard/dashboard.php" class="text-blue-400 hover:underline mb-6 inline-block">← Back to Dashboard</a>

    <!-- Profile Card -->
    <div class="bg-gray-800 rounded-2xl p-6 max-w-xl mx-auto mb-8">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-blue-500 rounded-full w-16 h-16 flex items-center justify-center text-2xl font-bold">
                <?= strtoupper(substr($user['name'], 0, 1)) ?>
            </div>
            <div>
                <h2 class="text-2xl font-bold"><?= htmlspecialchars($user['name']) ?></h2>
                <p class="text-gray-400"><?= htmlspecialchars($user['email']) ?></p>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="max-w-4xl mx-auto">
        <div class="flex gap-4 mb-6">
            <button onclick="showTab('orders')" id="tab-orders"
                class="tab-btn px-5 py-2 rounded-xl bg-blue-600 text-white font-semibold">
                My Orders
            </button>
            <button onclick="showTab('wishlist')" id="tab-wishlist"
                class="tab-btn px-5 py-2 rounded-xl bg-gray-700 text-white font-semibold">
                ♡ Wishlist
            </button>
        </div>

        <!-- Orders Tab -->
        <div id="orders-tab">
            <?php if (empty($orders)): ?>
                <p class="text-gray-400">You have no orders yet.</p>
            <?php else: ?>
                <div class="grid gap-4">
                <?php foreach ($orders as $order): ?>
                    <div class="bg-gray-800 rounded-xl p-4 flex items-center gap-4">
                        <img src="<?= htmlspecialchars($order['product_image']) ?>" 
                            class="w-16 h-16 object-cover rounded-lg bg-white p-1">
                        <div class="flex-1">
                            <h3 class="font-semibold"><?= htmlspecialchars($order['product_title']) ?></h3>
                            <p class="text-green-400">$<?= $order['product_price'] ?></p>
                            <p class="text-xs text-gray-500">Ordered: <?= $order['ordered_at'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Wishlist Tab -->
        <div id="wishlist-tab" class="hidden">
            <?php if (empty($wishlist)): ?>
                <p class="text-gray-400">Your wishlist is empty.</p>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <?php foreach ($wishlist as $item): ?>
                    <div class="bg-gray-800 rounded-xl p-4">
                        <img src="<?= htmlspecialchars($item['product_image']) ?>" 
                            class="w-full h-36 object-contain bg-white rounded-lg p-2 mb-3">
                        <h3 class="font-semibold text-sm line-clamp-2"><?= htmlspecialchars($item['product_title']) ?></h3>
                        <p class="text-green-400 mt-1">$<?= $item['product_price'] ?></p>
                        <form method="POST" action="../api/wishlist_remove_form.php">
                            <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                            <button type="submit" class="mt-3 w-full bg-red-600 hover:bg-red-700 py-1 rounded-lg text-sm">
                                Remove
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const showTab = (tab) => {
            document.getElementById("orders-tab").classList.add("hidden");
            document.getElementById("wishlist-tab").classList.add("hidden");
            document.getElementById("tab-orders").className = "tab-btn px-5 py-2 rounded-xl bg-gray-700 text-white font-semibold";
            document.getElementById("tab-wishlist").className = "tab-btn px-5 py-2 rounded-xl bg-gray-700 text-white font-semibold";

            document.getElementById(tab + "-tab").classList.remove("hidden");
            document.getElementById("tab-" + tab).className = "tab-btn px-5 py-2 rounded-xl bg-blue-600 text-white font-semibold";
        };
    </script>
</body>
</html>