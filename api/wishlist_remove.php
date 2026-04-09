<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$user_id = $_SESSION['user_id'];
$product_id = $data['product_id'];

$stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);

echo json_encode(["success" => $stmt->execute()]);