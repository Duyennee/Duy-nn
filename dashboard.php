<?php require_once('header.php'); ?>

<?php
// Check if the customer is logged in or not
if(!isset($_SESSION['customer'])) {
    header('location: '.BASE_URL.'logout.php');
    exit;
} else {
    // If customer is logged in, but admin make him inactive, then force logout this user.
    $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_id=? AND cust_status=?");
    $statement->execute(array($_SESSION['customer']['cust_id'],0));
    $total = $statement->rowCount();
    if($total) {
        header('location: '.BASE_URL.'logout.php');
        exit;
    }
}
?>

<div class="page">
    <div class="container">
        <div class="row">            
            <div class="col-md-12">
                <div class="user-content">
                    <h3 class="text-center">
                        <?php echo 'Chào mừng bạn đến với quản lý trang'?>
                    </h3>
                </div>                
            </div>

            <div class="col-md-12"> 
                <?php require_once('customer-sidebar.php'); ?>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>


<style>
    .container {
        max-width: 1200px; /* Kích thước tối đa của container */
        margin: 0 auto; /* Căn giữa */
        padding: 20px; /* Khoảng cách bên trong */
    }

    .user-content {
        border: 1px solid #ddd; /* Đường viền */
        border-radius: 8px; /* Bo góc */
        padding: 20px; /* Khoảng cách bên trong */
        background-color: #f9f9f9; /* Màu nền */
        font-size: 50px;
    }

    h3.text-center {
        margin-bottom: 30px; /* Khoảng cách dưới tiêu đề */
        font-size: 40px;
    }
</style>