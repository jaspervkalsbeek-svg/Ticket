<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestellingen</title>
    <link rel="stylesheet" href="main.css">
<style>
    :root
    {
        --yellow: #FFD600;
    }

    th, td { 
    padding: 6px 10px; 
    border: 1px solid var(--yellow); 
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
    .searchbtn {
        padding: 5px;
        background: var(--yellow);
        border-radius: 12px;
        font-family: 'bebas neue', sans-serif;
        margin-top: 10px;
        margin-bottom: 10px;
        margin-right: 10px;
        color: black;
    }
    .searchbar {
        width: 50%;
}
    .deletebtn {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
    }

    .editbtn {
        background-color: var(--yellow);
        color: black;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
    }
    .deletebtn:hover {
        opacity: 0.9;
    }
    .editbtn:hover {
        opacity: 0.9;
    }
</style>
</head>
<body>
<?php 
require_once 'auth.php';
require_once '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'] ?? null;
    if ($id) {
        $stmt = $conn->prepare("DELETE FROM tickets_tb WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            header("Location: orders.php");
            exit;
        }
    }
}
?>

<?php $currentPage = 'orders'; include 'sidebar.php'; ?>

<main class="main"> 
    <div class="page-header">
        <h1>Dashboard</h1>
        <p>Beheer evenementen, tickets en kortingscodes</p>
    </div>

    <?php
    $search = $_GET['search'] ?? '';
    if (!empty($search)) {
        $stmt = $conn->prepare("
            SELECT t.*, o.herkomst
            FROM tickets_tb t
            LEFT JOIN orders o ON t.order_id = o.id
            WHERE t.email LIKE :search
            OR t.Fname LIKE :search
            OR t.Lname LIKE :search
            OR t.ticket_id LIKE :search
            OR o.herkomst LIKE :search
        ");
        $searchTerm = "%" . $search . "%";
        $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $data = $conn->query("
            SELECT t.*, o.herkomst
            FROM tickets_tb t
            LEFT JOIN orders o ON t.order_id = o.id
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    if (!$data) {
        echo "<p style='color:red; font-weight:bold;'> geen tickets gevonden.</p>";
 } else
 ?>
 <div class="searchbar"> 
    <form method="GET" class="searchbar">
        <input type="text" name="search" placeholder="Zoeken" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button type="submit" class="searchbtn">Search</button>
        <a href="orders.php">Reset</a>
    </form>
    
</div>


    <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Voornaam</th>
            <th>Achternaam</th>
            <th>Email</th>
            <th>Provincie</th>
            <th>Scanned</th>
            <th>Ticket ID</th>
            <th>Datum</th>
            <th>Edit</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['id'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['Fname'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['Lname'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['email'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['herkomst'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['scanned'] ?? $row['scannen'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['ticket_id'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['date'] ?? '-') ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" class="deletebtn"
                        onclick="return confirm('Weet je zeker dat je <?= htmlspecialchars($row['email'], ENT_QUOTES) ?> wilt verwijderen?');">
                        🗑️ Verwijderen
                    </button>
                </form>
        <a href="edit.php?id=<?= $row['id'] ?>" class="editbtn"
                            onclick="return confirm('Weet je zeker dat je <?= htmlspecialchars($row['email'], ENT_QUOTES) ?> wilt wijzigen?');">
                            wijzigen ✏️
                            </a></td>
        </tr>
            
        <?php endforeach; ?>

    </tbody>
</table>


    <div class="sectio-title">Overzicht</div>

    </div>
</main>
 
</body>
</html>