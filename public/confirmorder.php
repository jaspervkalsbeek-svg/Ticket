<?php
include ("../includes/db.php");

$email = $_POST["email"]?? '';
$aanhef = $_POST["aanhef"]?? '';
$amount = $_POST["amount"]?? '';
$ticket_id = $_POST["ticket_id"]?? '';
$Fname = $_POST["Fname"]?? '';
$Lname = $_POST["Lname"]?? '';
$date = $_POST["geboortedatum"]?? '';
$event = $_POST["event"]?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($email) && !empty($Fname) && !empty($Lname) && !empty($date) && !empty($event) && !empty($amount) && !empty($aanhef)) {
        try {
            $stmt = $conn->prepare("INSERT INTO tickets_db (email, ticket_id, Fname, Lname, date, dateofattendance) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$email, $ticket_id, $Fname, $Lname, $date, new Date()]);
            header("Location: Orders.html?msg=ticket+toegevoegd");
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }   
    } else {
        echo "Vul alle vereiste velden in.";
    }
}
?>