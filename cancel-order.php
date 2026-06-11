<?php
require_once('header.php'); // File kết nối PDO
session_start();

if (!isset($_SESSION['customer'])) {
    header('location: logout.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_id'])) {
    $payment_id = $_POST['payment_id'];
    $cust_email = $_SESSION['customer']['cust_email'];

    // Lấy thông tin đơn hàng cần hủy
    $stmt = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_id = ? AND customer_email = ?");
    $stmt->execute([$payment_id, $cust_email]);

    if ($stmt->rowCount() > 0) {
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order['shipping_status'] === 'Pending') {
            // Cập nhật trạng thái đơn hàng
            $update = $pdo->prepare("UPDATE tbl_payment SET shipping_status = 'Completed' WHERE payment_id = ?");
            $update->execute([$payment_id]);

            $_SESSION['cancel_success'] = "Đơn hàng đã được hủy thành công.";
        } else {
            $_SESSION['cancel_error'] = "Chỉ có thể hủy đơn hàng đang chờ xử lý.";
        }
    } else {
        $_SESSION['cancel_error'] = "Không tìm thấy đơn hàng hợp lệ.";
    }
} else {
    $_SESSION['cancel_error'] = "Yêu cầu không hợp lệ.";
}

// Quay lại trang lịch sử đơn hàng
header('Location: customer-order.php');
exit;
