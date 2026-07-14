<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['email'])) {
    header('Location: ../../inlog_page/login.php');
    exit;
}
