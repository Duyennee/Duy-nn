<?php
session_start();
require_once 'Facebook/autoload.php';

$fb = new Facebook\Facebook([
    'app_id' => '1219337406581916',
    'app_secret' => '7a20f0a77fd2db375242dd1ff3cdfcdd',
    'default_graph_version' => '5.7.0',
]);

$helper = $fb->getRedirectLoginHelper();

try {
    $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    // Graph returned an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    // Facebook SDK returned an error
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (!isset($accessToken)) {
    if ($helper->getError()) {
        header('HTTP/1.0 401 Unauthorized');
        echo "Error: " . $helper->getError() . "\n";
        echo "Error Code: " . $helper->getErrorCode() . "\n";
        echo "Error Reason: " . $helper->getErrorReason() . "\n";
        echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
        header('HTTP/1.0 400 Bad Request');
        echo 'Bad request';
    }
    exit;
}

// Logged in
$_SESSION['facebook_access_token'] = (string) $accessToken;

// Get user details
try {
    $response = $fb->get('/me?fields=id,name,email', $accessToken);
    $user = $response->getGraphUser();

    // Here, you can save user info to the database or session
    $_SESSION['customer'] = [
        'id' => $customer->getId(),
        'name' => $customer->getName(),
        'email' => $customer->getEmail(),
    ];

    // Redirect to dashboard or any desired page
    header("Location: " . BASE_URL . "dashboard.php");
    exit;

} catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}
?>