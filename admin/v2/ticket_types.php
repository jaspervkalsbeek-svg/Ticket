<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket type toevoegen – Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
</head>
<body>
<?php
require_once '../../includes/db.php';
$success = ''; $error = '';

$events = $conn->query('SELECT id, name FROM events ORDER BY start_date ASC')->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name          = trim($_POST['name']);
    $price         = (float)$_POST['price'];
    $max_per_order = (int)$_POST['max_per_order'];
    $max_available = (int)$_POST['max_available'] ?: null;
    $event_id      = (int)$_POST['event_id'];

    if (!$name || !$event_id) {
        $error = 'Vul alle verplichte velden in.';
    } else {
        try {
            $stmt = $conn->prepare('INSERT INTO ticket_type_tb (name, price, max_per_order, max_available, event_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
            $stmt->execute([$name, $price, $max_per_order, $max_available, $event_id]);
            $success = 'Ticket type <strong>' . htmlspecialchars($name) . '</strong> succesvol toegevoegd!';
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
    <a href="events.php" class="nav-item"><span class="icon">🎪</span> Evenementen</a>
    <a href="ticket_types.php" class="nav-item active"><span class="icon">🎟️</span> Ticket types</a>
    <a href="coupons.php" class="nav-item"><span class="icon">🏷️</span> Kortingscodes</a>
    <a href="orders.php" class="nav-item"><span class="icon">📦</span> Bestellingen</a>
    <a href="success.php" class="nav-item"><span class="icon">🏆</span> Dagranglijst</a>
    <div class="sidebar-footer"><a href="../../public/festivals.php">← Terug naar site</a></div>
</aside>

<main class="main">
    <a href="index.php" class="back">← Terug naar dashboard</a>
    <div class="page-header">
        <h1>Ticket type toevoegen</h1>
        <p>Voeg een ticket type toe aan een bestaand evenement</p>
    </div>

    <?php if ($success): ?><div class="alert success"><?= $success ?></div><?php endif; ?>
    <?php if ($error):   ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="POST">
        <div class="section">
            <div class="section-title">Ticket type gegevens</div>
            <div class="field">
                <label>Evenement *</label>
                <select name="event_id" required>
                    <option value="">Selecteer evenement</option>
                    <?php foreach ($events as $e): ?>
                        <option value="<?= $e['id'] ?>" <?= (($_POST['event_id'] ?? '') == $e['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($e['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label>Naam *</label>
                <input type="text" name="name" placeholder="Normaal / Junior / Senior" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            </div>
            <div class="row">
                <div class="field">
                    <label>Prijs (€) *</label>
                    <input type="number" name="price" placeholder="35.00" step="0.01" min="0" required value="<?= $_POST['price'] ?? '' ?>">
                </div>
                <div class="field">
                    <label>Max per bestelling</label>
                    <input type="number" name="max_per_order" placeholder="8" min="1" value="<?= $_POST['max_per_order'] ?? '8' ?>">
                </div>
            </div>
            <div class="field">
                <label>Max beschikbaar (leeg = onbeperkt)</label>
                <input type="number" name="max_available" placeholder="500" min="0" value="<?= $_POST['max_available'] ?? '' ?>">
            </div>
        </div>
        <button type="submit" class="submit-btn">Ticket type toevoegen →</button>
    </form>
</main>
</body>
</html>