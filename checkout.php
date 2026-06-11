<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_checkout = $row['banner_checkout'];
}
?>

<?php
if(!isset($_SESSION['cart_p_id'])) {
    header('location: cart.php');
    exit;
}
?>

<div class="page-banner" style="background-image: url(assets/uploads/<?php echo $banner_checkout; ?>)">
    <div class="overlay"></div>
    <div class="page-banner-inner">
        <h1><?php echo 'Hóa đơn'; ?></h1>
    </div>
</div>

<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                
                <?php if(!isset($_SESSION['customer'])): ?>
                    <p>
                        <a href="login.php" class="btn btn-md btn-danger"><?php echo 'Hãy đăng nhập hoặc đăng ký trước khi tiến tới thanh toán'; ?></a>
                    </p>
                <?php else: ?>

                <h3 class="special"><?php echo 'Chi tiết hóa đơn'; ?></h3>
                <div class="cart">
                    <table class="table table-responsive table-hover table-bordered">
                        <tr>
                            <th><?php echo '#' ?></th>
                            <th><?php echo 'Ảnh' ?></th>
                            <th><?php echo 'Tên sản phẩm'; ?></th>
                            <th><?php echo 'Kích thước'; ?></th>
                            <th><?php echo 'Màu sắc'; ?></th>
                            <th><?php echo 'Giá'; ?></th>
                            <th><?php echo 'Số lượng'; ?></th>
                            <th><?php echo 'Tổng'; ?></th>
                        </tr>
                         <?php
                        $table_total_price = 0;

                        $i=0;
                        foreach($_SESSION['cart_p_id'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_id[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_size_id'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_size_id[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_size_name'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_size_name[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_color_id'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_color_id[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_color_name'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_color_name[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_p_qty'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_qty[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_p_current_price'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_current_price[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_p_name'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_name[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_p_featured_photo'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_featured_photo[$i] = $value;
                        }
                        ?>
                        <?php for($i=1;$i<=count($arr_cart_p_id);$i++): ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td>
                                <img src="assets/uploads/<?php echo $arr_cart_p_featured_photo[$i]; ?>" alt="">
                            </td>
                            <td><?php echo $arr_cart_p_name[$i]; ?></td>
                            <td><?php echo $arr_cart_size_name[$i]; ?></td>
                            <td><?php echo $arr_cart_color_name[$i]; ?></td>
                            <td><?php echo number_format($arr_cart_p_current_price[$i], 0, ',', '.'); ?> VNĐ</td>
                            <td><?php echo $arr_cart_p_qty[$i]; ?></td>

                            <td class="text-right">
                                <?php
                                $row_total_price = $arr_cart_p_current_price[$i]*$arr_cart_p_qty[$i];
                                $table_total_price += $row_total_price;
                                ?>
                                <?php echo number_format($row_total_price, 0, ',', '.'); ?> VNĐ
                            </td>
                        </tr>
                        <?php endfor; ?>           
                        <tr>
                            <th colspan="6" class="total-text"><?php echo 'Cộng tổng'; ?></th>
                            <th class="total-amount"><?php echo number_format($table_total_price, 0, ',', '.'); ?> VNĐ</th>
                        </tr>
                        <?php
                        $statement = $pdo->prepare("SELECT * FROM tbl_shipping_cost WHERE country_id=?");
                        $statement->execute(array($_SESSION['customer']['cust_country']));
                        $total = $statement->rowCount();
                        if($total) {
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                $shipping_cost = $row['amount'];
                            }
                        } else {
                            $statement = $pdo->prepare("SELECT * FROM tbl_shipping_cost_all WHERE sca_id=1");
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                $shipping_cost = $row['amount'];
                            }
                        }                        
                        ?>
                        <tr>
                            <td colspan="6" class="total-text"><?php echo 'Phí ship'; ?></td>
                            <td class="total-amount"><?php echo number_format($shipping_cost, 0, ',', '.'); ?> VNĐ</td>
                        </tr>
                        <tr>
                            <th colspan="6" class="total-text"><?php echo 'Tổng hóa đơn: '; ?></th>
                            <th class="total-amount">
                                <?php
                                $final_total = $table_total_price + $shipping_cost;
                                ?>
                                <?php echo number_format($final_total, 0, ',', '.'); ?> VNĐ
                            </th>
                        </tr>
                    </table> 
                </div>

                <div class="billing-address">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="special"><?php echo 'Địa chỉ thanh toán'; ?></h3>
                            <table class="table table-responsive table-bordered table-hover table-striped bill-address">
                                <tr>
                                    <td><?php echo 'Tên'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_b_name']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Công ty'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_b_cname']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Số điện thoại'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_b_phone']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Quốc gia'; ?></td>
                                    <td>
                                        <?php
                                        $statement = $pdo->prepare("SELECT * FROM tbl_country WHERE country_id=?");
                                        $statement->execute(array($_SESSION['customer']['cust_b_country']));
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result as $row) {
                                            echo $row['country_name'];
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Địa chỉ'; ?></td>
                                    <td><?php echo nl2br($_SESSION['customer']['cust_b_address']); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Thành phố'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_b_city']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Tỉnh'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_b_state']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Mã bưu điện'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_b_zip']; ?></td>
                                </tr>                                
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h3 class="special"><?php echo 'Địa chỉ nhận hàng'; ?></h3>
                            <table class="table table-responsive table-bordered table-hover table-striped bill-address">
                                <tr>
                                    <td><?php echo 'Tên'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_s_name']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Công ty'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_s_cname']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Số điện thoại'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_s_phone']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Quốc gia'; ?></td>
                                    <td>
                                        <?php
                                        $statement = $pdo->prepare("SELECT * FROM tbl_country WHERE country_id=?");
                                        $statement->execute(array($_SESSION['customer']['cust_s_country']));
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result as $row) {
                                            echo $row['country_name'];
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Địa chỉ'; ?></td>
                                    <td><?php echo nl2br($_SESSION['customer']['cust_s_address']); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Thành phố'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_s_city']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Tỉnh'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_s_state']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Mã bưu điện'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_s_zip']; ?></td>
                                </tr> 
                            </table>
                        </div>
                    </div>                    
                </div>

                <div class="cart-buttons">
                    <ul>
                        <li><a href="cart.php" class="btn btn-primary"><?php echo 'Quay lại giỏ hàng'; ?></a></li>
                    </ul>
                </div>

                <div class="clear"></div>
                <h3 class="special"><?php echo 'Phương thức thanh toán'; ?></h3>
                <div class="row">
                    <?php
                    $checkout_access = 1;
                    if (
                        empty($_SESSION['customer']['cust_b_name']) ||
                        empty($_SESSION['customer']['cust_b_cname']) ||
                        empty($_SESSION['customer']['cust_b_phone']) ||
                        empty($_SESSION['customer']['cust_b_country']) ||
                        empty($_SESSION['customer']['cust_b_address']) ||
                        empty($_SESSION['customer']['cust_b_city']) ||
                        empty($_SESSION['customer']['cust_b_state']) ||
                        empty($_SESSION['customer']['cust_b_zip']) ||
                        empty($_SESSION['customer']['cust_s_name']) ||
                        empty($_SESSION['customer']['cust_s_cname']) ||
                        empty($_SESSION['customer']['cust_s_phone']) ||
                        empty($_SESSION['customer']['cust_s_country']) ||
                        empty($_SESSION['customer']['cust_s_address']) ||
                        empty($_SESSION['customer']['cust_s_city']) ||
                        empty($_SESSION['customer']['cust_s_state']) ||
                        empty($_SESSION['customer']['cust_s_zip'])
                    ) {
                        $checkout_access = 0;
                    }
                    ?>
                    <?php if ($checkout_access == 0): ?>
                        <div class="col-md-12">
                            <div style="color:red;font-size:22px;margin-bottom:50px;">
                                Bạn cần điền đầy đủ thông tin thanh toán và giao hàng để hoàn tất đơn hàng. Vui lòng điền thông tin <a href="customer-billing-shipping-update.php" style="color:red;text-decoration:underline;">tại đây</a>.
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for=""><?php echo 'Phương thức thanh toán'; ?> *</label>
                                    <select name="payment_method" class="form-control select2" id="advFieldsStatus">
                                        <option value=""><?php echo 'Chọn phương thức thanh toán'; ?></option>
                                        <option value="COD"><?php echo 'Thanh toán khi nhận hàng'; ?></option>
                                        <option value="PayPal"><?php echo 'Paypal'; ?></option>
                                        <option value="Bank Deposit"><?php echo 'Chuyển khoản'; ?></option>
                                    </select>
                                </div>

                                    
                            
                                <!-- Thanh toán khi nhận hàng -->
                                <form action="payment/tienmat/cod.php" method="post" id="cash_on_delivery_form">
    <input type="hidden" name="final_total" value="<?php echo $final_total; ?>">
    <input type="hidden" name="payment_method" value="COD"> <!-- ✅ Thêm dòng này -->
    <input type="submit" class="btn btn-primary" value="Hoàn tất đơn hàng" name="form1">
</form>

        

                                <form class="paypal" action="<?php echo BASE_URL; ?>payment/paypal/payment_process.php" method="post" id="paypal_form" target="_blank">
                                    <input type="hidden" name="cmd" value="_xclick" />
                                    <input type="hidden" name="no_note" value="1" />
                                    <input type="hidden" name="lc" value="UK" />
                                    <input type="hidden" name="currency_code" value="USD" />
                                    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
                                    <input type="hidden" name="final_total" value="<?php echo $final_total; ?>">
                                    <div class="col-md-12 form-group">
                                        <input type="submit" class="btn btn-primary" value="<?php echo 'Hoàn tất thanh toán qua Paypal'; ?>" name="form1">
                                    </div>
                                </form>

                                <form action="payment/bank/init.php" method="post" id="bank_form">
                                    <input type="hidden" name="amount" value="<?php echo $final_total; ?>">
                                    <div class="col-md-12 form-group">
                                        <label for=""><?php echo 'Thông tin chuyển khoản'; ?></label><br>
                                        <?php
                                        $statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
                                        $statement->execute();
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result as $row) {
                                            echo nl2br($row['bank_detail']);
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label for=""><?php echo 'Thông tin giao dịch'; ?> <br><span style="font-size:12px;font-weight:normal;">(<?php echo 'Vui lòng ghi rõ thông tin'; ?>)</span></label>
                                        <textarea name="transaction_info" class="form-control" cols="30" rows="10"></textarea>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <input type="submit" class="btn btn-primary" value="<?php echo 'Hoàn tất chuyển khoản'; ?>" name="form3">
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>