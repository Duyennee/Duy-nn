<?php
require_once('header.php');
require_once('admin/inc/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = strip_tags($_POST['username']);
    $message = strip_tags($_POST['message']);
    $admin_id = $_POST['admin_id'];
    $customer_id = $_POST['customer_id'];

    // Lưu tin nhắn vào cơ sở dữ liệu
    $stmt = $pdo->prepare("INSERT INTO messages (username, message, is_admin, customer_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $message, 0, $customer_id]); // 0 cho khách hàng

    // Chuyển hướng trở lại contact.php
    header("Location: contact.php");
    exit();
}
?>