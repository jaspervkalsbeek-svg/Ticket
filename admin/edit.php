<?php
session_start();
include "../includes/db.php";

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    echo "Ongeldige ticket opgegeven.";
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email     = trim($_POST['email'] ?? '');
    $birthdate = trim($_POST['birthdate'] ?? '');
    $scannen   = trim($_POST['scannen'] ?? '');
    $herkomst  = trim($_POST['herkomst'] ?? '');
    $groepscode = trim($_POST['groepscode'] ?? '');

    if ($email && $birthdate && $herkomst) {
        try {
            $stmt = $conn->prepare("UPDATE tickets_tb SET email = ?, birthdate = ?, scannen = ?, herkomst = ?, groepscode = ? WHERE id = ?");
            $stmt->execute([$email, $birthdate, $scannen, $herkomst, $groepscode, $id]);
            header("Location: admin.php?msg=Ticket+bijgewerkt");
            exit;
        } catch (PDOException $e) {
            $error = "Update mislukt: " . $e->getMessage();
        }
    } else {
        $error = "Vul alle velden correct in.";
    }
}

$stmt = $conn->prepare("SELECT * FROM tickets_tb WHERE id = ?");
$stmt->execute([$id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    echo "Ticket niet gevonden.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket wijzigen</title>
    <style>
        body { font-family: sans-serif; max-width: 500px; margin: 40px auto; }
        label { display: block; margin: 1.2em 0 0.3em; }
        input[type=text], input[type=email], input[type=date] { width: 100%; padding: 8px; }
        .error { color: #c00; }
    </style>
</head>
<body>

<h2>Ticket wijzigen: <?= htmlspecialchars($ticket['ticket_id']) ?></h2>

<?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST">
    <label>Email</label>
    <input type="email" name="email" required value="<?= htmlspecialchars($ticket['email']) ?>">

    <label>Geboortedatum</label>
    <input type="date" name="birthdate" required value="<?= htmlspecialchars($ticket['birthdate']) ?>">

    <label>Handmatig scannen?</label>
    <input type="text" name="scannen" value="<?= htmlspecialchars($ticket['scannen']) ?>">

    <label>Herkomst</label>
    <input type="text" name="herkomst" required value="<?= htmlspecialchars($ticket['herkomst']) ?>">

    <label>Groepscode</label>
    <input type="text" name="groepscode" value="<?= htmlspecialchars($ticket['groepscode']) ?>">

    <button type="submit">Opslaan</button>
    &nbsp; &nbsp;
    <a href="admin.php">Annuleren / Terug</a>
</form>

</body>
</html>
