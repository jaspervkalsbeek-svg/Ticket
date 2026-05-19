<?php
session_start();
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="form-box active">
        <form action="login_register.php" method="post">
            <h2>Inloggen</h2>
            <?php if ($error): ?>
                <p class="error-message"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <input type="email" name="email" placeholder="E-mailadres" required>
            <input type="text" name="ticket_id" placeholder="Ticket ID (bv. D624F818CF32)" required>
            <button type="submit" name="login">Inloggen</button>
            <p>Nog geen kaartjes? <a href="../public/festivals.php">Koop ze hier!</a></p>
        </form>
    </div>
</body>
</html>
