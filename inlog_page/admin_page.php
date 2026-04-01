<?php

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="login.css">
</head>
<body style="background: #fff;">
    
    <div class="box">
        <h1>welcome, <span><?= $_SESSION['name']; ?></span></h1>
        <p>this is an <span> admin </span> page</p>
        <button onclick="window.location.href='logout.php'">logout</button>
    </div>

</body>

</html>