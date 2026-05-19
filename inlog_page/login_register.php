<?php
session_start();
require_once "../includes/db.php";

if (isset($_POST['login'])) {
    $email     = trim($_POST['email']);
    $ticket_id = trim($_POST['ticket_id']);

    $stmt = $conn->prepare("SELECT email FROM tickets_tb WHERE ticket_id = ? AND email = ? LIMIT 1");
    $stmt->execute([$ticket_id, $email]);
    $match = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($match) {
        $_SESSION['email'] = $match['email'];
        header("Location: user_page.php");
        exit();
    }

    $_SESSION['login_error'] = "Ticket ID en e-mailadres komen niet overeen.";
    header("Location: login.php");
    exit();
}

header("Location: login.php");
exit();
