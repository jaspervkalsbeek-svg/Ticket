<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestellingen</title>
    <link rel="stylesheet" href="add_festival_style.css">
<style>
    th, td { 
    padding: 6px 10px; 
    border: 1px solid #FFD600; 
    border-collapse: collapse;

}
    th { 
        background: rgba(255, 255, 255, 0.06); 
    }
    table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }
</style>
</head>
<body>
<?php require_once '../../includes/db.php';
?>

<aside class="sidebar">
    <div class="sidebar-logo">
        Admin Panel
        <span>Spik &amp; Span</span>
    </div>

    <div class="nav-label">Beheer</div>
    <a href="index.php" class="nav-item"><span class="icon">🏠</span> Dashboard</a>
    <a href="events.php" class="nav-item"><span class="icon">🎪</span> Evenementen</a>
    <a href="ticket_types.php" class="nav-item"><span class="icon">🎟️</span> Ticket types</a>
    <a href="coupons.php" class="nav-item"><span class="icon">🏷️</span> Kortingscodes</a>
    <a href="orders.php" class="nav-item active"><span class="icon">📦</span> Bestellingen</a>
 
    <div class="sidebar-footer">
        <a href="../public/festivals.php">← Terug naar site</a>
    </div>
</aside>

<main class="main"> 
    <div class="page-header">
        <h1>Dashboard</h1>
        <p>Beheer evenementen, tickets en kortingscodes</p>
    </div>

    <?php
    $orders = $conn->query('SELECT * FROM tickets_tb')->fetchAll(PDO::FETCH_ASSOC);

$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $stmt = $conn->prepare("
        SELECT * FROM  tickets_tb
        WHERE email LIKE :search 
        OR birthdate LIKE :search 
        OR scannen LIKE :search 
        OR herkomst LIKE :search 
    ");

    $searchTerm = "%" . $search . "%";
    $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $result = $conn->query("SELECT * FROM tickets_tb");
}


 if (!$result) {
    echo "<p style='color:red; font-weight:bold;'> dataquery mislukt: ";
 } $data = $result->fetchAll(PDO::FETCH_ASSOC);

    if (!$data) {
        echo "<p style='color:red; font-weight:bold;'> geen tickets gevonden.</p>";
 } else
 ?>
 <form method="GET">
        <input type="text" name="search" placeholder="Zoeken" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button type="submit">Search</button>
    </form>
    <a href="admin.php">Reset</a>

    <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Voornaam</th>
            <th>Achternaam</th>
            <th>Email</th>
            <th>Scanned</th>
            <th>Ticket ID</th>
            <th>Datum</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['id'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['Fname'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['Lname'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['email'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['scanned'] ?? $row['scannen'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['ticket_id'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['date'] ?? '-') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


    <div class="sectio-title">Overzicht</div>

    </div>
</main>
 
</body>
</html>