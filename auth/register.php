

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include("../config/db.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /GadgetHub/signinform.html");
    exit();
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

if ($name === '' || $email === '' || $password === '' || $confirmPassword === '') {
    header("Location: /GadgetHub/signinform.html?error=missing");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: /GadgetHub/signinform.html?error=email");
    exit();
}

if ($password !== $confirmPassword) {
    header("Location: /GadgetHub/signinform.html?error=password");
    exit();
}

$checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$existingUser = $checkStmt->get_result();

if ($existingUser->num_rows > 0) {
    header("Location: /GadgetHub/signinform.html?error=exists");
    exit();
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$insertStmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$insertStmt->bind_param("sss", $name, $email, $hashedPassword);

if ($insertStmt->execute()) {
    header("Location: /GadgetHub/loginform.html?registered=1");
    exit();
}

header("Location: /GadgetHub/signinform.html?error=server");
exit();
