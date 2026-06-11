<?php require_once('header.php'); ?>
<?php
if (!isset($_SESSION['customer_id'])) {
    $_SESSION['customer_id'] = 1;
}
$username = $_SESSION['username'] ?? '';
require_once('admin/inc/config.php');

$statement = $pdo->prepare("SELECT * FROM tbl_page WHERE id=1");
$statement->execute();
foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $contact_title = $row['contact_title'];
    $contact_banner = $row['contact_banner'];
}
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $contact_map_iframe = $row['contact_map_iframe'];
    $contact_email = $row['contact_email'];
    $contact_phone = $row['contact_phone'];
    $contact_address = $row['contact_address'];
}
$customer_id = $_SESSION['customer_id'];

// Xử lý gửi tin nhắn khi có POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if ($message !== '') {
        $stmt = $pdo->prepare("INSERT INTO messages (customer_id, username, message, is_admin, created_at) VALUES (?, ?, ?, 0, NOW())");
        $stmt->execute([$customer_id, $username, $message]);
        exit; // nếu dùng Ajax thì return rỗng
    }
}

// Xử lý tải tin nhắn nếu gọi bằng Ajax
if (isset($_GET['load_messages']) && $_GET['load_messages'] == 1) {
    $stmt = $pdo->prepare("SELECT * FROM messages WHERE customer_id = ? ORDER BY created_at ASC");
    $stmt->execute([$customer_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($messages as $msg) {
        $alignmentClass = $msg['is_admin'] ? 'admin-message' : 'user-message';
        echo "<div class='message $alignmentClass'>{$msg['message']}</div>";
    }
    exit;
}
?>

<div class="page-banner" style="background-image: url(assets/uploads/<?php echo $contact_banner; ?>);">
    <div class="inner"><h1>Liên hệ</h1></div>
</div>

<div class="page">
    <div class="container">
        <div class="row">            
            <div class="col-md-12">
                <h3>Chat cùng chúng tôi</h3>
                <div class="chatbox">
                    <div class="messages" id="messages"></div>
                    <form id="chat-form">
                        <input type="text" id="chat-message" name="message" placeholder="Tin nhắn" required>
                        <button type="submit">Gửi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="contact-info">
    <legend><span class="glyphicon glyphicon-globe"></span> Địa chỉ của chúng tôi</legend>
    <address>
        <?php echo nl2br($contact_address); ?>
    </address>
    <address>
        <strong>Số điện thoại:</strong><br>
        <span><?php echo $contact_phone; ?></span>
    </address>
    <address>
        <strong>Email:</strong><br>
        <a href="mailto:<?php echo $contact_email; ?>"><span><?php echo $contact_email; ?></span></a>
    </address>
</div>

<h3>Tìm địa chỉ của chúng tôi trên map</h3>
<?php echo str_replace(
    ['width="600"', 'height="450"'],
    ['width="100%"', 'height="500"'],
    $contact_map_iframe
); ?>
</div>

<?php require_once('footer.php'); ?>

<!-- jQuery + Ajax -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function loadMessages() {
        $.get(window.location.href, { load_messages: 1 }, function(data) {
            $('#messages').html(data);
            $('#messages').scrollTop($('#messages')[0].scrollHeight);
        });
    }

    setInterval(loadMessages, 3000);
    loadMessages();

    $('#chat-form').submit(function(e) {
        e.preventDefault();
        let message = $('#chat-message').val().trim();
        if (message !== '') {
            $.post(window.location.href, { message: message }, function() {
                $('#chat-message').val('');
                loadMessages();
            });
        }
    });
});
</script>

<style>
    body {
        font-family: Arial, sans-serif;
    }
    .contact-info {
    text-align: center; /* Căn giữa nội dung */
    margin: 20px; /* Khoảng cách xung quanh */
}

.contact-info address {
    display: block;
    margin: 10px 0; /* Khoảng cách giữa các địa chỉ */
}
    .page {
        padding: 20px;
    }

    .chatbox {
        border: 1px solid #ccc;
        padding: 10px;
        margin-top: 20px;
        border-radius: 5px;
        background-color: #f9f9f9;
        width: 600px;
    }

    .messages {
        height: 250px; /* Giảm chiều cao */
        overflow-y: scroll;
        margin-bottom: 10px;
        border: 1px solid #eee;
        padding: 10px;
        background-color: #fff;
        border-radius: 5px;
        display: flex;
        flex-direction: column;
    }

    .message {
        margin: 5px 0;
        display: flex;
        align-items: center;
        padding: 10px;
        border-radius: 8px;
        max-width: 100%;
        word-break: break-word;
    }

    .admin-message {
        align-self: flex-start; /* Admin bên trái */
        background-color: #d1e7dd; /* Màu nền cho tin nhắn admin */
        text-align: left;
    }

    .user-message {
        align-self: flex-end; /* User bên phải */
        background-color: #e0e0e0; /* Màu nền cho tin nhắn user */
        text-align: right;
    }

    input[type="text"] {
        width: calc(100% - 80px);
        margin-right: 5px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    button {
        padding: 10px 15px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }

    h3, h4 {
        margin-top: 20px;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    ul li {
        margin: 5px 0;
    }

    ul li a {
        text-decoration: none;
        color: #007bff;
    }

    ul li a:hover {
        text-decoration: underline;
    }

</style>