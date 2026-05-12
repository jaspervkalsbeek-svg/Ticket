<?php
require_once '../includes/db.php';
 
$stmt = $conn->query('SELECT * FROM events ORDER BY start_date ASC');
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Festivals</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="festivalstyle.css">
</head>
<body>
<div class="page">
    <header>
        <img src="../img/Spik en Span.png" alt="Spik en Span">
        <h1>Kies je festival</h1>
        <p>Selecteer een festival om tickets te bestellen</p>
    </header>
 
    <?php if (empty($events)): ?>
        <div class="empty">Geen festivals beschikbaar op dit moment.</div>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($events as $e):

                $priceStmt = $conn->prepare('SELECT MIN(price) as min_price FROM ticket_type_tb WHERE event_id = ? AND deleted_at IS NULL');
                $priceStmt->execute([$e['id']]);
                $minPrice = $priceStmt->fetchColumn();
            ?>
                <a class="card" href="ordersV2.php?event_id=<?= $e['id'] ?>">
                    <div class="card-date">
                        <?= date('d M Y', strtotime($e['start_date'])) ?>
                        <?php if ($e['end_date'] && $e['end_date'] !== $e['start_date']): ?>
                            – <?= date('d M Y', strtotime($e['end_date'])) ?>
                        <?php endif; ?>
                    </div>
                    <div class="card-name"><?= htmlspecialchars($e['name']) ?></div>
                    <div class="card-desc"><?= htmlspecialchars($e['discription']) ?></div>
                    <div class="card-location">📍 <?= htmlspecialchars($e['location']) ?></div>
                    <?php if ($minPrice): ?>
                        <span class="card-price">v.a. €<?= number_format($minPrice, 2, ',', '.') ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
 
    <a class="back" href="homepage.php">← Terug naar home</a>
</div>
</body>
</html>
