<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$user_id = $_SESSION['user_id'];
$product_id = $data['product_id'];
$title = $data['product_title'];
$price = $data['product_price'];
$image = $data['product_image'];
$category = $data['product_category'];

// Check if already in cart
$check = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND product_id = ?");
$check->bind_param("ii", $user_id, $product_id);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Already in cart"]);
    exit();
}

$stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, product_title, product_price, product_image, product_category) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iisdss", $user_id, $product_id, $title, $price, $image, $category);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "DB error"]);
}