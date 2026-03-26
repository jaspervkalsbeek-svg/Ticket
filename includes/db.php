<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tickets_db";

$conn = new PDO("mysql:host=$servername; dbname=$dbname", $username, $password);

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection Failed". $e->getMessage());
}

?>