<?php
include "admin/models/connectdb.php";

$conn = connectdb();

// Check all users
echo "<h2>Tất cả user trong database:</h2>";
$sql = "SELECT id, email, mat_khau, vai_tro FROM tbl_nguoidung LIMIT 20";
$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>";
var_dump($users);
echo "</pre>";

// Check specific user
echo "<h2>User với email theanh04@gmail.com:</h2>";
$sql2 = "SELECT id, email, mat_khau, vai_tro FROM tbl_nguoidung WHERE email = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute(['theanh04@gmail.com']);
$user = $stmt2->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>";
var_dump($user);
echo "</pre>";

echo "<h2>Expected password hash: 0c35bf5e1e0b76c238995420e29c9065</h2>";
?>
