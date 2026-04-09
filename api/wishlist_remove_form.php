<?php
session_start();
include("../config/db.php");

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

$stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();

header("Location: ../profile/profile.php");
exit();