<?php
require_once '../includes/db.php';

$allowed_lang = ['nl', 'li'];
$taal = in_array($_GET['lang'] ?? '', $allowed_lang) ? $_GET['lang'] : 'nl';
$t = include "../lang/{$taal}.php";
$name_col = $taal === 'nl' ? 'name' : "name_{$taal}";
$desc_col  = $taal === 'nl' ? 'description' : "description_{$taal}";
$stmt = $conn->query("SELECT e.id, e.{$name_col} as name, e.{$desc_col} as description, e.start_date, e.end_date, e.location, (SELECT MIN(price) FROM ticket_type_tb WHERE event_id = e.id AND deleted_at IS NULL) as min_price FROM events e ORDER BY e.start_date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="<?= $taal ?>">
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
        <h1><?= $t['festivals_titel'] ?></h1>
        <p><?= $t['festivals_subtitel'] ?></p>

        <div class="taal-keuze">
            <a href="?lang=nl">🇳🇱 NL</a>
            <a href="?lang=li">🏴 LI</a>
        </div>
    </header>

    <?php if (empty($events)): ?>
        <div class="empty"><?= $t['geen_festivals'] ?></div>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($events as $e): ?>
                <a class="card" href="ordersV2.php?event_id=<?= $e['id'] ?>&lang=<?= $taal ?>">
                    <div class="card-date">
                        <?= date('d M Y', strtotime($e['start_date'])) ?>
                        <?php if ($e['end_date'] && $e['end_date'] !== $e['start_date']): ?>
                            - <?= date('d M Y', strtotime($e['end_date'])) ?>
                        <?php endif; ?>
                    </div>
                    <div class="card-name"><?= htmlspecialchars($e['name']) ?></div>
                    <div class="card-desc"><?= htmlspecialchars($e['description']) ?></div>
                    <div class="card-location">📍 <?= htmlspecialchars($e['location']) ?></div>
                    <?php if ($e['min_price']): ?>
                        <span class="card-price">v.a. €<?= number_format($e['min_price'], 2, ',', '.') ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <a class="back" href="index.php?lang=<?= $taal ?>"><?= $t['terug'] ?></a>
</div>
</body>
</html>