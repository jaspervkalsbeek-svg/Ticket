<?php
include '../includes/db.php';

if (isset($_POST['submit'])) {
    $email    = $_POST['email'];
    $leeftijd = $_POST['leeftijd'];
    $fname    = $_POST['fname'];
    $lname    = $_POST['lname'];
    $herkomst = $_POST['herkomst'];
    $ticketid = $_POST['ticketid'];

    $stmt = $conn->prepare("INSERT INTO users (email, leeftijd, fname, lname, herkomst, ticket_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$email, $leeftijd, $fname, $lname, $herkomst, $ticketid]);
    echo "New record created successfully";
}
?>

<form action="create.php" method="post">
    Email: <input type="email" name="email" placeholder="email"><br>
    Leeftijd: <input type="text" name="leeftijd" placeholder="leeftijd"><br>
    First Name: <input type="text" name="fname" placeholder="first name"><br>
    Last Name: <input type="text" name="lname" placeholder="last name"><br>
    Herkomst: <input type="text" name="herkomst" placeholder="herkomst"><br>
    Ticket ID: <input type="text" name="ticketid" placeholder="ticket ID"><br>
    <input type="submit" name="submit" value="Create User">
</form>
