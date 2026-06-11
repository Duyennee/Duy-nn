<?php require_once('header.php'); ?>
<?php
$priceRange = isset($_GET['price_range']) ? $_GET['price_range'] : '';
$orderBy = 'p_id'; // Mặc định sắp xếp theo ID

// Kiểm tra xem có giá trị sắp xếp nào không
if (isset($_GET['sort_by'])) {
    switch ($_GET['sort_by']) {
        case 'name_asc':
            $orderBy = 'p_name ASC';
            break;
        case 'name_desc':
            $orderBy = 'p_name DESC';
            break;
        case 'price_asc':
            $orderBy = 'ROUND(p_old_price * (100 - discount_percent) / 100) ASC';
            break;
        case 'price_desc':
            $orderBy = 'ROUND(p_old_price * (100 - discount_percent) / 100) DESC';
            break;
        default:
            $orderBy = 'p_id';
            break;
    }
}

$statement = $pdo->prepare("SELECT home_featured FROM tbl_settings WHERE id = 1");
$statement->execute();
$row = $statement->fetch(PDO::FETCH_ASSOC);
?>

<div class="bannermenu">
    <div class="detail">
        <h1>CHÀO MỪNG BẠN ĐẾN VỚI "Min Min"</h1>
        <p>
            👉 Chúng tôi luôn theo dõi các xu hướng mới nhất và đặt mong muốn của khách hàng lên hàng đầu. Đó là lý do tại sao chúng tôi đã làm hài lòng khách hàng trên toàn thế giới và rất vui khi được trở thành một phần của bạn.<br>
            👉 Lợi ích của khách hàng luôn là ưu tiên hàng đầu đối với chúng tôi, vì vậy chúng tôi hy vọng bạn sẽ thích sản phẩm của chúng tôi nhiều như chúng tôi thích cung cấp chúng cho bạn.<br>
            👉 Chúng tôi đảm bảo bạn sẽ nhận được những bộ trang phục chất lượng tốt nhất với chính sách đổi trả dễ dàng. Chúng tôi đảm bảo những gì bạn thấy chính xác là những gì bạn nhận được!
        </p>
        <span><a href="index.php">Trang chủ</a><i class="bx bx-right-arrow-alt"></i>Menu</span>
    </div>
</div>

<div class="product pt_70 pb_70">
    <div class="heading">
        <h1><?php echo 'TẤT CẢ CÁC SẢN PHẨM'; ?></h1>
    </div>

    <form method="GET" action="">
        <label for="price_range">Chọn khoảng giá:</label>
        <select name="price_range" id="price_range">
            <option value="">Tất cả</option>
            <option value="0-100000" <?= ($priceRange == '0-100000') ? 'selected' : ''; ?>>Dưới 100.000 vnđ</option>
            <option value="100000-500000" <?= ($priceRange == '100000-500000') ? 'selected' : ''; ?>>100.000 - 500.000 vnđ</option>
            <option value="500000-1000000" <?= ($priceRange == '500000-1000000') ? 'selected' : ''; ?>>500.000 - 1.000.000 vnđ</option>
            <option value="1000000-5000000" <?= ($priceRange == '1000000-5000000') ? 'selected' : ''; ?>>1.000.000 - 5.000.000 vnđ</option>
            <option value="5000000-" <?= ($priceRange == '5000000-') ? 'selected' : ''; ?>>Trên 5.000.000 vnđ</option>
        </select>

        <label for="sort_by">Sắp xếp theo:</label>
        <select name="sort_by" id="sort_by">
            <option value="">Mặc định</option>
            <option value="name_asc" <?= ($_GET['sort_by'] ?? '') == 'name_asc' ? 'selected' : ''; ?>>Tên A-Z</option>
            <option value="name_desc" <?= ($_GET['sort_by'] ?? '') == 'name_desc' ? 'selected' : ''; ?>>Tên Z-A</option>
            <option value="price_asc" <?= ($_GET['sort_by'] ?? '') == 'price_asc' ? 'selected' : ''; ?>>Giá tăng dần</option>
            <option value="price_desc" <?= ($_GET['sort_by'] ?? '') == 'price_desc' ? 'selected' : ''; ?>>Giá giảm dần</option>
            
        </select>
        <button type="submit">Lọc</button>
    </form>

    <div class="rowmenu">
        <?php
        $p_total_view = isset($row['p_total_view']) ? $row['p_total_view'] : 0;
$p_total_view += 1; // Tăng giá trị lên 1

if (isset($_REQUEST['id'])) {
    $statement = $pdo->prepare("UPDATE tbl_product SET p_total_view=? WHERE p_id=?");
    $statement->execute(array($p_total_view, $_REQUEST['id']));
}

        

        if ($priceRange) {
            $priceParts = explode('-', $priceRange);
            $minPrice = isset($priceParts[0]) ? (int)$priceParts[0] : 0;
            $maxPrice = (isset($priceParts[1]) && $priceParts[1] !== '') ? (int)$priceParts[1] : PHP_INT_MAX;

            $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE ROUND(p_old_price * (100 - discount_percent) / 100) BETWEEN ? AND ? ORDER BY $orderBy");
            $statement->execute([$minPrice, $maxPrice]);
        } else {
            $statement = $pdo->prepare("SELECT * FROM tbl_product ORDER BY $orderBy");
            $statement->execute();
        }

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (count($result) > 0) {
            foreach ($result as $row) {
                ?>
                <div class="item">
                    <div class="thumb">
                        <div class="photo" style="background-image:url(assets/uploads/<?php echo $row['p_featured_photo']; ?>);">
                            <?php if ($row['discount_percent'] > 0): ?>
                                <div style="position: absolute; top: 10px; right: 10px; background-color: red; color: white; padding: 5px 10px; border-radius: 5px; font-weight: bold; z-index: 10;">
                                    <?php echo $row['discount_percent']; ?>%
                                </div>
                            <?php endif; ?>
                            <div class="product-views" style="position:absolute; left:10px; background-color: rgba(255,255,255,0.8); padding: 5px 10px; border-radius: 5px; font-size:12px;">
                                <i class="fa fa-eye"></i> <?php echo $row['p_total_view']; ?> lượt xem
                            </div>
                        </div>
                        <div class="overlay"></div>
                    </div>
                    <div class="text">
                        <h3><a href="product.php?id=<?php echo $row['p_id']; ?>"><?php echo $row['p_name']; ?></a></h3>
                        <h4>
                            <?php
                            $oldPriceVnd = $row['p_old_price'];
                            $discountPercent = $row['discount_percent'];
                            $currentPriceVnd = round($oldPriceVnd * (100 - $discountPercent) / 100);

                            if ($discountPercent > 0) {
                                echo '<del>' . number_format($oldPriceVnd, 0, ',', '.') . ' vnđ</del> ';
                            }
                            echo number_format($currentPriceVnd, 0, ',', '.') . ' vnđ';
                            ?>
                        </h4>

                        <div class="rating">
                            <?php
                            $t_rating = 0;
                            $statement1 = $pdo->prepare("SELECT * FROM tbl_rating WHERE p_id=?");
                            $statement1->execute([$row['p_id']]);
                            $tot_rating = $statement1->rowCount();

                            if ($tot_rating == 0) {
                                $avg_rating = 0;
                            } else {
                                $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result1 as $row1) {
                                    $t_rating += $row1['rating'];
                                }
                                $avg_rating = $t_rating / $tot_rating;
                            }

                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $avg_rating ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star-o"></i>';
                            }
                            ?>
                        </div>

                        <?php if ($row['p_qty'] == 0): ?>
                            <div class="out-of-stock">
                                <div class="inner">Hết hàng</div>
                            </div>
                        <?php else: ?>
                            <p><a href="product.php?id=<?php echo $row['p_id']; ?>"><i class="fa fa-shopping-cart"></i> Thêm vào giỏ </a></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<p>Không có sản phẩm nào trong khoảng giá đã chọn.</p>';
        }
        ?>
    </div>
</div>

<div class="containermenu">
    <div class="left">
        <img src="admin/img/b7.png" alt="LOFI CHIC collection" />
    </div>
    <div class="rightmenu">
        <h1>LOFI CHIC | NEW COLLECTION</h1><br>
        <p>
            💕Thời trang không đơn thuần chỉ là những bộ trang phục, mà là cách chúng ta kể câu chuyện của chính mình thông qua ngôn ngữ giao tiếp không lời. Được thiết kế dành riêng cho những tâm hồn duy mỹ và yêu cái đẹp, 𝐋𝐎𝐅𝐈 𝐂𝐇𝐈𝐂 là hành trình khám phá, theo đuổi và khẳng định bản sắc cá nhân của những quý cô hiện đại thông qua thời trang. 
        </p>
        <button class="button">Xem sản phẩm NEW COLLECT</button>
    </div>
</div>

<?php require_once('footer.php'); ?>