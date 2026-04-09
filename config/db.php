<?php
$host = "sql305.infinityfree.com";
$user = "if0_41616374";
$pass = "SreeTirtha20";
$db   = "if0_41616374_gadget_Hub_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>