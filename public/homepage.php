<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="style.css">
</head>

<header class="sticky-footer">
    <div>
        <div class="logo">
            <h1>
                <img src="../img/Spik en Span.png" alt="Spik en Span" height="50px">
                <span class="vertaal"
                    data-nl="Welkom bij Spik en Span, verzorgt door kasteel Limbricht"
                    data-li="Welkom bij Spik en Span, verzörg door kasteel Limbricht"></span>
                <img src="../img/kasteel limbricht.svg" alt="Kasteel Limbricht" height="50px">
            </h1>
        </div>

        <div class="header-buttons">
            <h1>
                <a href="../inlog_page/login.php" class="btn-primary">
                    <button class="vertaal" data-nl="Inloggen" data-li="Inlogge"></button>
                </a>
                <a href="festivals.php" class="btn-primary">
                    <button class="vertaal" data-nl="Tickets kopen" data-li="Kaartsjes kope"></button>
                </a>
                <a href="#" class="btn-primary">
                    <button class="vertaal" data-nl="Medewerkers" data-li="Medewerkers"></button>
                </a>
            </h1>
        </div>

        <!-- Taalwisselaar -->
        <div class="taal-switcher">
            <button id="knop-nl" onclick="setTaal('nl')">🇳🇱 Nederlands</button>
            <button id="knop-li" onclick="setTaal('li')">Limburgs</button>
        </div>
    </div>
    <hr>
</header>

<body>

    <img src="../img/kasteel limbricht.svg" alt="Kasteel Limbricht" height="100px">
    <div class="omschrijving">
        <h1 class="vertaal"
            data-nl="Sfeervol genieten"
            data-li="Sfeervol geniete"></h1>
        <hr>
        <h2 style="color: white; text-align: center;" class="vertaal"
            data-nl="op kasteel Limbricht"
            data-li="op kasteel Limbricht"></h2>
        <p style="color: white;" class="vertaal"
            data-nl="Het kasteelcomplex is gelegen binnen een brede omgrachting en bestaat uit een burcht en een kasteelboerderij als voorburcht. De burcht is een geheel onderkelderd viervleugelig vierkant gebouw rond een kleine binnenplaats. Het is een zeldzaam voorbeeld van een mottekasteel, een kasteel op een kunstmatig opgeworpen heuvel. De motte met daarop de burcht ligt als een eiland in de omgrachting."
            data-li="Het kasteelcomplex ligt binne 'n breede omgrachting en besteit oet 'ne burcht en 'ne kasteelboerderij as veurburcht. De burcht is 'n geheel onderkelderd viervleugeleg vierkaant gebouw rond 'n kleine binneplaots. Het is 'n zeldzaam veurbeeld van 'ne mottekasteel, 'ne kasteel op 'ne kunstmatig opgeworpe heuvel. De motte mit d'r op de burcht ligt as 'n eiland in de omgrachting."></p>
        <div><img src="../img/kasteel.jpg" alt="Kasteel Limbricht" class="img"></div>
    </div>

    <img src="../img/Spik en Span.png" alt="Spik en Span" style="display: block; margin-left: auto; margin-right: auto;">
    <div class="omschrijving" style="background-color: blue;">
        <h2 style="text-align: center;" class="vertaal"
            data-nl="Wie zijn ze nou eigenlijk?"
            data-li="Wie zeen ze nou eiges?"></h2>
        <p style="color: yellow;" class="vertaal"
            data-nl="Jo Huijnen en Niek Dirkx, oftewel Spik en Span, zijn de Kampioene van de Nach. Met hun vijf LVK-overwinningen zijn zij het meest succesvolle vastelaoves-duo van het afgelopen decennium in Limburg. Spik en Span is als vastelaoves-duo actief sinds 2010 en vanaf het prille begin vaste finalist van het Limburgs Vastelaoves Leedjes Konkoer. Hun optredens zijn vol energie, met nummers die bekend zijn van Noord tot Zuid in de provincie. Een optreden van Spik en Span staat garant voor entertainment, samen zingen en samen beleven. In 2020 won het duo wederom het LVK met het nummer 'Vrunj tot de allerbeste runj'."
            data-li="Jo Huijnen en Niek Dirkx, oftewel Spik en Span, zeen de Kampioene van de Nach. Mit heur vief LVK-overwinninger zeen zie het meiste succesvolle vastelaoves-duo van het ofgelope decennium in Limburg. Spik en Span is as vastelaoves-duo actief sedert 2010 en vanof het prille begin vaste finalist van het Limburgs Vastelaoves Leedjes Konkoer. Heur optreeje zeen vol energie, mit nummers die bekend zeen van Noord tot Zuid in de provincie. In 2020 won het duo wederom het LVK mit het nummer 'Vrunj tot de allerbeste runj'."></p>
        <div class="image">
            <img src="../img/spik en span.jpg" alt="Spik en Span" class="img">
        </div>
    </div>

</body>

<footer>
    <?php
    include "../includes/footer.html";
    ?>
</footer>

<script src="../includes/translate.js" defer></script>

</html>