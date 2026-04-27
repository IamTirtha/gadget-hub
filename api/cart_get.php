<?php
session_start();

header("Content-Type: application/json");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    require_once __DIR__ . "/../config/db.php";

    if (!isset($_SESSION["user_id"])) {
        http_response_code(401);
        echo json_encode([
            "success" => false,
            "message" => "User not logged in.",
            "items" => [],
            "total" => 0
        ]);
        exit();
    }

    $userId = (int) $_SESSION["user_id"];

    $stmt = $conn->prepare(
        "SELECT id, product_id, product_title, product_price, product_image, product_category, added_at
         FROM cart
         WHERE user_id = ?
         ORDER BY id DESC"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    $total = 0;

    while ($row = $result->fetch_assoc()) {
        $item = [
            "id" => (int) $row["id"],
            "product_id" => (int) $row["product_id"],
            "product_title" => $row["product_title"],
            "product_price" => (float) $row["product_price"],
            "product_image" => $row["product_image"],
            "product_category" => $row["product_category"],
            "quantity" => 1,
            "added_at" => $row["added_at"]
        ];

        $items[] = $item;
        $total += $item["product_price"];
    }

    echo json_encode([
        "success" => true,
        "user_id" => $userId,
        "user_name" => $_SESSION["user_name"] ?? "",
        "items" => $items,
        "total" => $total
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Unable to load cart.",
        "error" => $e->getMessage(),
        "items" => [],
        "total" => 0
    ]);
}
?>
