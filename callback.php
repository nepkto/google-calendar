<?php
session_start();
require_once 'vendor/autoload.php';

$client = new Google_Client();
$errors = [];

try {
    $client->setAuthConfig('client_secret.json');
    $client->addScope(Google_Service_Calendar::CALENDAR);
    $client->setRedirectUri('http://localhost:8000/callback.php');

    if (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $_SESSION['access_token'] = $token;
        header('Location: index.php');
        exit;
    }
} catch (Google_Service_Exception $e) {
    $errors[] = json_decode($e->getMessage())->error->message;
} catch (Google_Exception $e) {
    $errors[] = $e->getMessage();
} catch (Exception $e) {
    $errors[] = "An unexpected error occurred: " . $e->getMessage();
}

if (!empty($errors)) {
    echo '<div style="color: red; background-color: #FEE; padding: 10px; border: 1px solid red; margin-bottom: 20px;"><ul>';
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo '</ul></div>';
    echo '<a href="index.php">Go back to main page</a>';
}
?>