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
            "orders" => []
        ]);
        exit();
    }

    $userId = (int) $_SESSION["user_id"];

    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY ordered_at DESC, id DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    echo json_encode([
        "success" => true,
        "orders" => $orders
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Unable to load orders.",
        "error" => $e->getMessage(),
        "orders" => []
    ]);
}
?>
