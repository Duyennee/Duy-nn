<?php require_once('header.php'); ?>

<?php

if (isset($_SESSION['cancel_success'])) {
    echo "<div class='alert alert-success'>".$_SESSION['cancel_success']."</div>";
    unset($_SESSION['cancel_success']);
}

if (isset($_SESSION['cancel_error'])) {
    echo "<div class='alert alert-danger'>".$_SESSION['cancel_error']."</div>";
    unset($_SESSION['cancel_error']);
}
// Kiểm tra xem khách hàng đã đăng nhập hay chưa
if (!isset($_SESSION['customer'])) {
    header('location: ' . BASE_URL . 'logout.php');
    exit;
} else {
    // Nếu khách hàng đã đăng nhập nhưng quản trị viên đã làm cho họ không hoạt động
    $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_id=? AND cust_status=?");
    $statement->execute(array($_SESSION['customer']['cust_id'], 0));
    $total = $statement->rowCount();
    if ($total) {
        header('location: ' . BASE_URL . 'logout.php');
        exit;
    }
}
?>

<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php require_once('customer-sidebar.php'); ?>
            </div>
            <div class="col-md-12">
                <div class="user-content">
                    <h3><?php echo 'Lịch sử đặt hàng'; ?></h3>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th><?php echo '#' ?></th>
                                    <th><?php echo 'Sản phẩm'; ?></th>
                                    <th><?php echo 'Thời gian'; ?></th>
                                    <th><?php echo 'ID'; ?></th>
                                    <th><?php echo 'Giá'; ?></th>
                                    <th><?php echo 'Tình trạng'; ?></th>
                                    <th><?php echo 'Phương thức'; ?></th>
                                    <th><?php echo 'Mã giao dịch'; ?></th>
                                </tr>
                            </thead>
                            <tbody>

            <?php
            // ===================== Pagination Code Starts ==================
            $adjacents = 5;

            $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE customer_email=? ORDER BY id DESC");
            $statement->execute(array($_SESSION['customer']['cust_email']));
            $total_pages = $statement->rowCount();

            $targetpage = BASE_URL . 'customer-order.php';
            $limit = 10;
            $page = @$_GET['page'];
            if ($page) 
                $start = ($page - 1) * $limit;
            else
                $start = 0;

            $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE customer_email=? ORDER BY id DESC LIMIT $start, $limit");
            $statement->execute(array($_SESSION['customer']['cust_email']));
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
           
            if ($page == 0) $page = 1;
            $prev = $page - 1;
            $next = $page + 1;
            $lastpage = ceil($total_pages / $limit);
            $lpm1 = $lastpage - 1;   
            $pagination = "";
            if ($lastpage > 1) {   
                $pagination .= "<div class=\"pagination\">";
                if ($page > 1) 
                    $pagination .= "<a href=\"$targetpage?page=$prev\">&#171; previous</a>";
                else
                    $pagination .= "<span class=\"disabled\">&#171; previous</span>";    
                
                // Hiển thị số trang
                if ($lastpage < 7 + ($adjacents * 2)) {   
                    for ($counter = 1; $counter <= $lastpage; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<span class=\"current\">$counter</span>";
                        else
                            $pagination .= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
                    }
                } elseif ($lastpage > 5 + ($adjacents * 2)) {
                    if ($page < 1 + ($adjacents * 2)) {        
                        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                            if ($counter == $page)
                                $pagination .= "<span class=\"current\">$counter</span>";
                            else
                                $pagination .= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
                        }
                        $pagination .= "...";
                        $pagination .= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
                        $pagination .= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";       
                    } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                        $pagination .= "<a href=\"$targetpage?page=1\">1</a>";
                        $pagination .= "<a href=\"$targetpage?page=2\">2</a>";
                        $pagination .= "...";
                        for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                            if ($counter == $page)
                                $pagination .= "<span class=\"current\">$counter</span>";
                            else
                                $pagination .= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
                        }
                        $pagination .= "...";
                        $pagination .= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
                        $pagination .= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";       
                    } else {
                        $pagination .= "<a href=\"$targetpage?page=1\">1</a>";
                        $pagination .= "<a href=\"$targetpage?page=2\">2</a>";
                        $pagination .= "...";
                        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                            if ($counter == $page)
                                $pagination .= "<span class=\"current\">$counter</span>";
                            else
                                $pagination .= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
                        }
                    }
                }
                if ($page < $counter - 1) 
                    $pagination .= "<a href=\"$targetpage?page=$next\">next &#187;</a>";
                else
                    $pagination .= "<span class=\"disabled\">next &#187;</span>";
                $pagination .= "</div>\n";       
            } 
            // ===================== Pagination Code Ends ================== 
            ?>

            <?php
            $tip = $page * 10 - 10;
            foreach ($result as $row) {
                $tip++;
                ?>
                <tr>
                    <td><?php echo $tip; ?></td>
                    <td>
                        <?php
                        // Lấy thông tin đơn hàng
                        $orderStatement = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
                        $orderStatement->execute(array($row['payment_id']));
                        $orderResult = $orderStatement->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach ($orderResult as $order) {
                            echo 'Tên sản phẩm: ' . $order['product_name'];
                            echo '<br>Kích thước: ' . $order['size'];
                            echo '<br>Màu sắc: ' . $order['color'];
                            echo '<br>Số lượng: ' . $order['quantity'];
                            echo '<br>Giá đơn vị: ' . number_format($order['unit_price'], 0, ',', '.') . ' VNĐ';
                            echo '<br><br>';
                            
                            // Kiểm tra trạng thái giao hàng
                            if ($row['shipping_status'] === 'Pending') { // Chỉ hiển thị nút nếu trạng thái là 'Pending'
                                echo '<form method="post" action="cancel-order.php">';
                                echo '<input type="hidden" name="payment_id" value="' . $row['payment_id'] . '">';
                                echo '<input type="submit" value="Hủy đơn hàng" onclick="return confirm(\'Bạn có chắc chắn muốn hủy đơn hàng này?\');">';
                                echo '</form>';
                            } else {
                                echo 'Đơn hàng không thể hủy vì đã hoàn thành.<br>';
                            }
                        }
                        ?>
                    </td>
                    <td><?php echo $row['payment_date']; ?></td>
                    <td><?php echo $row['txnid']; ?></td>
                    <td><?php echo number_format($row['paid_amount'], 0, ',', '.'). ' VNĐ'; ?></td>
                    <td><?php echo $row['shipping_status']; ?></td>
                    <td><?php echo $row['payment_method']; ?></td>
                    <td><?php echo $row['payment_id']; ?></td>
                </tr>
                <?php
            } 
            ?>                               
                                
                            </tbody>
                        </table>
                        <div class="pagination" style="overflow: hidden;">
                        <?php 
                            echo $pagination; 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>