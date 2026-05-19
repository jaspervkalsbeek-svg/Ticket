<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
<?php require_once '../../includes/db.php';?>

<aside class="sidebar">
    <div class="sidebar-logo">
        Admin Panel
        <span>Spik &amp; Span</span>
    </div>

    <div class="nav-label">Beheer</div>
    <a href="index.php" class="nav-item active"><span class="icon">🏠</span> Dashboard</a>
    <a href="events.php" class="nav-item"><span class="icon">🎪</span> Evenementen</a>
    <a href="ticket_types.php" class="nav-item"><span class="icon">🎟️</span> Ticket types</a>
    <a href="coupons.php" class="nav-item"><span class="icon">🏷️</span> Kortingscodes</a>
    <a href="orders.php" class="nav-item"><span class="icon">📦</span> Bestellingen</a>
 
    <div class="sidebar-footer">
        <a href="../../public/festivals.php">← Terug naar site</a>
    </div>
</aside>

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