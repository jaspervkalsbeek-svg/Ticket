<?php

session_start();

ini_set("display_errors",1);
ini_set("display_startup_errors",1);
error_reporting(E_ALL);
include ("../includes/db.php");
$id = (int)($_GET['email'] ?? 0);

if ($id <= 0) {
    echo "ongeldige ticket opgegeven.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email   = trim($_POST['email']   ?? '');
    $birthdate = trim($_POST['birthdate'] ?? '');
    $scannen = trim($_POST['scannen'] ?? '');
    $herkomst = trim($_POST['herkomst'] ?? '');
    $groepscode = trim($_POST['groepscode'] ?? '');

    if ($email && $birthdate && $scannen && $herkomst && $groepscode) {

    try {
        $stmt = $conn->prepare("UPDATE tickets_tb SET email = ?, birthdate = ?, scannen = ?, herkomst = ?, groepscode = ? WHERE id = ?");    
        $stmt->execute([$email, $birthdate, $scannen, $herkomst, $groepscode, $id]);

        if ($stmt->execute()) {
            header("Location: admin.php?msg=Product+bijgewerkt");
            exit;
        } 
        } catch (PDOException $e) {
            $error = "Update mislukt: " . $e->getMessage();
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

<h2>Product wijzigen: <?= htmlspecialchars($product['name']) ?></h2>

<?php if (isset($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST">
    <label>Email</label>
    <input type="hidden" name="email" value="<?= htmlspecialchars($product['email']) ?>">
    
    <label>volwassen</label>
    <input type="date" name="age" required value="<?= htmlspecialchars($product['volwassen']) ?>">

    

    <label>Weight:</label>
    <input type="text" name="weight" required class="weight" placeholder="Weight" value="<?= htmlspecialchars($product['weight']) ?>">

    <label>Height:</label>
    <input type="text" name="height" required class="height" placeholder="Height" value="<?= htmlspecialchars($product['height']) ?>">

    <label>Description:</label>
    <input type="text" name="description" required class="description" placeholder="Description" value="<?= htmlspecialchars($product['description']) ?>">

    <button type="submit">Opslaan</button>
    &nbsp; &nbsp;
    <a href="admin.php">Annuleren / Terug</a>
</form>

</body>
</html>