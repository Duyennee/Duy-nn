<?php
require_once('header.php');
require_once('admin/inc/config.php');

if (!isset($_SESSION['customer'])) {
    header('Location: login.php');
    exit;
}

$customer_id = $_SESSION['customer']['cust_id'];
$products = [];
$statement = $pdo->prepare("SELECT p.* FROM wishlist w JOIN tbl_product p ON w.product_id = p.p_id WHERE w.customer_id = ?");
$statement->execute([$customer_id]);
$products = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sản Phẩm Yêu Thích</title>
    <style>
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap; /* Cho phép các phần tử xuống dòng */
            justify-content: space-between; /* Căn giữa các cột */
            margin: -10px; /* Điều chỉnh khoảng cách giữa các cột */
}
.old-price {
    text-decoration: line-through;
    color: #999;
    margin-right: 8px;
}

.item {
    width: 23%; /* Chiều rộng mỗi cột */
    box-sizing: border-box; /* Đảm bảo padding không làm rộng thêm */
    margin: 10px; /* Khoảng cách giữa các hàng và các cột */
}

.thumb {
    height: 120px; /* Chiều cao hình ảnh */
    background-size: cover;
    background-position: center;
    margin-bottom: 10px; /* Khoảng cách giữa hình ảnh và tên sản phẩm */
}
        .text {
            flex: 1;
        }
        h3 {
            font-size: 1.2em;
            color: #007bff;
        }
        h4 {
            font-size: 1em;
            color: #555;
        }
        .rating {
            margin: 5px 0;
        }
        .rating i {
            color: #FFD700; /* Màu vàng cho sao */
        }
        p a {
            color: #007bff;
            text-decoration: none;
        }
        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sản Phẩm Yêu Thích</h1>
        <?php if (empty($products)): ?>
            <p>Chưa có sản phẩm nào trong danh sách yêu thích.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($products as $row): ?>
                    <li class="item">
                        <div class="thumb" style="background-image:url(assets/uploads/<?php echo htmlspecialchars($row['p_featured_photo']); ?>);"></div>
                        <div class="text">
                            <h3><a href="product.php?id=<?php echo $row['p_id']; ?>"><?php echo htmlspecialchars($row['p_name']); ?></a></h3>
                            <h4>
                                   <?php
    // 1) Chuyển giá gốc về VNĐ
    $oldPriceVnd       = $row['p_old_price'];
    $discountPercent   = $row['discount_percent'];
    // 2) Tính giá đã giảm
    $p_current_price   = round($oldPriceVnd * (100 - $discountPercent) / 100);

    // 3) Hiển thị
    if ($discountPercent > 0) {
        // Giá gốc gạch ngang
        echo '<del>' 
             . number_format($oldPriceVnd, 0, ',', '.') 
             . ' vnđ</del> ';
    }
    // Giá sau giảm
    echo number_format($p_current_price, 0, ',', '.') . ' vnđ';
?>
                                </h4>
                            <div class="rating">
                                <?php
                                $t_rating = 0;
                                $statement1 = $pdo->prepare("SELECT * FROM tbl_rating WHERE p_id=?");
                                $statement1->execute([$row['p_id']]);
                                $tot_rating = $statement1->rowCount();
                                if ($tot_rating > 0) {
                                    $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($result1 as $row1) {
                                        $t_rating += $row1['rating'];
                                    }
                                    $avg_rating = $t_rating / $tot_rating;

                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $avg_rating) {
                                            echo '<i class="fa fa-star"></i>';
                                        } else {
                                            echo '<i class="fa fa-star-o"></i>';
                                        }
                                    }
                                }
                                ?>
                            </div>
                            <p><a href="product.php?id=<?php echo $row['p_id']; ?>">Xem chi tiết</a></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <a href="index.php">Quay lại trang chủ</a>
    </div>
</body>
</html>

<?php require_once('footer.php'); ?>