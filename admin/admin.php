<?php
session_start();
include "../includes/db.php";

if (empty($_SESSION['email'])) {
    header("Location: ../inlog_page/login.php");
    exit;
}

// DELETE
if (isset($_POST["action"]) && $_POST["action"] === "delete") {
    $id = $_POST["id"] ?? null;
    $stmt = $conn->prepare("DELETE FROM tickets_tb WHERE id = :id");
    $stmt->bindParam(":id", $id);
    if ($stmt->execute()) {
        header("Location: admin.php");
        exit;
    } else {
        $error = "Verwijderen mislukt.";
    }
}

$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $stmt = $conn->prepare("
        SELECT * FROM tickets_tb
        WHERE email LIKE :search
        OR birthdate LIKE :search
        OR scannen LIKE :search
        OR herkomst LIKE :search
        OR groepscode LIKE :search
    ");
    $searchTerm = "%" . $search . "%";
    $stmt->bindValue(':search', $searchTerm);
    $stmt->execute();
    $result = $stmt;
} else {
    $result = $conn->query("SELECT * FROM tickets_tb");
}

$data = $result->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin – Tickets</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table { border-collapse: collapse; margin: 1em 0; width: 100%; }
        th, td { padding: 6px 10px; border: 1px solid #999; }
        th { background: #eee; }
        .btn { display: inline-block; padding: 6px 12px; margin: 2px; text-decoration: none; color: white; border-radius: 4px; font-size: 0.95em; cursor: pointer; }
        .btn.change { background-color: #007bff; }
        .btn.delete { background-color: #dc3545; }
        .btn:hover { opacity: 0.9; }
    </style>
</head>
<body>

<div class="box">
    <h2>Voeg ticket toe</h2>
    <form action="insert.php" method="POST" class="insert-form">
        Email <input name="email" required type="email" placeholder="Email"><br><br>
        Geboortedatum <input name="birthdate" required type="date"><br><br>
        Handmatig scannen? <input name="scannen" type="checkbox"><br><br>
        Herkomst <input name="herkomst" required type="text" placeholder="Herkomst"><br><br>
        Groepscode <input name="groepscode" type="text" placeholder="Groepscode"><br><br>
        <input type="Submit" value="Submit">
    </form>
</div>

<?php if (!empty($search)): ?>
    <p>Resultaten voor: "<?= htmlspecialchars($search) ?>" – <a href="admin.php">Reset</a></p>
<?php endif; ?>

<form method="GET">
    <input type="text" name="search" placeholder="Zoeken" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
</form>

<?php if (empty($data)): ?>
    <p style="color:red; font-weight:bold;">Geen tickets gevonden.</p>
<?php else: ?>
<table>
    <tr>
        <th>id</th>
        <th>Email</th>
        <th>Geboortedatum</th>
        <th>Scannen</th>
        <th>Herkomst</th>
        <th>Groepscode</th>
        <th>Acties</th>
    </tr>
    <?php foreach ($data as $row): ?>
    <tr>
        <td><?= htmlspecialchars($row['id'] ?? '—') ?></td>
        <td><?= htmlspecialchars($row['email'] ?? '—') ?></td>
        <td><?= htmlspecialchars($row['birthdate'] ?? '—') ?></td>
        <td><?= htmlspecialchars($row['scannen'] ?? '—') ?></td>
        <td><?= htmlspecialchars($row['herkomst'] ?? '—') ?></td>
        <td><?= htmlspecialchars($row['groepscode'] ?? '—') ?></td>
        <td>
            <form action="admin.php" method="POST" style="display:inline;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" class="btn delete"
                    onclick="return confirm('Weet je zeker dat je <?= htmlspecialchars($row['email'], ENT_QUOTES) ?> wilt verwijderen?');">
                    Verwijderen
                </button>
            </form>
            <a href="edit.php?id=<?= $row['id'] ?>"
               class="btn change"
               onclick="return confirm('Weet je zeker dat je <?= htmlspecialchars($row['email'], ENT_QUOTES) ?> wilt wijzigen?');">
                Wijzigen
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>

</body>
</html>
