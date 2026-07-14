<?php

session_start();

include ("../../includes/db.php");

if (empty($_SESSION['email'])) {
    header("Location: ../../inlog_page/login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    echo "Ongeldige ticket opgegeven.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $email   = trim($_POST['email']   ?? '');
    $ticket_id = trim($_POST['ticket_id'] ?? '');
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $scanned = (int)($_POST['scanned'] ?? 0);

    if ($email && $ticket_id && $fname && $lname) {
        $stmt = $conn->prepare("UPDATE tickets_tb SET email = ?, ticket_id = ?, fname = ?, lname = ?, scanned = ? WHERE id = ?");    
        if ($stmt->execute([$email, $ticket_id, $fname, $lname, $scanned, $id])) {
            header("Location: orders.php");
            exit;
        } else {
            $error = "Update mislukt.";
        }
    } else {
        $error = "Vul alle velden correct in.";
    }
}


// load current data

$stmt = $conn->prepare("SELECT * FROM tickets_tb WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Product niet gevonden.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product wijzigen</title>
    <style>
        body { font-family: sans-serif; max-width: 500px; margin: 40px auto; }
        label { display: block; margin: 1.2em 0 0.3em; }
        input[type=text], input[type=number] { width: 100%; padding: 8px; }
        .error { color: #c00; }
    </style>
</head>
<body>

<h2>Product wijzigen: <?= htmlspecialchars($product['ticket_id']) ?></h2>

<?php if (isset($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST">
    <input type="hidden" name="id" value="<?= $product['id'] ?>">

    <label>Email:</label>
    <input type="text" name="email" value="<?= htmlspecialchars($product['email']) ?>">

    <label>Ticket ID:</label>
    <input type="text" name="ticket_id" value="<?= htmlspecialchars($product['ticket_id']) ?>">

    <label>Voornaam:</label>
    <input type="text" name="fname" value="<?= htmlspecialchars($product['Fname']) ?>">

    <label>Achternaam:</label>
    <input type="text" name="lname" value="<?= htmlspecialchars($product['Lname']) ?>">

    <label>Gescannd (0 = niet gescand, 1 = gescand):</label>
    <input type="number" name="scanned" min="0" max="1" value="<?= htmlspecialchars($product['scanned']) ?>">

<form method="POST">

    <button type="submit">Opslaan</button>
    &nbsp; &nbsp;
    <a href="orders.php">Annuleren / Terug</a>
</form>

</body>
</html>