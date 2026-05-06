    <?php
    include '../includes/db.php'; // Include the database connection

    if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $leeftijd = $_POST['leeftijd'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $herkomst = $_POST['herkomst'];
    $ticketid = $_POST['ticketid'];


    $sql = "INSERT INTO users (email, leeftijd, fname, lname, herkomst, ticket_id) VALUES ('$email', '$leeftijd', '$fname', '$lname', '$herkomst', '$ticketid')";

    if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
    } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    }}
    ?>

    <form action=”create.php” method="post">
    Email: <input type=”email” name=”email” placeholder="email"><br>
    Leeftijd: <input type=”text” name=”leeftijd” placeholder="leeftijd"><br>
    First Name: <input type=”text” name=”Firstname” placeholder="first name"><br>
    Last Name: <input type=”text” name=”Lastname” placeholder="last name"><br>
    Herkomst: <input type=”text” name=”Herkomst” placeholder="herkomst"><br>
    Ticket ID: <input type=”text” name=”TicketID” placeholder="ticket ID"><br>
    <input type=”submit” name=”submit” value=”Create User”>
    </form>