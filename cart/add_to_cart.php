<?php
session_start();

header("Content-Type: application/json");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    require_once __DIR__ . "/../config/db.php";

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

    $userId = (int) $_SESSION["user_id"];
    $productId = (int) ($_POST["product_id"] ?? 0);
    $productTitle = trim($_POST["product_title"] ?? $_POST["product_name"] ?? "");
    $productPrice = (float) ($_POST["product_price"] ?? $_POST["price"] ?? 0);
    $productImage = trim($_POST["product_image"] ?? "");
    $productCategory = trim($_POST["product_category"] ?? "");

    if ($productId <= 0 || $productTitle === "" || $productPrice <= 0) {
        http_response_code(422);
        echo json_encode([
            "success" => false,
            "message" => "Missing product details."
        ]);
        exit();
    }

    $checkStmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND product_id = ?");
    $checkStmt->bind_param("ii", $userId, $productId);
    $checkStmt->execute();

    if ($checkStmt->get_result()->num_rows > 0) {
        echo json_encode([
            "success" => false,
            "message" => "Already in cart."
        ]);
        exit();
    }

    $insertStmt = $conn->prepare(
        "INSERT INTO cart (user_id, product_id, product_title, product_price, product_image, product_category)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $insertStmt->bind_param("iisdss", $userId, $productId, $productTitle, $productPrice, $productImage, $productCategory);
    $insertStmt->execute();

    echo json_encode([
        "success" => true,
        "message" => "Added to cart."
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Unable to add to cart.",
        "error" => $e->getMessage()
    ]);
}
?>
