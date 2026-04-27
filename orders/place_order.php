<?php
session_start();

header("Content-Type: application/json");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function ensureOrdersColumns(mysqli $conn): void
{
    $requiredColumns = [
        "pickup_location" => "ALTER TABLE orders ADD COLUMN pickup_location VARCHAR(100) NULL AFTER quantity",
        "destination" => "ALTER TABLE orders ADD COLUMN destination VARCHAR(100) NULL AFTER pickup_location",
        "total_amount" => "ALTER TABLE orders ADD COLUMN total_amount DECIMAL(10,2) NULL AFTER destination"
    ];

    foreach ($requiredColumns as $columnName => $sql) {
        $check = $conn->prepare("SHOW COLUMNS FROM orders LIKE ?");
        $check->bind_param("s", $columnName);
        $check->execute();
        $exists = $check->get_result()->num_rows > 0;

        if (!$exists) {
            $conn->query($sql);
        }
    }
}

try {
    require_once __DIR__ . "/../config/db.php";
    $transactionStarted = false;

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);
        echo json_encode([
            "success" => false,
            "message" => "Invalid request method."
        ]);
        exit();
    }

    if (!isset($_SESSION["user_id"])) {
        http_response_code(401);
        echo json_encode([
            "success" => false,
            "message" => "User not logged in."
        ]);
        exit();
    }

    $pickup = trim($_POST["pickup"] ?? "");
    $destination = trim($_POST["destination"] ?? "");

    if ($pickup === "" || $destination === "") {
        http_response_code(422);
        echo json_encode([
            "success" => false,
            "message" => "Pickup and destination are required."
        ]);
        exit();
    }

    $userId = (int) $_SESSION["user_id"];

    ensureOrdersColumns($conn);

    $cartStmt = $conn->prepare(
        "SELECT product_id, product_title, product_price, product_image, product_category
         FROM cart
         WHERE user_id = ?
         ORDER BY id ASC"
    );
    $cartStmt->bind_param("i", $userId);
    $cartStmt->execute();
    $cartResult = $cartStmt->get_result();

    $cartItems = [];
    $totalAmount = 0;

    while ($row = $cartResult->fetch_assoc()) {
        $row["product_id"] = (int) $row["product_id"];
        $row["product_price"] = (float) $row["product_price"];
        $row["quantity"] = 1;
        $row["product_category"] = $row["product_category"] ?? "";
        $cartItems[] = $row;
        $totalAmount += $row["product_price"] * $row["quantity"];
    }

    if (count($cartItems) === 0) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Your cart is empty."
        ]);
        exit();
    }

    $conn->begin_transaction();
    $transactionStarted = true;

    $orderStmt = $conn->prepare(
        "INSERT INTO orders (
            user_id,
            product_id,
            product_title,
            product_price,
            product_image,
            quantity,
            pickup_location,
            destination,
            total_amount
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $orderItemStmt = $conn->prepare(
        "INSERT INTO order_items (order_id, product_name, price, quantity)
         VALUES (?, ?, ?, ?)"
    );

    foreach ($cartItems as $item) {
        $quantity = (int) $item["quantity"];
        $price = (float) $item["product_price"];
        $productId = (int) $item["product_id"];
        $productTitle = $item["product_title"];
        $productImage = $item["product_image"];
        $itemPriceForOrderItems = (int) round($price);

        $orderStmt->bind_param(
            "iisdsissd",
            $userId,
            $productId,
            $productTitle,
            $price,
            $productImage,
            $quantity,
            $pickup,
            $destination,
            $totalAmount
        );
        $orderStmt->execute();

        $orderId = $conn->insert_id;

        $orderItemStmt->bind_param(
            "isii",
            $orderId,
            $productTitle,
            $itemPriceForOrderItems,
            $quantity
        );
        $orderItemStmt->execute();
    }

    $clearCartStmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $clearCartStmt->bind_param("i", $userId);
    $clearCartStmt->execute();

    $conn->commit();

    echo json_encode([
        "success" => true,
        "message" => "Order placed successfully.",
        "redirect" => "/GadgetHub/profile/profile.php",
        "total" => $totalAmount,
        "item_count" => count($cartItems)
    ]);
} catch (Throwable $e) {
    if (isset($conn, $transactionStarted) && $conn instanceof mysqli && $transactionStarted) {
        $conn->rollback();
    }

    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Unable to place order.",
        "error" => $e->getMessage()
    ]);
}
?>
