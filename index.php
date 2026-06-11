<?php require_once('header.php'); ?>

<?php
$p_total_view = isset($row['p_total_view']) ? $row['p_total_view'] : 0;
$p_total_view += 1; // Tăng giá trị lên 1

if (isset($_REQUEST['id'])) {
    $statement = $pdo->prepare("UPDATE tbl_product SET p_total_view=? WHERE p_id=?");
    $statement->execute(array($p_total_view, $_REQUEST['id']));
}
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row)
{
    $cta_title = $row['cta_title'];
    $cta_content = $row['cta_content'];
    $cta_read_more_text = $row['cta_read_more_text'];
    $cta_read_more_url = $row['cta_read_more_url'];
    $cta_photo = $row['cta_photo'];
    $featured_product_title = $row['featured_product_title'];
    $featured_product_subtitle = $row['featured_product_subtitle'];
    $latest_product_title = $row['latest_product_title'];
    $latest_product_subtitle = $row['latest_product_subtitle'];
    $popular_product_title = $row['popular_product_title'];
    $popular_product_subtitle = $row['popular_product_subtitle'];
    $total_featured_product_home = $row['total_featured_product_home'];
    $total_latest_product_home = $row['total_latest_product_home'];
    $total_popular_product_home = $row['total_popular_product_home'];
    $home_service_on_off = $row['home_service_on_off'];
    $home_welcome_on_off = $row['home_welcome_on_off'];
    $home_featured_product_on_off = $row['home_featured_product_on_off'];
    $home_latest_product_on_off = $row['home_latest_product_on_off'];
    $home_popular_product_on_off = $row['home_popular_product_on_off'];

}

?>

<div id="bootstrap-touch-slider" class="carousel bs-slider fade control-round indicators-line" data-ride="carousel" data-pause="hover" data-interval="4000">

    <div class="carousel-inner" role="listbox">
        <?php
        $slides = [
            [
                'image' => 'admin/img/15.png',
                'src' => 'product-category.php?id=2&type=top-category',
                'button_text' => 'Xem sản phẩm',
                'position' => 'Left'
            ],
            [
                'image' => 'admin/img/banner1.png',
                'src' => 'product-category.php?id=1&type=top-category',
                'button_text' => 'Khám phá',
                'position' => 'Center'
            ],
            [
                'image' => 'admin/img/banner1.jpg',
                'src' => 'product-category.php?id=3&type=top-category',
                'button_text' => 'Mua ngay',
                'position' => 'Right'
            ]
        ];

        foreach ($slides as $i => $row): ?>
            <div class="item <?php echo ($i == 0) ? 'active' : ''; ?>" style="background-image: url('<?php echo $row['image']; ?>'); border-radius: 0px;">
                <div class="bs-slider-overlay"></div>
                <div class="container">
                    <div class="row">
                        <div class="slide-text <?php echo 'slide_style_' . strtolower($row['position']); ?>">
                            <a href="<?php echo $row['src']; ?>" class="btn btn-primary" data-animation="animated 
                                <?php echo ($row['position'] == 'Left') ? 'fadeInLeft' : (($row['position'] == 'Center') ? 'fadeInDown' : 'fadeInRight'); ?>">
                                <?php echo $row['button_text']; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <ol class="carousel-indicators">
        <?php for ($j = 0; $j < count($slides); $j++): ?>
            <li data-target="#bootstrap-touch-slider" data-slide-to="<?php echo $j; ?>" class="<?php echo ($j == 0 ? 'active' : ''); ?>"></li>
        <?php endfor; ?>
    </ol>

    <a class="left carousel-control" href="#bootstrap-touch-slider" role="button" data-slide="prev">
        <span class="fa fa-angle-left" aria-hidden="true"></span>
        <span class="sr-only">Quay lại</span>
    </a>
    <a class="right carousel-control" href="#bootstrap-touch-slider" role="button" data-slide="next">
        <span class="fa fa-angle-right" aria-hidden="true"></span>
        <span class="sr-only">Tiếp</span>
    </a>
</div>



<div class="banner-title">THỜI TRANG KẾT HỢP CÔNG NGHỆ</div>
<div class="banner">
    <div class="banner-item">
        <img src="admin/img/c0.png" alt="Công Nghệ Cafe Tái Sinh">
    </div>
    <div class="banner-item">
        <img src="admin/img/c1.png" alt="Công Nghệ Cool Touch">
    </div>
    <div class="banner-item">
        <img src="admin/img/c2.png" alt="Jeans Coolmax Thoáng Khí">
    </div>
    <div class="banner-item">
        <img src="admin/img/c3.png" alt="Smart Cool">
    </div>
</div>

    
       <!--  <img src="admin/img/banner1.png" style="max-width: 100%; height: 500px; font-weight: 890.17px; border-radius: 10px; margin-bottom: 20px; margin-top: 40px;">
        <img src="admin/img/banner1.jpg" style="max-width: 100%; height: 500px; font-weight: 890.17px; border-radius: 10px; margin: 20px;"> -->

   


<!-- bắt đầu service section -->
    <div class="sevice-title">TIỆN ÍCH</div>
    <div class="service">
        <div class="box-container">
        <!-- service item box -->
            <div class="box">
                <div class="icon">
                    <div class="icon-box">
                        <img src="admin/img/services.png" class="img1">
                        <!-- <img src="image/services (1).png" class="img2"> -->
                    </div>
                </div>
                <div class="detail">
                    <h4>Vận chuyển</h4>
                    <span>100% an toàn</span>
                </div>
            </div>
        <!--  -->
            <div class="box">
                <div class="icon">
                    <div class="icon-box">
                        <img src="admin/img/services (2).png" class="img1">
                        <!-- <img src="image/services (3).png" class="img2"> -->
                    </div>
                </div>
                <div class="detail">
                    <h4>Hỗ trợ tư vấn</h4>
                    <span>24/7</span>
                </div>
            </div>
        <!--  -->
            <div class="box">
                <div class="icon">
                    <div class="icon-box">
                        <img src="admin/img/services (5).png" class="img1">
                        <!-- <img src="image/services (6).png" class="img2"> -->
                    </div>
                </div>
                <div class="detail">
                    <h4>Thanh toán</h4>
                    <span>Tiện lợi</span>
                </div>
            </div>
        <!--  -->
            <div class="box">
                <div class="icon">
                    <div class="icon-box">
                        <img src="admin/img/service.png" class="img1">
                        <!-- <img src="image/service (01).png" class="img2"> -->
                    </div>
                </div>
                <div class="detail">
                    <h4>Đổi trả</h4>
                    <span>Miễn phí</span>
                </div>
            </div>
        <!--  -->
            <div class="box">
                <div class="icon">
                    <div class="icon-box">
                        <img src="admin/img/services (7).png" class="img1">
                        <!-- <img src="image/services (8).png" class="img2"> -->
                    </div>
                </div>
                <div class="detail">
                    <h4>Quà tặng</h4>
                    <span>Hấp dẫn</span>
                </div>
            </div>
        <!--  -->
            <div class="box">
                <div class="icon">
                    <div class="icon-box">
                        <img src="admin/img/sevice(6).png" class="img1">
                        <!-- <img src="image/services (1).png" class="img2"> -->
                    </div>
                </div>
                <div class="detail">
                    <h4>Chất liệu</h4>
                    <span>Thoải mái</span>
                </div>
            </div>

        </div>
    </div>


 <video autoplay loop muted controls 
  style="    width: 100%;
    max-width: 100%;
    height: 360px;
    overflow: hidden;
    display: block;
    object-fit: cover;
    margin: 40px;
    margin-top: 0px;
    padding-right: 80px;">
  <source src="admin/img/video.mp4" type="video/mp4">
</video>





<?php if($home_featured_product_on_off == 1): ?>
<div class="product pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="headline">
                    <h2><?php echo $featured_product_title; ?></h2>
                    <h3><?php echo $featured_product_subtitle; ?></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="product-carousel">
                    
                    <?php
                    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_is_featured=? AND p_is_active=? LIMIT ".$total_featured_product_home);
                    $statement->execute(array(1,1));
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
                    foreach ($result as $row) {
                        ?>
                        <div class="item">
                            <div class="thumb">

                                <div class="photo" style="background-image:url(assets/uploads/<?php echo $row['p_featured_photo']; ?>);">

                                 

                                 <div class="item">
    <div class="thumb" style="position: relative;">
        <div class="photo" style="background-image:url(assets/uploads/<?php echo $row['p_featured_photo']; ?>);">
            <?php if ($row['discount_percent'] > 0): ?>
                 <div class="product-views" style="position:absolute; left:10px; background-color: rgba(255,255,255,0.8); padding: 5px 10px; border-radius: 5px; font-size:12px;">
                                <i class="fa fa-eye"></i> <?php echo $row['p_total_view']; ?> lượt xem
                            </div>
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


                    <?php echo $row['discount_percent']; ?>%
                </div>
            <?php endif; ?>
        </div>
        <div class="overlay"></div>
    </div>
    <div class="text">
        <h3><a href="product.php?id=<?php echo $row['p_id']; ?>"><?php echo $row['p_name']; ?></a></h3>
        <h4>
            <?php if($row['p_old_price'] != ''): ?>
                <del><?php echo number_format($row['p_old_price'], 0, ',', '.') . ' vnđ'; ?></del>
            <?php endif; ?>
            <?php echo number_format($row['p_current_price'], 0, ',', '.') . ' vnđ'; ?>
        </h4>
        <div class="rating">
            <?php
            // Đánh giá mã ở đây
            ?>
        </div>
        <?php if($row['p_qty'] == 0): ?>
            <div class="out-of-stock">
                <div class="inner">Hết hàng</div>
            </div>
        <?php else: ?>
            <p><a href="product.php?id=<?php echo $row['p_id']; ?>"><i class="fa fa-shopping-cart"></i> Thêm vào giỏ </a></p>
        <?php endif; ?>
    </div>
</div>   
                                </div>
                                <div class="overlay"></div>

                            </div>
                            <div class="text">
                                <h3><a href="product.php?id=<?php echo $row['p_id']; ?>"><?php echo $row['p_name']; ?></a></h3>
                                <h4>
                                <?php
                                    // 1) Chuyển giá gốc về VNĐ
                                    $oldPriceVnd       = $row['p_old_price'] ;
                                    $discountPercent   = $row['discount_percent'];
                                    // 2) Tính giá đã giảm
                                    $currentPriceVnd   = round($oldPriceVnd * (100 - $discountPercent) / 100);

                                    // 3) Hiển thị
                                    if ($discountPercent > 0) {
                                        // Giá gốc gạch ngang
                                        echo '<del>' 
                                             . number_format($oldPriceVnd, 0, ',', '.') 
                                             . ' vnđ</del> ';
                                    }
                                    // Giá sau giảm
                                    echo number_format($currentPriceVnd, 0, ',', '.') . ' vnđ';
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

                                <?php if($row['p_qty'] == 0): ?>
                                    <div class="out-of-stock">
                                        <div class="inner">
                                            Hết hàng
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p><a href="product.php?id=<?php echo $row['p_id']; ?>"><i class="fa fa-shopping-cart"></i> Thêm vào giỏ </a></p>
                                <?php endif; ?>
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
<?php endif; ?>



<img src="admin/img/b5.png" style="max-width: 100%; height: 100%; border-radius: 10px; margin-bottom: 40px; margin-top: 40px; margin-left: 30px;">
<?php if($home_latest_product_on_off == 1): ?>
<div class="product bg-gray pt_70 pb_30">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="headline">
                    <h2><?php echo $latest_product_title; ?></h2>
                    <h3><?php echo $latest_product_subtitle; ?></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="product-carousel">

                    <?php
                    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_is_active=? ORDER BY p_id DESC LIMIT ".$total_latest_product_home);
                    $statement->execute(array(1));
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
                    foreach ($result as $row) {
                        ?>
                        <div class="item">
                            <div class="thumb">
                                <div class="photo" style="background-image:url(assets/uploads/<?php echo $row['p_featured_photo']; ?>);">
                                    <?php if ($row['discount_percent'] > 0): ?>
                                         <div class="product-views" style="position:absolute; left:10px; background-color: rgba(255,255,255,0.8); padding: 5px 10px; border-radius: 5px; font-size:12px;">
                                <i class="fa fa-eye"></i> <?php echo $row['p_total_view']; ?> lượt xem
                            </div>
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


                                            <?php echo $row['discount_percent']; ?>%
                                        </div>
                                    <?php endif; ?>
                                </div>
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
                                    $currentPriceVnd   = round($oldPriceVnd * (100 - $discountPercent) / 100);

                                    // 3) Hiển thị
                                    if ($discountPercent > 0) {
                                        // Giá gốc gạch ngang
                                        echo '<del>' 
                                             . number_format($oldPriceVnd, 0, ',', '.') 
                                             . ' vnđ</del> ';
                                    }
                                    // Giá sau giảm
                                    echo number_format($currentPriceVnd, 0, ',', '.') . ' vnđ';
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
                                <?php if($row['p_qty'] == 0): ?>
                                    <div class="out-of-stock">
                                        <div class="inner">
                                            Hết hàng
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p><a href="product.php?id=<?php echo $row['p_id']; ?>"><i class="fa fa-shopping-cart"></i> Thêm vào giỏ</a></p>
                                <?php endif; ?>
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
<?php endif; ?>


<img src="admin/img/b6.png" style="max-width: 100%; height: 100%; border-radius: 10px; margin-bottom: 40px; margin-top: 40px; margin-left: 30px;">

<?php if($home_popular_product_on_off == 1): ?>
<div class="product pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="headline">
                    <h2><?php echo $popular_product_title; ?></h2>
                    <h3><?php echo $popular_product_subtitle; ?></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="product-carousel">

                    <?php
                    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_is_active=? ORDER BY p_total_view DESC LIMIT ".$total_popular_product_home);
                    $statement->execute(array(1));
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
                    foreach ($result as $row) {
                        ?>
                        <div class="item">
                            <div class="thumb">
                                <div class="photo" style="background-image:url(assets/uploads/<?php echo $row['p_featured_photo']; ?>);">
                                    <?php if ($row['discount_percent'] > 0): ?>
                                         <div class="product-views" style="position:absolute; left:10px; background-color: rgba(255,255,255,0.8); padding: 5px 10px; border-radius: 5px; font-size:12px;">
                                <i class="fa fa-eye"></i> <?php echo $row['p_total_view']; ?> lượt xem
                            </div>
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


                                            <?php echo $row['discount_percent']; ?>%
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="overlay"></div>
                            </div>
                            <div class="text">
                                <h3><a href="product.php?id=<?php echo $row['p_id']; ?>"><?php echo $row['p_name']; ?></a></h3>
                                <h4>
                                <?php
                                    // 1) Chuyển giá gốc về VNĐ
                                    $oldPriceVnd       = $row['p_old_price'] ;
                                    $discountPercent   = $row['discount_percent'];
                                    // 2) Tính giá đã giảm
                                    $currentPriceVnd   = round($oldPriceVnd * (100 - $discountPercent) / 100);

                                    // 3) Hiển thị
                                    if ($discountPercent > 0) {
                                        // Giá gốc gạch ngang
                                        echo '<del>' 
                                             . number_format($oldPriceVnd, 0, ',', '.') 
                                             . ' vnđ</del> ';
                                    }
                                    // Giá sau giảm
                                    echo number_format($currentPriceVnd, 0, ',', '.') . ' vnđ';
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
                                <?php if($row['p_qty'] == 0): ?>
                                    <div class="out-of-stock">
                                        <div class="inner">
                                            Hết hàng
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p><a href="product.php?id=<?php echo $row['p_id']; ?>"><i class="fa fa-shopping-cart"></i>Thêm vào giỏ</a></p>
                                <?php endif; ?>
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
<?php endif; ?>

<div class="ice-container">
        <div class="overlay"></div>
        <div class="detail">
            <h1>LOFI CHIC | NEW COLLECTION</h1>
            <p>💕 Thời trang không đơn thuần chỉ là những bộ trang phục, mà là cách chúng ta kể câu chuyện của chính mình thông qua ngôn ngữ giao tiếp không lời. Được thiết kế dành riêng cho những tâm hồn duy mỹ và yêu cái đẹp, 𝐋𝐎𝐅𝐈 𝐂𝐇𝐈𝐂 là hành trình khám phá, theo đuổi và khẳng định bản sắc cá nhân của những quý cô hiện đại thông qua thời trang. Ngoài những gam màu basic nhưng mang sự sang trọng vượt thời gian như trắng và đen, điểm nhấn của bộ sưu tập này nằm ở những gam màu xu hướng được thêm vào như một nét chấm phá, làm bừng sáng tủ đồ Xuân Hè của nàng như: Crisp Blue dịu mát, Moody Plum quyến rũ hay màu Mocha ấm áp. Bên cạnh đó, Xuân Hè năm nay cũng là mùa lên ngôi của những chi tiết cách điệu như cà vạt, sọc kẻ hay những chiếc áo kiểu tay bồng, mang lại không chỉ là nét thanh lịch mà còn là sự sáng tạo, linh hoạt biến hóa giữa các hoàn cảnh khác nhau. Ưu điểm của 𝐋𝐎𝐅𝐈 𝐂𝐇𝐈𝐂 chính là nằm ở tính ứng dụng cao, giúp nàng từ chốn công sở bận rộn cho đến những buổi gặp gỡ bạn bè trở nên thật dễ dàng. Không cần phải lăn tăn chọn lựa phức tạp, nàng vẫn toát lên vẻ thời thượng, khí chất, sẵn sàng đón nhận mọi trải nghiệm trong cuộc sống. BST đã có mặt trên toàn hệ thống Eva de Eva, mời nàng ghé store để là người đầu tiên ướm thử những thiết kế mới nhất trong 𝐋𝐎𝐅𝐈 𝐂𝐇𝐈𝐂 nhé!</p>
            <a href="menu.php" class="btn">Sắm ngay</a>
        </div>
    </div>




<?php require_once('footer.php'); ?>