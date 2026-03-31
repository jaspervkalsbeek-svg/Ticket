 <?php
ini_set ('display_errors',1);
ini_set ('display_startup_errors',1);
error_reporting (E_ALL);

include ("../includes/db.php");


//  DELETE
if (isset($_POST["action"]) && $_POST["action"] === "delete") {
    $id = $_POST["dex_number"] ?? null;

   $stmt = $conn->prepare("DELETE FROM tickets_tb WHERE id = :id");
   $stmt->bindParam(":id", $id);

   if ($stmt->execute()) {
       header ("Location: admin.php");
       exit;
    } else { $error = "verwijderen mislukt:". implode(", ", $conn->errorInfo()); 
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>insert/delete page</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table {
            border-collapse: collapse;
            margin: 1em 0;
        }
        th, td { padding: 6px 10px; border: 1px solid #999; }
        th { background: #eee; }
        .btn {
    display: inline-block;
    padding: 6px 12px;
    margin: 2px;
    text-decoration: none;
    color: white;
    border-radius: 4px;
    font-size: 0.95em;
    cursor: pointer;
}

.btn.btn.change {
    background-color: #007bff;
}

.btn.delete {
    background-color: #dc3545;
}

.btn:hover {
    opacity: 0.9;
}


    </style>
</head>

<!-- lijst van pokemons -->
<body>

        <div class="box">
            <h2>
                voeg ticket toe
            </h2>
            <form action="insert.php" method="POST" class="insert-form">
                Email
                <input name="email" required type="email" placeholder="Email"> <br> <br>

                Geboortedatum
                <input name="birthdate" required type="date" placeholder="Geboortedatum"> <br> <br>

                Handmatig scannen?
                <input name="scannen" required type="checkbox" placeholder="scannen"> <br/> <br>

                Herkomst
                <input name="herkomst" required type="text" placeholder="Herkomst"> <br> <br>
                
            </div>
                <br> <br>
                <input type="Submit" value="Submit">
                <br> <br> 
            </form>
            <p>

<?php



$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $stmt = $conn->prepare("
        SELECT * FROM  tickets_tb
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


 if (!$result) {
    echo "<p style='color:red; font-weight:bold;'> dataquery mislukt: ";
 } $data = $result->fetchAll(PDO::FETCH_ASSOC);

    if (!$data) {
        echo "<p style='color:red; font-weight:bold;'> geen tickets gevonden.</p>";
 } else{
 ?>

    <form method="GET">
        <input type="text" name="search" placeholder="Zoeken" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button type="submit">Search</button>
    </form>
    <a href="admin.php">Reset</a>

    <table>
        <tr>
            <th>id</th>
            <th>Email</th>
            <th>Geboortedatum</th>
            <th>Scannen</th>
            <th>Herkomst</th>
            <th>Groepscode</th>
        </tr>

        <?php foreach ($data as $row) { ?>
            <tr>
            <td><?= htmlspecialchars($row['id']    ?? '—') ?></td>
            <td><?= htmlspecialchars($row['email']  ?? '—') ?></td>
            <td><?= htmlspecialchars($row['birthdate']  ?? '—') ?></td>
            <td><?= htmlspecialchars($row['scannen'] ?? '—') ?></td>
            <td><?= htmlspecialchars($row['herkomst'] ?? '—') ?></td>
            <td><?= htmlspecialchars($row['groepscode'] ?? '—') ?></td>
            <td><form action="admin.php" method="POST" style="display:inline;"> 
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <button type="submit" class="btn delete"
                onclick="return confirm('Weet je zeker dat je <?= htmlspecialchars($row['email'], ENT_QUOTES) ?> wilt verwijderen?');">
                🗑️ Verwijderen
            </button> 
        <a href="edit.php?id=<?= $row['id'] ?>" 
                            class="btn change"
                            onclick="return confirm('Weet je zeker dat je <?= htmlspecialchars($row['email'], ENT_QUOTES) ?> wilt wijzigen?');">
                            wijzigen ✏️
                            </a>
        
        </td> 
 </form>

</tr>
        
    <?php
        }}
    ?>
    </table>
   
</body>
</html>