<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

require_once "../includes/db.php";
$email = $_SESSION['email'];

$orders = $conn->prepare("
    SELECT o.*, e.name AS event_name, e.start_date, e.location
    FROM orders o
    LEFT JOIN events e ON o.event_id = e.id
    WHERE o.email = ?
    ORDER BY o.created_at DESC
");
$orders->execute([$email]);
$orders = $orders->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn bestellingen</title>
    <link rel="stylesheet" href="login.css">
</head>
<body class="user-page">
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>Mijn bestellingen</h1>
        <a href="logout.php" class="logout-btn">Uitloggen</a>
    </div>

    <?php if (empty($orders)): ?>
        <div class="empty">
            <p>Geen bestellingen gevonden voor <strong><?= htmlspecialchars($email) ?></strong>.</p>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order):
            $tickets = $conn->prepare("SELECT * FROM tickets_tb WHERE order_id = ?");
            $tickets->execute([$order['id']]);
            $tickets = $tickets->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="order-card">
            <h3><?= htmlspecialchars($order['event_name']) ?></h3>
            <div class="order-meta">
                Bestel #<?= $order['id'] ?> &middot;
                <?= date('d M Y H:i', strtotime($order['created_at'])) ?> &middot;
                📍 <?= htmlspecialchars($order['location']) ?>
            </div>
            <div class="ticket-list">
                <?php foreach ($tickets as $t): ?>
                <div class="ticket-row">
                    <span><?= htmlspecialchars($t['Fname'] . ' ' . $t['Lname']) ?></span>
                    <span class="ticket-id"><?= htmlspecialchars($t['ticket_id']) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="total">Totaal: € <?= number_format($order['total_price'], 2, ',', '.') ?></div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="../public/festivals.php" class="back-link">← Terug naar festivals</a>
</div>
</body>
</html>
