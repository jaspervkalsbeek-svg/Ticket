<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
</head>
<body>
<?php
require_once 'auth.php';
require_once '../../includes/db.php';

$currentPage = 'dashboard';
include 'sidebar.php';

?>

<main class="main"> 
    <div class="page-header">
        <h1>Dashboard</h1>
        <p>Beheer evenementen, tickets en kortingscodes</p>
    </div>

    <?php
 $eventCount  = $conn->query('SELECT COUNT(*) FROM events')->fetchColumn();
    $ticketCount = $conn->query('SELECT COUNT(*) FROM ticket_type_tb WHERE deleted_at IS NULL')->fetchColumn();
    $couponCount = $conn->query('SELECT COUNT(*) FROM coupon_tb')->fetchColumn();
    $orderCount  = $conn->query('SELECT COUNT(*) FROM orders')->fetchColumn();
    ?>

    <div class="sectio-title">Overzicht</div>
    <div class="stats-grid"> 
        <div class="stat-card"> 
            <div class="stat-value"><?= $eventCount ?></div>
            <div class="stat-label"> Evenementen</div>
        </div>
        <div class="stat-card"> 
            <div class="stat-value"><?= $ticketCount ?></div>
            <div class="stat-label"> Ticket types</div>
        </div>
        <div class="stat-card"> 
            <div class="stat-value"><?= $couponCount ?></div>
            <div class="stat-label"> Kortingscodes</div>
        </div>
        <div class="stat-card"> 
            <div class="stat-value"><?= $orderCount ?></div>
            <div class="stat-label"> Bestellingen</div>
        </div>
    </div>

    <?php
    $topDays = $conn->query('
        SELECT DATE(created_at) AS day, COUNT(id) AS cnt, COALESCE(SUM(total_price), 0) AS rev
        FROM orders GROUP BY DATE(created_at) ORDER BY rev DESC LIMIT 5
    ')->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($topDays)):
        $maxRev = max(array_column($topDays, 'rev'));
        $minRev = min(array_column($topDays, 'rev'));
        $range = $maxRev - $minRev;
        function tier($pct) {
            if ($pct >= 90) return ['S','#FFD700']; if ($pct >= 70) return ['A','#4CAF50'];
            if ($pct >= 50) return ['B','#2196F3']; if ($pct >= 30) return ['C','#9E9E9E'];
            if ($pct >= 10) return ['D','#795548']; return ['F','#f44336'];
        }
    ?>
    <div class="section-title">🏆 Top dagen</div>
    <div class="top-days-row">
        <?php foreach ($topDays as $d):
            $pct = $range > 0 ? (($d['rev'] - $minRev) / $range) * 100 : 100;
            [$t, $c] = tier($pct);
        ?>
        <div class="top-day-card">
            <span class="top-day-tier" style="background:<?= $c ?>"><?= $t ?></span>
            <div>
                <div class="top-day-name"><?= date('D j M', strtotime($d['day'])) ?></div>
                <div class="top-day-meta"><?= $d['cnt'] ?> ord &middot; €<?= number_format($d['rev'], 0, ',', '.') ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php
    $herkomst = $conn->query('
        SELECT herkomst, COUNT(*) AS cnt, COUNT(*)*100/(SELECT COUNT(*) FROM orders WHERE herkomst IS NOT NULL AND herkomst != "") AS pct
        FROM orders WHERE herkomst IS NOT NULL AND herkomst != ""
        GROUP BY herkomst ORDER BY cnt DESC
    ')->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($herkomst)):
        $herkomstColors = ['#FFD600', '#4CAF50', '#2196F3', '#9E9E9E', '#FF9800', '#f44336'];
    ?>
    <div class="section-title">📍 Provincie bezoekers</div>
    <div class="herkomst-list">
        <?php foreach ($herkomst as $i => $h):
            $color = $herkomstColors[$i % count($herkomstColors)];
        ?>
        <div class="herkomst-bar">
            <div class="herkomst-label"><?= htmlspecialchars($h['herkomst']) ?></div>
            <div class="herkomst-track">
                <div class="herkomst-fill" style="width:<?= round($h['pct']) ?>%;background:<?= $color ?>;"></div>
            </div>
            <div class="herkomst-count"><?= $h['cnt'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

        <div class="section-title">Nieuw festival toevoegen</div>
    <a href="add_festival.php" class="festival-card">
        <div class="festival-card-icon">🎪</div>
        <div class="festival-card-content">
            <h2>Volledig festival aanmaken</h2>
            <p>Voeg een nieuw festival toe met alle informatie: datum, locatie, ticket types en prijzen — alles op één pagina.</p>
        </div>
        <div class="festival-card-arrow">→</div>
    </a>

     <!-- Individual adds -->
    <div class="section-title">Individueel toevoegen</div>
    <div class="cards-grid">
        <a href="add_event.php" class="card">
            <div class="card-icon">🎪</div>
            <div class="card-title">Evenement</div>
            <div class="card-desc">Voeg een nieuw evenement toe met datum, locatie en omschrijving.</div>
            <div class="card-action">Toevoegen →</div>
        </a>
        <a href="add_ticket_type.php" class="card">
            <div class="card-icon">🎟️</div>
            <div class="card-title">Ticket type</div>
            <div class="card-desc">Voeg een ticket type toe aan een bestaand evenement met prijs en beschikbaarheid.</div>
            <div class="card-action">Toevoegen →</div>
        </a>
        <a href="add_coupon.php" class="card">
            <div class="card-icon">🏷️</div>
            <div class="card-title">Kortingscode</div>
            <div class="card-desc">Maak een kortingscode aan met een vast bedrag of percentage korting.</div>
            <div class="card-action">Toevoegen →</div>
        </a>
    </div>
</main>
 
</body>
</html>