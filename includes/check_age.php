<?php
include ("../include/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $birthdate = $_POST['birthdate'] ?? '';

if (empty($birthdate)) {
    echo'geen geboortedatum ingevuld';
    exit;
    }

$today = new DateTime();
$birthdate = new DateTime($birthdate);
$age = $today->diff($birthdate)->y;

if ($age >= 18) {
    echo "Je bent $age jaar oud. dit is automatische een volwassen ticket.";
} else {
    echo "Je bent $age jaar oud. Dit is automatisch een minderjaarige ticket.";
}}
?>