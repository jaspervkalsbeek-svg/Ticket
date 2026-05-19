<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evenement toevoegen – Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="add_event_style.css">
</head>
<body>
<?php
require_once '../../includes/db.php';
$success = ''; $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $desc     = trim($_POST['description']);
    $start    = $_POST['start_date'];
    $end      = $_POST['end_date'];
    $location = trim($_POST['location']);

    if (!$name || !$start || !$location) {
        $error = 'Vul alle verplichte velden in.';
    } else {
        try {
            $stmt = $conn->prepare('INSERT INTO events (name, description, start_date, end_date, location) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$name, $desc, $start, $end ?: null, $location]);
            $success = 'Evenement <strong>' . htmlspecialchars($name) . '</strong> succesvol toegevoegd!';
        } catch (PDOException $e) {
            $error = 'Fout bij opslaan: ' . $e->getMessage();
        }
    }
}
?>
<aside class="sidebar">
    <div class="sidebar-logo">Admin Panel<span>Spik &amp; Span</span></div>
    <div class="nav-label">Beheer</div>
    <a href="index.php" class="nav-item"><span class="icon">🏠</span> Dashboard</a>
    <a href="events.php" class="nav-item active"><span class="icon">🎪</span> Evenementen</a>
    <a href="ticket_types.php" class="nav-item"><span class="icon">🎟️</span> Ticket types</a>
    <a href="coupons.php" class="nav-item"><span class="icon">🏷️</span> Kortingscodes</a>
    <a href="orders.php" class="nav-item"><span class="icon">📦</span> Bestellingen</a>
    <div class="sidebar-footer"><a href="../public/festivals.php">← Terug naar site</a></div>
</aside>

<main class="main">
    <a href="index.php" class="back">← Terug naar dashboard</a>
    <div class="page-header">
        <h1>Evenement toevoegen</h1>
        <p>Voeg een nieuw evenement toe aan de database</p>
    </div>

    <?php if ($success): ?><div class="alert success"><?= $success ?></div><?php endif; ?>
    <?php if ($error):   ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="POST">
        <div class="section">
            <div class="section-title">Evenement gegevens</div>
            <div class="field">
                <label>Naam *</label>
                <input type="text" name="name" placeholder="Spik & Span XXL 2027" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            </div>
            <div class="field">
                <label>Omschrijving</label>
                <textarea name="description" placeholder="Beschrijving van het evenement..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>
            <div class="row">
                <div class="field">
                    <label>Startdatum *</label>
                    <input type="datetime-local" name="start_date" required value="<?= $_POST['start_date'] ?? '' ?>">
                </div>
                <div class="field">
                    <label>Einddatum</label>
                    <input type="datetime-local" name="end_date" value="<?= $_POST['end_date'] ?? '' ?>">
                </div>
            </div>
            <div class="field">
                <label>Locatie *</label>
                <input type="text" name="location" placeholder="Landgoed Kasteel Limbricht" required value="<?= htmlspecialchars($_POST['location'] ?? '') ?>">
            </div>
        </div>
        <button type="submit" class="submit-btn">Evenement toevoegen →</button>
    </form>
</main>
</body>
</html>