<?php
$allowed_lang = ['nl', 'li'];
$taal = in_array($_GET['lang'] ?? '', $allowed_lang) ? $_GET['lang'] : 'nl';
$t = include "../lang/{$taal}.php";
?>

<!DOCTYPE html>
<html lang="<?= $taal ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="style.css">    
</head>

<body>

    <header class="sticky-footer">
        <div>
            <div class="header-buttons">
                <img src="../img/Spik en Span.png" alt="Spik en Span" height="50px">
                <h1><?= $t['welkom'] ?></h1>
                <img src="../img/kasteel limbricht.svg" alt="Kasteel Limbricht" height="50px">
            </div>

            <div class="header-buttons">
                <a href="../inlog_page/login.php" class="btn-primary"><button><?= $t['inloggen'] ?></button></a>
                <a href="festivals.php" class="btn-primary"><button><?= $t['tickets'] ?></button></a>
                <a href="#" class="btn-primary"><button><?= $t['medewerkers'] ?></button></a>
            </div>

            <div class="taal-keuze">
                <a href="?lang=nl">🇳🇱 NL</a>
                <a href="?lang=li">🇱🇮 LI</a>
            </div>
        </div>
    </header>

    <!-- Kasteel sectie -->
    <img src="../img/kasteel limbricht.svg" alt="Kasteel Limbricht" height="100px">
    <div class="omschrijving">
        <h1><?= $t['kasteel_titel'] ?></h1>
        <hr>
        <h2><?= $t['kasteel_subtitel'] ?></h2>
        <p><?= $t['kasteel_tekst'] ?></p>
        <div>
            <img src="../img/kasteel.jpg" alt="Kasteel Limbricht" class="img">
        </div>
    </div>

    <!-- Spik en Span sectie -->
    <img src="../img/Spik en Span.png" alt="Spik en Span" style="display: block; margin: 0 auto;">
    <div class="omschrijving spikspan">
        <h2><?= $t['spikspan_titel'] ?></h2>
        <p><?= $t['spikspan_tekst'] ?></p>
        <div class="image">
            <img src="../img/spik en span.jpg" alt="Spik en Span" class="img">
        </div>
    </div>

    <footer>
        <?php include "../includes/footer.html"; ?>
    </footer>

</body>
</html>