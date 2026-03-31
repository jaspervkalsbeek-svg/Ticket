<?php
session_start();
include ("../includes/db.php");

$email = $_POST["email"] ?? '';
$birthdate = $_POST["birthdate"] ?? '';
$scannen = $_POST["scannen"] ?? '';
$herkomst = $_POST["herkomst"] ?? '';
$groepscode = $_POST["groepscode"] ?? '';

$types = $_POST["type"] ?? [];
$type1 = $types[0] ?? NULL;
$type2 = $types[1] ?? NULL;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($email) && !empty($birthdate) && !empty($scannen) && !empty($herkomst) && !empty($groepscode)) {
        try {
            $stmt = $conn->prepare("INSERT INTO tickets_tb (email, birthdate, scannen, herkomst, groepscode) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$email, $birthdate, $scannen, $herkomst, $groepscode]);
            header("Location: admin.php?msg=Product+toegevoegd");
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    } else {
        echo "Vul alle vereiste velden in.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <br>
    <a href="admin.php">Terug naar adminpagina</a>
</body>
</html>