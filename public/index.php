<?php
    include "../includes/db.php";
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spik en Span Tickets bestellen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="taal-switcher">
    <button id="knop-nl" onclick="setTaal('nl')">🇳🇱 Nederlands</button>
    <button id="knop-li" onclick="setTaal('li')">Limburgs</button>
</div>

<h1 class="vertaal"
    data-nl="Tickets bestellen?"
    data-li="Kaartsjes bestèlle?"></h1>

<div class="PrimaryContainer">
    <div class="bestellen">
        <p class="vertaal"
            data-nl="Kaarten voor Spik en Span op 15 of 16 augustus op kasteel Limbricht."
            data-li="Kaartsjes veur Spik en Span op 15 of 16 augustus op kasteel Limbricht."></p>
        <a href="Orders.html" class="btn-primary">
            <button class="vertaal"
                data-nl="Snel tickets bestellen"
                data-li="Snel kaartsjes bestèlle"></button>
        </a>
    </div>
</div>

<br>

<div class="SecondaryContainer">
    <h2 class="vertaal"
        data-nl="Al een kaartje gekocht?"
        data-li="Al 'n kaartsje gekoch?"></h2>
    <div class="inloggen btn-secondary">
        <a href="#">
            <button class="vertaal"
                data-nl="Inloggen"
                data-li="Inlogge"></button>
        </a>
        <p class="klein vertaal"
            data-nl="Om je tickets te bekijken, downloaden of printen."
            data-li="Veur diene tickets te bekieke, downloade of printen."></p>
    </div>

    <h2 class="vertaal"
        data-nl="Medewerker?"
        data-li="Medewerker?"></h2>
    <div class="medewerker">
        <a href="#" class="btn-secondary">
            <button class="vertaal"
                data-nl="Inloggen medewerker"
                data-li="Inlogge medewerker"></button>
        </a>
    </div>
</div>

<!-- Script inladen -->
<script src="../includes/translate.js"></script>

</body>

<ui:include src="../includes/footer.html" />
</html>