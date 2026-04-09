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
$title = $data['product_title'];
$price = $data['product_price'];
$image = $data['product_image'];
$category = $data['product_category'];

$check = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
$check->bind_param("ii", $user_id, $product_id);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Already in wishlist"]);
    exit();
}

$stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id, product_title, product_price, product_image, product_category) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iisdss", $user_id, $product_id, $title, $price, $image, $category);

echo json_encode(["success" => $stmt->execute()]);