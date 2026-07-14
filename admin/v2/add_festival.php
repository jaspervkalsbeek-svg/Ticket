<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuw festival – Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
</head>
<body>
<?php
require_once 'auth.php';
require_once '../../includes/db.php';
 
$success = '';
$error   = '';
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = trim($_POST['name']);
    $desc       = trim($_POST['description']);
    $start      = $_POST['start_date'];
    $end        = $_POST['end_date'];
    $location   = trim($_POST['location']);
    $types      = $_POST['types'] ?? [];
    $nameli = trim($_POST['name_li']);
    $descriptionli = trim($_POST['description_li']);
 
    if (!$name || !$start || !$location) {
        $error = 'Vul alle verplichte velden in.';
    } else {
        try {
            // Insert event
            $stmt = $conn->prepare('INSERT INTO events (name, description, start_date, end_date, location) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$name, $desc, $start, $end ?: null, $location]);
            $event_id = $conn->lastInsertId();
 
            // Insert ticket types
            foreach ($types as $t) {
                if (empty($t['name']) || !isset($t['price'])) continue;
                $ttStmt = $conn->prepare('INSERT INTO ticket_type_tb (name, price, max_per_order, max_available, event_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
                $ttStmt->execute([
                    trim($t['name']),
                    (float)$t['price'],
                    (int)($t['max_per_order'] ?: 8),
                    (int)($t['max_available'] ?: 0) ?: null,
                    $event_id
                ]);
            }
 
            $success = 'Festival <strong>' . htmlspecialchars($name) . '</strong> succesvol aangemaakt!';
        } catch (PDOException $e) {
            $error = 'Fout bij opslaan: ' . $e->getMessage();
        }
    }
}
?>
 
<?php $currentPage = ''; include 'sidebar.php'; ?>
 
<main class="main">
    <a href="index.php" class="back">← Terug naar dashboard</a>
 
    <div class="page-header">
        <h1>Nieuw festival</h1>
        <p>Voeg een volledig nieuw festival toe met ticket types</p>
    </div>
 
    <?php if ($success): ?><div class="alert success"><?= $success ?></div><?php endif; ?>
    <?php if ($error):   ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
 
    <form method="POST">
 
        <!-- Event details -->
        <div class="section">
            <div class="section-title">Festival gegevens</div>
 
            <div class="field">
                <label>Naam *</label>
                <input type="text" name="name" placeholder="Spik & Span XXL 2027" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            </div>
            <div class="field">
                <label>Naam limburgs</label>
                <input type="text" name="name_li" placeholder="vertaling" value="<?= htmlspecialchars($_POST['name_li'] ?? '') ?>">
            </div>
 
            <div class="field">
                <label>Omschrijving</label>
                <textarea name="description" placeholder="Beschrijving van het festival..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>
            <div class="field">
                <label>Omschrijving limburgs</label>
                <textarea name="description_li" placeholder="vertaling"><?= htmlspecialchars($_POST['description_li'] ?? '') ?></textarea>
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
                <input type="text" name="location" placeholder="Landgoed Kasteel Limbricht, Limbricht" required value="<?= htmlspecialchars($_POST['location'] ?? '') ?>">
            </div>
        </div>
 
        <!-- Ticket types -->
        <div class="section">
            <div class="section-title">Ticket types</div>
            <div id="ticket-types">
                <!-- Default: 1 type row -->
                <div class="ticket-type-row">
                    <button type="button" class="remove-btn" onclick="removeType(this)">✕</button>
                    <div class="row">
                        <div class="field">
                            <label>Naam</label>
                            <input type="text" name="types[0][name]" placeholder="Normaal">
                        </div>
                        <div class="field">
                            <label>Prijs (€)</label>
                            <input type="number" name="types[0][price]" placeholder="35.00" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="field">
                            <label>Max per bestelling</label>
                            <input type="number" name="types[0][max_per_order]" placeholder="8" min="1">
                        </div>
                        <div class="field">
                            <label>Max beschikbaar</label>
                            <input type="number" name="types[0][max_available]" placeholder="500" min="0">
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="add-type-btn" onclick="addType()">+ Ticket type toevoegen</button>
        </div>
 
        <button type="submit" class="submit-btn">Festival aanmaken →</button>
    </form>
</main>
 
<script>
let typeCount = 1;
 
function addType() {
    const i = typeCount++;
    const html = `
    <div class="ticket-type-row">
        <button type="button" class="remove-btn" onclick="removeType(this)">✕</button>
        <div class="row">
            <div class="field">
                <label>Naam</label>
                <input type="text" name="types[${i}][name]" placeholder="Junior">
            </div>
            <div class="field">
                <label>Prijs (€)</label>
                <input type="number" name="types[${i}][price]" placeholder="20.00" step="0.01" min="0">
            </div>
        </div>
        <div class="row">
            <div class="field">
                <label>Max per bestelling</label>
                <input type="number" name="types[${i}][max_per_order]" placeholder="8" min="1">
            </div>
            <div class="field">
                <label>Max beschikbaar</label>
                <input type="number" name="types[${i}][max_available]" placeholder="500" min="0">
            </div>
        </div>
    </div>`;
    document.getElementById('ticket-types').insertAdjacentHTML('beforeend', html);
}
 
function removeType(btn) {
    const rows = document.querySelectorAll('.ticket-type-row');
    if (rows.length > 1) btn.closest('.ticket-type-row').remove();
}
</script>
</body>
</html>