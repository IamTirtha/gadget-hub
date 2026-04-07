<?php
session_start();
include("../config/db.php");

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    header("Location: /GadgetHub/loginform.html?error=1");
    exit();
}

$stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header("Location: /GadgetHub/dashboard/dashboard.php");
        exit();
    }
}

header("Location: /GadgetHub/loginform.html?error=1");
exit();
