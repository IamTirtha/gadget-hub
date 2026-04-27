<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: /GadgetHub/loginform.html");
    exit();
}

$userId = (int) $_SESSION["user_id"];

$userStmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$userStmt->bind_param("i", $userId);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();

$orderStmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY ordered_at DESC, id DESC");
$orderStmt->bind_param("i", $userId);
$orderStmt->execute();
$orders = $orderStmt->get_result()->fetch_all(MYSQLI_ASSOC);

$wishStmt = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ?");
$wishStmt->bind_param("i", $userId);
$wishStmt->execute();
$wishlist = $wishStmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - GadgetHub</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-gray-900 p-6 text-white">
    <a href="/GadgetHub/dashboard/dashboard.php" class="mb-6 inline-block text-blue-400 hover:underline">
        Back to Dashboard
    </a>

    <div class="mx-auto mb-8 max-w-xl rounded-2xl bg-gray-800 p-6">
        <div class="flex items-center gap-4">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-500 text-2xl font-bold">
                <?= strtoupper(substr($user["name"], 0, 1)) ?>
            </div>
            <div>
                <h1 class="text-2xl font-bold"><?= htmlspecialchars($user["name"]) ?></h1>
                <p class="text-gray-400"><?= htmlspecialchars($user["email"]) ?></p>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-5xl">
        <div class="mb-6 flex gap-4">
            <button onclick="showTab('orders')" id="tab-orders" class="rounded-xl bg-blue-600 px-5 py-2 font-semibold text-white">
                My Orders
            </button>
            <button onclick="showTab('wishlist')" id="tab-wishlist" class="rounded-xl bg-gray-700 px-5 py-2 font-semibold text-white">
                Wishlist
            </button>
        </div>

        <div id="orders-tab">
            <?php if (empty($orders)): ?>
                <p class="text-gray-400">You have no orders yet.</p>
            <?php else: ?>
                <div class="grid gap-4">
                    <?php foreach ($orders as $order): ?>
                        <div class="flex items-center gap-4 rounded-xl bg-gray-800 p-4">
                            <img
                                src="<?= htmlspecialchars($order["product_image"] ?: "") ?>"
                                alt="<?= htmlspecialchars($order["product_title"]) ?>"
                                class="h-16 w-16 rounded-lg bg-white object-contain p-1"
                            >
                            <div class="min-w-0 flex-1">
                                <h2 class="truncate font-semibold"><?= htmlspecialchars($order["product_title"]) ?></h2>
                                <p class="text-green-400">Rs. <?= number_format((float) $order["product_price"], 2) ?></p>
                                <p class="text-sm text-gray-400">
                                    <?= htmlspecialchars($order["pickup_location"] ?? "Pickup") ?> to <?= htmlspecialchars($order["destination"] ?? "Destination") ?>
                                </p>
                                <p class="text-sm text-gray-400">Quantity: <?= (int) ($order["quantity"] ?? 1) ?></p>
                                <p class="text-xs text-gray-500">Ordered: <?= htmlspecialchars($order["ordered_at"]) ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">Total</p>
                                <p class="font-semibold text-blue-300">Rs. <?= number_format((float) ($order["total_amount"] ?? $order["product_price"]), 2) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div id="wishlist-tab" class="hidden">
            <?php if (empty($wishlist)): ?>
                <p class="text-gray-400">Your wishlist is empty.</p>
            <?php else: ?>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                    <?php foreach ($wishlist as $item): ?>
                        <div class="rounded-xl bg-gray-800 p-4">
                            <img
                                src="<?= htmlspecialchars($item["product_image"]) ?>"
                                alt="<?= htmlspecialchars($item["product_title"]) ?>"
                                class="mb-3 h-36 w-full rounded-lg bg-white object-contain p-2"
                            >
                            <h3 class="line-clamp-2 text-sm font-semibold"><?= htmlspecialchars($item["product_title"]) ?></h3>
                            <p class="mt-1 text-green-400">Rs. <?= number_format((float) $item["product_price"], 2) ?></p>
                            <form method="POST" action="../api/wishlist_remove_form.php">
                                <input type="hidden" name="product_id" value="<?= (int) $item["product_id"] ?>">
                                <button type="submit" class="mt-3 w-full rounded-lg bg-red-600 py-1 text-sm hover:bg-red-700">
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
        function showTab(tab) {
            document.getElementById("orders-tab").classList.add("hidden");
            document.getElementById("wishlist-tab").classList.add("hidden");
            document.getElementById("tab-orders").className = "rounded-xl bg-gray-700 px-5 py-2 font-semibold text-white";
            document.getElementById("tab-wishlist").className = "rounded-xl bg-gray-700 px-5 py-2 font-semibold text-white";

            document.getElementById(tab + "-tab").classList.remove("hidden");
            document.getElementById("tab-" + tab).className = "rounded-xl bg-blue-600 px-5 py-2 font-semibold text-white";
        }
    </script>
</body>
</html>
