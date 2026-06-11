<?php
session_start();
include 'admin/inc/config.php'; // Kết nối cơ sở dữ liệu

if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    $userId = $_SESSION['customer']['cust_id'] ?? null;

    // Kiểm tra nếu người dùng đã đăng nhập
    if (!$userId) {
        echo json_encode(['message' => 'Vui lòng đăng nhập để thêm vào wishlist.']);
        exit;
    }

    // Kiểm tra xem sản phẩm đã có trong wishlist chưa
    $statement = $pdo->prepare("SELECT * FROM wishlist WHERE customer_id = ? AND product_id = ?");
    $statement->execute([$userId, $productId]);

    if ($statement->rowCount() == 0) {
        // Thêm vào wishlist
        $insert = $pdo->prepare("INSERT INTO wishlist (id, customer_id, product_id, created_at) VALUES (UUID(), ?, ?, NOW())");
        if ($insert->execute([$userId, $productId])) {
            echo json_encode(['message' => 'Đã thêm vào wishlist!']);
        } else {
            echo json_encode(['message' => 'Có lỗi xảy ra: ' . implode(", ", $insert->errorInfo())]);
        }
    } else {
        echo json_encode(['message' => 'Sản phẩm đã có trong wishlist.']);
    }
} else {
    echo json_encode(['message' => 'ID sản phẩm không hợp lệ.']);
}
error_log(print_r($insert->errorInfo(), true)); // Ghi log thông tin lỗi vào file log
?>