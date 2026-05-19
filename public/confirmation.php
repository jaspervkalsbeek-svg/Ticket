<?php
require_once '../includes/db.php';

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if (!$order_id) {
    header('Location: festivals.php');
    exit;
}

$stmt = $conn->prepare('SELECT * FROM orders WHERE id = ? LIMIT 1');
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header('Location: festivals.php');
    exit;
}

$evStmt = $conn->prepare('SELECT * FROM events WHERE id = ? LIMIT 1');
$evStmt->execute([$order['event_id']]);
$event = $evStmt->fetch(PDO::FETCH_ASSOC);

$ticketStmt = $conn->prepare('
    SELECT t.*, tt.name as type_name, tt.price
    FROM tickets_tb t
    LEFT JOIN ticket_type_tb tt ON t.ticket_type_id = tt.id
    WHERE t.order_id = ?
');
$ticketStmt->execute([$order_id]);
$tickets = $ticketStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestelling bevestigd</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="confirmationstyle.css">
</head>
<body>
<div class="page">

    <div class="success-header">
        <div class="checkmark">✓</div>
        <h1>Bestelling bevestigd!</h1>
        <p>Bestelnummer #<?= $order_id ?></p>
    </div>

    <div class="email-notice">
        📧 Je tickets zijn als PDF verstuurd naar <strong><?= htmlspecialchars($order['email']) ?></strong>
    </div>

    <div class="section">
        <div class="section-title">Jouw gegevens</div>
        <div class="info-row">
            <span class="info-label">Naam</span>
            <span class="info-value"><?= htmlspecialchars($order['Fname'] . ' ' . $order['Lname']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">E-mail</span>
            <span class="info-value"><?= htmlspecialchars($order['email']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Evenement</span>
            <span class="info-value"><?= htmlspecialchars($event['name']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Datum</span>
            <span class="info-value">
                <?= date('d M Y', strtotime($event['start_date'])) ?>
                <?php if ($event['end_date'] && $event['end_date'] !== $event['start_date']): ?>
                    – <?= date('d M Y', strtotime($event['end_date'])) ?>
                <?php endif; ?>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Locatie</span>
            <span class="info-value"><?= htmlspecialchars($event['location']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Besteldatum</span>
            <span class="info-value"><?= date('d M Y H:i', strtotime($order['created_at'])) ?></span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Jouw tickets</div>
        <?php foreach ($tickets as $i => $t): ?>
            <div class="ticket-card">
                <div class="ticket-qr">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= urlencode($t['ticket_id']) ?>"
                         alt="QR Code">
                </div>
                <div class="ticket-info">
                    <div class="ticket-number">Ticket <?= $i + 1 ?></div>
                    <div class="ticket-name"><?= htmlspecialchars($t['Fname'] . ' ' . $t['Lname']) ?></div>
                    <div class="ticket-type"><?= htmlspecialchars($t['type_name']) ?></div>
                    <div class="ticket-id"><?= htmlspecialchars($t['ticket_id']) ?></div>
                </div>
                <div class="ticket-price">€<?= number_format($t['price'], 2, ',', '.') ?></div>
            </div>
        <?php endforeach; ?>

        <div class="total-row">
            <span>Totaal</span>
            <span>€<?= number_format($order['total_price'], 2, ',', '.') ?></span>
        </div>
    </div>

    <a href="index.php" class="home-btn">Terug naar home →</a>

</div>
</body>
</html>