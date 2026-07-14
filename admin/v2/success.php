<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dagranglijst</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
</head>
<body>
<?php
require_once 'auth.php';
require_once '../../includes/db.php';

$currentPage = 'success';
include 'sidebar.php';
?>

<main class="main">
    <div class="page-header">
        <h1>Dagranglijst</h1>
        <p>Welke dag was het meest succesvol?</p>
    </div>

    <?php
    $days = $conn->query('
        SELECT
            DATE(o.created_at) AS day,
            COUNT(o.id) AS order_count,
            COALESCE(SUM(o.total_price), 0) AS revenue,
            COUNT(t.id) AS ticket_count
        FROM orders o
        LEFT JOIN tickets_tb t ON t.order_id = o.id
        GROUP BY DATE(o.created_at)
        ORDER BY revenue DESC
    ')->fetchAll(PDO::FETCH_ASSOC);

    if (empty($days)): ?>
        <div class="empty-state">
            <div class="big">📭</div>
            <p>Nog geen bestellingen om te rangschikken.</p>
        </div>
    <?php else:
        $revenues = array_column($days, 'revenue');
        $maxRev = max($revenues);
        $minRev = min($revenues);
        $range = $maxRev - $minRev;

        function tierForScore($pct) {
            if ($pct >= 90) return ['S', 's'];
            if ($pct >= 70) return ['A', 'a'];
            if ($pct >= 50) return ['B', 'b'];
            if ($pct >= 30) return ['C', 'c'];
            if ($pct >= 10) return ['D', 'd'];
            return ['F', 'f'];
        }
    ?>

    <div class="section-title">Tier list — beste dagen</div>
    <div class="tier-list">
        <?php foreach ($days as $row):
            $pct = $range > 0 ? (($row['revenue'] - $minRev) / $range) * 100 : 100;
            [$tier, $cls] = tierForScore($pct);
            $dayFormatted = date('l j F Y', strtotime($row['day']));
            $today = date('Y-m-d') === $row['day'];
        ?>
        <div class="tier-row">
            <div class="tier-label <?= $cls ?>"><?= $tier ?></div>
            <div class="tier-content">
                <div class="tier-day">
                    <?= htmlspecialchars($dayFormatted) ?>
                    <?php if ($today): ?><small>Vandaag</small><?php endif; ?>
                </div>
                <div class="tier-stats">
                    <span>📦 <strong><?= $row['order_count'] ?></strong> bestellingen</span>
                    <span>🎟️ <strong><?= $row['ticket_count'] ?></strong> tickets</span>
                </div>
                <div class="tier-revenue">€&nbsp;<?= number_format($row['revenue'], 2, ',', '.') ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="tier-legend">
        <span class="tier-legend-item"><span class="tier-legend-swatch" style="background:var(--tier-s)"></span> S = top 10%</span>
        <span class="tier-legend-item"><span class="tier-legend-swatch" style="background:var(--tier-a)"></span> A = 70-89%</span>
        <span class="tier-legend-item"><span class="tier-legend-swatch" style="background:var(--tier-b)"></span> B = 50-69%</span>
        <span class="tier-legend-item"><span class="tier-legend-swatch" style="background:var(--tier-c)"></span> C = 30-49%</span>
        <span class="tier-legend-item"><span class="tier-legend-swatch" style="background:var(--tier-d)"></span> D = 10-29%</span>
        <span class="tier-legend-item"><span class="tier-legend-swatch" style="background:var(--tier-f)"></span> F = 0-9%</span>
    </div>
    <?php endif; ?>
</main>

</body>
</html>
