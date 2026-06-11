
<?php require_once('header.php'); ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<?php
// Fetch settings from database
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

$footer_about = $contact_email = $contact_phone = $contact_address = $footer_copyright = '';
$newsletter_on_off = 0; // Default value
$before_body = ''; // Initialize before_body

foreach ($result as $row) {
    $footer_about = $row['footer_about'];
    $contact_email = $row['contact_email'];
    $contact_phone = $row['contact_phone'];
    $contact_address = $row['contact_address'];
    $footer_copyright = $row['footer_copyright'];
    $total_recent_post_footer = $row['total_recent_post_footer'];
    $total_popular_post_footer = $row['total_popular_post_footer'];
    $newsletter_on_off = $row['newsletter_on_off'];
    $before_body = $row['before_body'];
}

// Process newsletter signup
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Kiểm tra định dạng email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Kiểm tra xem email đã tồn tại chưa
        $checkStatement = $pdo->prepare("SELECT COUNT(*) FROM tbl_newsletter WHERE email = ?");
        $checkStatement->execute([$email]);
        $emailExists = $checkStatement->fetchColumn();

        if ($emailExists) {
            echo '<p>Email này đã được đăng ký trước đó.</p>';
        } else {
            // Thêm vào cơ sở dữ liệu
            $statement = $pdo->prepare("INSERT INTO tbl_newsletter (email) VALUES (?)");
            if ($statement->execute([$email])) {
                echo '<p>Đăng ký thành công! Cảm ơn bạn đã tham gia.</p>';

                // Gửi thông báo cho admin
                $to = 'admin@example.com';
                $subject = 'Mới có người đăng ký nhận thông tin';
                $message = "Email mới đăng ký: $email";
                $headers = 'From: no-reply@example.com' . "\r\n" .
                           'Reply-To: no-reply@example.com' . "\r\n";

                mail($to, $subject, $message, $headers);
            } else {
                echo '<p>Có lỗi xảy ra. Vui lòng thử lại.</p>';
            }
        }
    } else {
        echo '<p>Email không hợp lệ. Vui lòng nhập lại.</p>';
    }
}
?>


<?php if($newsletter_on_off == 1): ?>

	<div class="newsletter">
  <div class="content">
    <span>Nhận thông tin mới từ Min Min</span>
    <h1>Đăng ký để nhận ưu đãi sớm nhất</h1>
    <p></p>
    <div class="input-field">
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Nhập email" required>
        <button class="btn" type="submit">Đăng ký</button>
    </form>
</div>
    <p></p>
    <div class="box-container">
      <div class="box">
        <div class="box-counter"><p class="counter">50</p><i class="bx bx-plus"></i></div>
        <h3>Voucher ưu đãi các sản phẩm</h3>
        <p>Giảm 80% và 55%</p>
      </div>
      <div class="box">
        <div class="box-counter"><p class="counter">299</p><i class="bx bx-plus"></i></div>
        <h3>Quà tặng đi kèm</h3>
        <p>Mua online hoặc offline</p>
      </div>
    </div>
  </div>
</div>
<footer>
  <div class="content">
    <div class="box">
      <img src="admin/img/logo1.png">
      <p>Chúng tôi luôn sẵn sàng lắng nghe những góp ý của các bạn để cửa hàng hoàn thiện một cách tốt nhất! <br>Đừng ngại chi gửi gắm 1 chút tình yêu thương đến cửa hàng nhaaa!!!!</p>

    </div>
    <div class="box">
       <h3>Min Min</h3>
       <a href=""><i class="bx bx-chevron-right"></i>Tài khoản</a>
       <a href=""><i class="bx bx-chevron-right"></i>Lịch sử đặt hàng</a>
       <a href=""><i class="bx bx-chevron-right"></i>Yêu thích</a>
       <a href=""><i class="bx bx-chevron-right"></i>Thông báo</a>
    </div>
    <div class="box">
       <h3>Thông tin cá nhân</h3>
       <a href=""><i class="bx bx-chevron-right"></i>Tài khoản</a>
       <a href=""><i class="bx bx-chevron-right"></i>Lịch sử đặt hàng</a>
       <a href=""><i class="bx bx-chevron-right"></i>Yêu thích</a>
       <a href=""><i class="bx bx-chevron-right"></i>Thông báo</a>
    </div>
    <div class="box">
       <h3>Mở rộng</h3>
       <a href=""><i class="bx bx-chevron-right"></i>Nhãn hàng</a>
       <a href=""><i class="bx bx-chevron-right"></i>Qùa tặng</a>
       <a href=""><i class="bx bx-chevron-right"></i>Ưu đãi</a>
  
    </div>
    <div class="box">
       <h3>Liên hệ với chúng tôi</h3>
       <p><i class="bx bxs-phone"></i>(+84)64 347 927 </p>
       <p><i class="bx bxs-envelope"></i>blue_icecream@gmail.com</p>
       <p><i class="bx bxs-location-plus"></i>Thanh Xuân - Hà Nội </p>
       <div>
        <i class="bx bxl-facebook"></i>
        <i class="bx bxl-instagram"></i>
        <i class="bx bxl-twitter"></i>
       </div>
    </div>
    <!-- 1h53p -->
  </div>
  <div class="footer-bottom">
  <div class="container">
    <div class="row">
      <div class="col-md-12 copyright">
        <?php echo $footer_copyright; ?>
      </div>
    </div>
  </div>
</div>
</footer>


<?php endif; ?>



<a href="#" class="scrollup">
	<i class="fa fa-angle-up"></i>
</a>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $stripe_public_key = $row['stripe_public_key'];
    $stripe_secret_key = $row['stripe_secret_key'];
}
?>

<script src="assets/js/jquery-2.2.4.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="https://js.stripe.com/v2/"></script>
<script src="assets/js/megamenu.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/js/owl.animate.js"></script>
<script src="assets/js/jquery.bxslider.min.js"></script>
<script src="assets/js/jquery.magnific-popup.min.js"></script>
<script src="assets/js/rating.js"></script>
<script src="assets/js/jquery.touchSwipe.min.js"></script>
<script src="assets/js/bootstrap-touch-slider.js"></script>
<script src="assets/js/select2.full.min.js"></script>
<script src="assets/js/custom.js"></script>

<script>

function confirmDelete()

{

return confirm("Sure you want to delete this data?");

}

$(document).ready(function () {

advFieldsStatus = $('#advFieldsStatus').val();

$('#paypal_form').hide();

$('#cash_on_delivery_form').hide();

$('#bank_form').hide();

$('#advFieldsStatus').on('change',function() {

advFieldsStatus = $('#advFieldsStatus').val();

if ( advFieldsStatus == '' ) {

$('#paypal_form').hide();

$('#stripe_form').hide();

$('#bank_form').hide();

} else if ( advFieldsStatus == 'PayPal' ) {

$('#paypal_form').show();

$('#cash_on_delivery_form').hide();

$('#bank_form').hide();

} else if ( advFieldsStatus == 'COD' ) {

$('#paypal_form').hide();

$('#cash_on_delivery_form').show();

$('#bank_form').hide();

} else if ( advFieldsStatus == 'Bank Deposit' ) {

$('#paypal_form').hide();

$('#cash_on_delivery_form').hide();

$('#bank_form').show();

}

});

});

$(document).on('submit', '#scash_on_delivery_form', function () {

// createToken returns immediately - the supplied callback submits the form if there are no errors

$('#submit-button').prop("disabled", true);

$("#msg-container").hide();

Stripe.card.createToken({

number: $('.card-number').val(),

cvc: $('.card-cvc').val(),

exp_month: $('.card-expiry-month').val(),

exp_year: $('.card-expiry-year').val()

// name: $('.card-holder-name').val()

}, stripeResponseHandler);

return false;

});

Stripe.setPublishableKey('<?php echo $stripe_public_key; ?>');

function stripeResponseHandler(status, response) {

if (response.error) {

$('#submit-button').prop("disabled", false);

$("#msg-container").html('<div style="color: red;border: 1px solid;margin: 10px 0px;padding: 5px;"><strong>Error:</strong> ' + response.error.message + '</div>');

$("#msg-container").show();

} else {

var form$ = $("#cash_on_delivery_form");

var token = response['id'];

form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");

form$.get(0).submit();

}

}

</script>

<?php echo $before_body; ?>
</body>
</html>