<?php require_once('header.php'); ?>

<?php
if (!isset($_REQUEST['id'])) {
    header('location: index.php');
    exit;
}

// Lấy chi tiết sản phẩm
$productId = $_REQUEST['id'];
$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
$statement->execute([$productId]);

if ($statement->rowCount() == 0) {
    header('location: index.php');
    exit;
}

$product = $statement->fetch(PDO::FETCH_ASSOC);
extract($product);

foreach($result as $row) {
    $p_name = $row['p_name'];
    $p_old_price = $row['p_old_price'];


    $discountPercent = isset($row['discount_percent']) ? $row['discount_percent'] : 0;


    $p_current_price = $row['p_current_price'];
    $p_qty = $row['p_qty'];
    $p_featured_photo = $row['p_featured_photo'];
    $p_description = $row['p_description'];
    $p_short_description = $row['p_short_description'];
    $p_feature = $row['p_feature'];
    $p_condition = $row['p_condition'];
    $p_return_policy = $row['p_return_policy'];
    $p_total_view = $row['p_total_view'];
    $p_is_featured = $row['p_is_featured'];
    $p_is_active = $row['p_is_active'];
    $ecat_id = $row['ecat_id'];
}


//wishlist

// Getting all categories name for breadcrumb
$statement = $pdo->prepare("SELECT
                        t1.ecat_id,
                        t1.ecat_name,
                        t1.mcat_id,

                        t2.mcat_id,
                        t2.mcat_name,
                        t2.tcat_id,

                        t3.tcat_id,
                        t3.tcat_name

                        FROM tbl_end_category t1
                        JOIN tbl_mid_category t2
                        ON t1.mcat_id = t2.mcat_id
                        JOIN tbl_top_category t3
                        ON t2.tcat_id = t3.tcat_id
                        WHERE t1.ecat_id=?");
$statement->execute(array($ecat_id));
$total = $statement->rowCount();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $ecat_name = $row['ecat_name'];
    $mcat_id = $row['mcat_id'];
    $mcat_name = $row['mcat_name'];
    $tcat_id = $row['tcat_id'];
    $tcat_name = $row['tcat_name'];
}

//cập nhật số lượt xem
$p_total_view = $p_total_view + 1;

$statement = $pdo->prepare("UPDATE tbl_product SET p_total_view=? WHERE p_id=?");
$statement->execute(array($p_total_view,$_REQUEST['id']));

//kích thước
$statement = $pdo->prepare("SELECT * FROM tbl_product_size WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $size[] = $row['size_id'];
}
//màu sắc
$statement = $pdo->prepare("SELECT * FROM tbl_product_color WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $color[] = $row['color_id'];
}


if(isset($_POST['form_review'])) {
    
    $statement = $pdo->prepare("SELECT * FROM tbl_rating WHERE p_id=? AND cust_id=?");
    $statement->execute(array($_REQUEST['id'],$_SESSION['customer']['cust_id']));
    $total = $statement->rowCount();
    
    if($total) {
        $error_message = LANG_VALUE_68; 
    } else {
        $statement = $pdo->prepare("INSERT INTO tbl_rating (p_id,cust_id,comment,rating) VALUES (?,?,?,?)");
        $statement->execute(array($_REQUEST['id'],$_SESSION['customer']['cust_id'],$_POST['comment'],$_POST['rating']));
        $success_message = LANG_VALUE_163;    
    }
    
}

// Getting the average rating for this product
$max_qty = isset($max_qty) ? $max_qty : 0;  // Gán mặc định là 0 nếu chưa được gán

$t_rating = 0;
$statement = $pdo->prepare("SELECT * FROM tbl_rating WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$tot_rating = $statement->rowCount();
if($tot_rating == 0) {
    $avg_rating = 0;
} else {
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
    foreach ($result as $row) {
        $t_rating = $t_rating + $row['rating'];
    }
    $avg_rating = $t_rating / $tot_rating;
}


// Thêm vào giỏ hàng
if (isset($_POST['form_add_to_cart'])) {
    // Lấy số lượng sản phẩm hiện tại từ cơ sở dữ liệu
    $statement = $pdo->prepare("SELECT p_qty FROM tbl_product WHERE p_id = ?");
    $statement->execute([$_REQUEST['id']]);
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    $current_p_qty = $row['p_qty'];


    
    // Kiểm tra nếu thiếu size hoặc color
    if (empty($_POST['size_id']) || empty($_POST['color_id'])) {
        echo "<script>alert('Vui lòng chọn kích thước và màu sắc trước khi thêm vào giỏ hàng.');</script>";
    } else {
    // Lấy số lượng người dùng nhập vào
    $input_qty = isset($_POST['p_qty']) ? (int)$_POST['p_qty'] : 0;

    // Kiểm tra số lượng sản phẩm
    if ($input_qty > $current_p_qty) {
        $temp_msg = 'Xin lỗi! Sản phẩm chỉ còn ' . $current_p_qty . ' sản phẩm';
        echo "<script type='text/javascript'>alert('$temp_msg');</script>";
    } else {
        // Nếu giỏ hàng đã tồn tại
        if (isset($_SESSION['cart_p_id'])) {
            $added = false;

            // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
            for ($i = 1; $i <= count($_SESSION['cart_p_id']); $i++) {
                if ($_SESSION['cart_p_id'][$i] == $_REQUEST['id'] && 
                    $_SESSION['cart_size_id'][$i] == $_POST['size_id'] && 
                    $_SESSION['cart_color_id'][$i] == $_POST['color_id']) {
                    $added = true;

                    break;

                }
            }

            if ($added) {
                $error_message1 = 'Sản phẩm đã có trong giỏ hàng.';
            } else {
                // Thêm sản phẩm mới vào giỏ hàng
                $new_key = count($_SESSION['cart_p_id']) + 1;

                // Lưu thông tin kích thước
                $size_id = $_POST['size_id'] ?? 0;
                $size_name = '';
                if ($size_id) {
                    $statement = $pdo->prepare("SELECT size_name FROM tbl_size WHERE size_id = ?");
                    $statement->execute([$size_id]);
                    $row = $statement->fetch(PDO::FETCH_ASSOC);
                    $size_name = $row['size_name'] ?? '';
                }

                // Lưu thông tin màu sắc
                $color_id = $_POST['color_id'] ?? 0;
                $color_name = '';
                if ($color_id) {
                    $statement = $pdo->prepare("SELECT color_name FROM tbl_color WHERE color_id = ?");
                    $statement->execute([$color_id]);
                    $row = $statement->fetch(PDO::FETCH_ASSOC);
                    $color_name = $row['color_name'] ?? '';
                }

                // Thêm thông tin sản phẩm vào giỏ hàng
                $_SESSION['cart_p_id'][$new_key] = $_REQUEST['id'];
                $_SESSION['cart_size_id'][$new_key] = $size_id;
                $_SESSION['cart_size_name'][$new_key] = $size_name;
                $_SESSION['cart_color_id'][$new_key] = $color_id;
                $_SESSION['cart_color_name'][$new_key] = $color_name;
                $_SESSION['cart_p_qty'][$new_key] = $input_qty;
                $_SESSION['cart_p_current_price'][$new_key] = $p_current_price;
                $_SESSION['cart_p_name'][$new_key] = $_POST['p_name'];
                $_SESSION['cart_p_featured_photo'][$new_key] = $_POST['p_featured_photo'];

                $success_message1 = 'Sản phẩm đã được thêm thành công!';
            }
        } else {
            // Nếu giỏ hàng chưa tồn tại, khởi tạo giỏ hàng
            $_SESSION['cart_p_id'][1] = $_REQUEST['id'];
            $_SESSION['cart_size_id'][1] = $_POST['size_id'] ?? 0;
            $_SESSION['cart_size_name'][1] = '';
            $_SESSION['cart_color_id'][1] = $_POST['color_id'] ?? 0;
            $_SESSION['cart_color_name'][1] = '';
            $_SESSION['cart_p_qty'][1] = $input_qty;
            $_SESSION['cart_p_current_price'][1] = $p_current_price;
            $_SESSION['cart_p_name'][1] = $_POST['p_name'];
            $_SESSION['cart_p_featured_photo'][1] = $_POST['p_featured_photo'];

            $success_message1 = 'Sản phẩm đã được thêm thành công!';
        }
    }
    }
}
?>


<?php
if ($success_message1 !== '') {
    echo "<script>
        alert('$success_message1');
        window.location.href = 'product.php?id=" . $_REQUEST['id'] . "';
    </script>";
    exit;
}

if ($error_message1 !== '') {
    echo "<script>alert('$error_message1');</script>";
}
?>




<div class="page">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
                

				<div class="product">
					<div class="row product-detail-container">
						<div class="col-md-6">
							<ul class="prod-slider">
                                
								<li style="background-image: url(assets/uploads/<?php echo $p_featured_photo; ?>);">
                                     <?php if ($discount_percent > 0): ?>
                                        <div style="
                                            position: absolute;
                                            top: 10px; /* Khoảng cách từ trên xuống */
                                            right: 10px; /* Khoảng cách từ bên phải */
                                            background-color: red; /* Màu nền */
                                            color: white; /* Màu chữ */
                                            padding: 5px 10px; /* Khoảng cách bên trong */
                                            border-radius: 5px; /* Bo góc */
                                            font-weight: bold; /* Đậm chữ */
                                            z-index: 10; /* Đảm bảo nó nằm trên cùng */
                                        ">


                                            <?php echo $discount_percent; ?>%
                                        </div>
                                    <?php endif; ?>
                                    <a class="popup" href="assets/uploads/<?php echo $p_featured_photo; ?>"></a>
								</li>
                                <?php
                                $statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=?");
                                $statement->execute(array($_REQUEST['id']));
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    ?>
                                    <li style="background-image: url(assets/uploads/product_photos/<?php echo $row['photo']; ?>);">
                                        <a class="popup" href="assets/uploads/product_photos/<?php echo $row['photo']; ?>"></a>
                                    </li>
                                    <?php
                                }
                                ?>
							</ul>
							<div id="prod-pager">
								<a data-slide-index="0" href="">
                                    <div class="prod-pager-thumb" style="background-image: url('assets/uploads/<?php echo $p_featured_photo; ?>');"></div></a>
                                <?php
                                $i=1;
                                $statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=?");
                                $statement->execute(array($_REQUEST['id']));
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    ?>
                                    <a data-slide-index="<?php echo $i; ?>" href="">
                                        <div class="prod-pager-thumb" style="background-image: url('assets/uploads/product_photos/<?php echo $row['photo']; ?>');"></div></a>
                                    <?php
                                    $i++;
                                }
                                ?>
							</div>

                           

						</div>
						<div class="col-md-6">
							<div class="p-title"><h2><?php echo $p_name; ?></h2></div>
							<div class="p-review">
								<div class="rating">
                                    <?php
                                    if($avg_rating == 0) {
                                        echo '';
                                    }
                                    elseif($avg_rating == 1.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    } 
                                    elseif($avg_rating == 2.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    }
                                    elseif($avg_rating == 3.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    }
                                    elseif($avg_rating == 4.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                        ';
                                    }
                                    else {
                                        for($i=1;$i<=5;$i++) {
                                            ?>
                                            <?php if($i>$avg_rating): ?>
                                                <i class="fa fa-star-o"></i>
                                            <?php else: ?>
                                                <i class="fa fa-star"></i>
                                            <?php endif; ?>
                                            <?php
                                        }
                                    }                                    
                                    ?>
                                </div>

                                <!-- THÊM PHẦN NÀY DƯỚI ĐÂY -->

                                <div class="stock-status" style="margin-top: 5px;">
                                    <?php if($p_qty == 0): ?>
                                        <span class="text-danger"><i class="fa fa-times-circle"></i> Hết hàng</span>
                                    <?php elseif($p_qty < 5): ?>
                                        <span class="text-warning"><i class="fa fa-exclamation-triangle"></i> Chỉ còn <?php echo $p_qty; ?> sản phẩm</span>
                                    <?php else: ?>
                                        <span class="text-success"><i class="fa fa-check-circle"></i> Còn hàng</span>
                                    <?php endif; ?>
                                </div>

                                <div class="product-views" style="margin-top:10px; font-size:14px; color:#777;">
    <i class="fa fa-eye"></i> <?php echo $p_total_view; ?> lượt xem
</div>


							</div>
							<div class="p-short-des">
								<p>
									<?php echo $p_short_description; ?>
								</p>
							</div>


                            <form action="" method="post">
    <div class="p-quantity">
        <div class="row">
            <?php if(isset($size) && !empty($size)): ?>
            <div class="col-md-12 mb_20">
    <?php echo 'Kích thước'; ?> <br>
    <select id="size_select" name="size_id" class="form-control" style="width:auto;">
        <option value="">Chọn kích thước</option>
        <?php
        $statement = $pdo->prepare("SELECT * FROM tbl_size");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            if(in_array($row['size_id'],$size)) {
                echo '<option value="' . $row['size_id'] . '">' . $row['size_name'] . '</option>';
            }
        }
        ?>
    </select>
     <br>
    <a href="size.html" id="size_guide_link" style="color: #000000; text-decoration: underline;">Hướng dẫn chọn size</a>
</div>

<!-- Modal Hướng Dẫn Chọn Size -->
<div id="sizeGuideModal" style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%); background:#fff; padding:20px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.5); z-index:9999; max-width:90%; max-height:80%; overflow:auto;">
    <div style="text-align:right;">
        <button onclick="closeSizeGuide()" style="background:none; border:none; font-size:20px;">&times;</button>
    </div>
    <div id="sizeGuideContent">
        <!-- Nội dung size.html sẽ được load vào đây -->
    </div>
</div>
<!-- Nền mờ -->
<!-- <div id="sizeGuideOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9998;" onclick="closeSizeGuide()"></div> -->



            <?php endif; ?>

            <?php if(isset($color) && !empty($color)): ?>
            <div class="col-md-12">
                <?php echo 'Màu sắc'; ?> <br>
                <select id="color_select" name="color_id" class="form-control" style="width:auto;">
                    <option value="">Chọn màu sắc</option>
                    <?php
                    $statement = $pdo->prepare("SELECT * FROM tbl_color");
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        if(in_array($row['color_id'],$color)) {
                            echo '<option value="' . $row['color_id'] . '">' . $row['color_name'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <?php endif; ?>
        </div>
    </div>


   


    <input type="hidden" name="p_name" value="<?php echo $p_name; ?>">
    <input type="hidden" name="p_featured_photo" value="<?php echo $p_featured_photo; ?>">

     <div class="p-quantity">
                                    <label>Số lượng</label><br>
                                    <input type="number" name="p_qty" min="1" max="<?php echo $p_qty; ?>" value="1" class="input-text qty">
                                </div>

    <div class="btn-cart btn-cart1">
        <input type="submit" value="<?php echo 'Thêm vào giỏ'; ?>" name="form_add_to_cart">

        <button class="add-to-wishlist" data-product-id="<?php echo $product['p_id']; ?>">
        ❤️ Thêm vào wishlist
    </button>
    </div>
</form>




						</div>
					</div>

                    <div class="p-price">
                                <span style="font-size: 20px;margin-right: 20px;">Giá:</span><br>
                                <?php if ($p_old_price > $p_current_price): ?>
                                    <span style="text-decoration: line-through; color: gray; font-size: 20px;">
                                        <?php echo number_format($p_old_price, 0, ',', '.') . ' vnđ'; ?>
                                    </span>
                                <?php endif; ?>
                                
                                <span><?php echo number_format($p_current_price, 0, ',', '.') . ' vnđ'; ?></span>
                            </div>

                    <div class="share">
                                <?php echo 'Chia sẻ sản phẩm'; ?> <br>
                                <div class="sharethis-inline-share-buttons"></div>
                            </div>

					
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Bootstrap Nav Tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="active">
        <a href="#description" role="tab" data-toggle="tab"><?php echo 'Mô tả'; // Mô tả ?></a>
    </li>
    <li>
        <a href="#feature" role="tab" data-toggle="tab"><?php echo 'Tính năng'; // Tính năng ?></a>
    </li>
    <li>
        <a href="#condition" role="tab" data-toggle="tab"><?php echo 'Tình trạng'; // Tình trạng ?></a>
    </li>
    <li>
        <a href="#return_policy" role="tab" data-toggle="tab"><?php echo 'Chính sách trả hàng'; // Chính sách trả hàng ?></a>
    </li>
</ul>

<!-- Bootstrap Tab Panes -->
<div class="tab-content" style="padding-top: 15px;">
    <div role="tabpanel" class="tab-pane active" id="description">
        <p>
            <?php echo $p_description != '' ? $p_description : 'Mô tả'; ?>
        </p>
    </div>
    <div role="tabpanel" class="tab-pane" id="feature">
        <p>
            <?php echo $p_feature != '' ? $p_feature : 'Tính năng'; ?>
        </p>
    </div>
    <div role="tabpanel" class="tab-pane" id="condition">
        <p>
            <?php echo $p_condition != '' ? $p_condition : 'Điều kiện'; ?>
        </p>
    </div>
    <div role="tabpanel" class="tab-pane" id="return_policy">
        <p>
            <?php echo $p_return_policy != '' ? $p_return_policy : 'Chính sách đổi trả'; ?>
        </p>
    </div>
</div>







                                    <div class="review-form">
                                        <?php
                                        $statement = $pdo->prepare("SELECT * 
                                                            FROM tbl_rating t1 
                                                            JOIN tbl_customer t2 
                                                            ON t1.cust_id = t2.cust_id 
                                                            WHERE t1.p_id=?");
                                        $statement->execute(array($_REQUEST['id']));
                                        $total = $statement->rowCount();
                                        ?>
                                        <h2><?php echo 'Đánh giá'; ?> (<?php echo $total; ?>)</h2>
                                        <?php
                                        if($total) {
                                            $j=0;
                                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($result as $row) {
                                                $j++;
                                                ?>
                                                <div class="mb_10"><b><u><?php echo 'Đánh giá: '; ?> <?php echo $j; ?></u></b></div>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th style="width:170px;"><?php echo 'Tên '; ?></th>
                                                        <td><?php echo $row['cust_name']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo 'Lời góp ý của bạn'; ?></th>
                                                        <td><?php echo $row['comment']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo 'Số sao yêu thương của bạn'; ?></th>
                                                        <td>
                                                            <div class="rating">
                                                                <?php
                                                                for($i=1;$i<=5;$i++) {
                                                                    ?>
                                                                    <?php if($i>$row['rating']): ?>
                                                                        <i class="fa fa-star-o"></i>
                                                                    <?php else: ?>
                                                                        <i class="fa fa-star"></i>
                                                                    <?php endif; ?>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <?php
                                            }
                                        } else {
                                            echo 'Viết đánh giá';
                                        }
                                        ?>
                                        
                                        <h2><?php echo 'Viết đánh giá: '; ?></h2>
                                        <?php
if ($success_message1 != '') {
    echo "<script>alert('".$success_message1."');</script>";
}
if ($error_message1 != '') {
    echo "<script>alert('".$error_message1."');</script>";
}
?>
                                        <?php if(isset($_SESSION['customer'])): ?>

                                            <?php
                                            $statement = $pdo->prepare("SELECT * 
                                                                FROM tbl_rating
                                                                WHERE p_id=? AND cust_id=?");
                                            $statement->execute(array($_REQUEST['id'],$_SESSION['customer']['cust_id']));
                                            $total = $statement->rowCount();
                                            ?>
                                            <?php if($total==0): ?>
                                            <form action="" method="post">
                                            <div class="rating-section">
                                                <input type="radio" name="rating" class="rating" value="1" checked>
                                                <input type="radio" name="rating" class="rating" value="2" checked>
                                                <input type="radio" name="rating" class="rating" value="3" checked>
                                                <input type="radio" name="rating" class="rating" value="4" checked>
                                                <input type="radio" name="rating" class="rating" value="5" checked>
                                            </div>                                            
                                            <div class="form-group">
                                                <textarea name="comment" class="form-control" cols="30" rows="10" placeholder="Viết bình luận của bạn (optional)" style="height:100px;"></textarea>
                                            </div>
                                            <input type="submit" class="btn btn-default" name="form_review" value="<?php echo 'Đánh giá'; ?>">
                                            </form>
                                            <?php else: ?>
                                                <span style="color:red;"><?php echo 'Bạn đã đánh giá rồi, cảm ơn bạn!'; ?></span>
                                            <?php endif; ?>


                                        <?php else: ?>
                                            <p class="error">
												<?php echo LANG_VALUE_69; ?> <br>
												<a href="login.php" style="color:red;text-decoration: underline;"><?php echo LANG_VALUE_9; ?></a>
											</p>
                                        <?php endif; ?>                         
                                    </div>

								</div>
							</div>
						</div>
					</div>

				</div>

			</div>
		</div>
	</div>
</div>

<div class="product bg-gray pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="headline">
                    <h2><?php echo 'Sản phẩm liên quan'; ?></h2>
                    <h3><?php echo 'Xem tất cả sản phẩm liên quan bên dưới'; ?></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="product-carousel">

                    <?php
                    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE ecat_id=? AND p_id!=?");
                    $statement->execute(array($ecat_id,$_REQUEST['id']));
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        ?>
                        <div class="item">
                            <div class="thumb">
                                <div class="photo" style="background-image:url(assets/uploads/<?php echo $row['p_featured_photo']; ?>);"></div>
                                <div class="overlay"></div>
                            </div>
                            <div class="text">
                                <h3><a href="product.php?id=<?php echo $row['p_id']; ?>"><?php echo $row['p_name']; ?></a></h3>
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
                                    $statement1->execute(array($row['p_id']));
                                    $tot_rating = $statement1->rowCount();
                                    if($tot_rating == 0) {
                                        $avg_rating = 0;
                                    } else {
                                        $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result1 as $row1) {
                                            $t_rating = $t_rating + $row1['rating'];
                                        }
                                        $avg_rating = $t_rating / $tot_rating;
                                    }
                                    ?>
                                    <?php
                                    if($avg_rating == 0) {
                                        echo '';
                                    }
                                    elseif($avg_rating == 1.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    } 
                                    elseif($avg_rating == 2.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    }
                                    elseif($avg_rating == 3.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    }
                                    elseif($avg_rating == 4.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                        ';
                                    }
                                    else {
                                        for($i=1;$i<=5;$i++) {
                                            ?>
                                            <?php if($i>$avg_rating): ?>
                                                <i class="fa fa-star-o"></i>
                                            <?php else: ?>
                                                <i class="fa fa-star"></i>
                                            <?php endif; ?>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <p><a href="product.php?id=<?php echo $row['p_id']; ?>"><?php echo LANG_VALUE_154; ?></a></p>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                </div>

            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.add-to-wishlist').on('click', function(e) {
        e.preventDefault();

        var productId = $(this).data('product-id');

        $.ajax({
            type: 'POST',
            url: 'add_wishlist.php', // Đường dẫn đến file xử lý
            data: { product_id: productId },
            success: function(response) {
                var data = JSON.parse(response);
                alert(data.message);
            },
            error: function() {
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            }
        });
    });
});
</script>

<?php require_once('footer.php'); ?>
<script src="js/rating.js"></script>
