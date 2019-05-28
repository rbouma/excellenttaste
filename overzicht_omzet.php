<?php
require_once ('db/MysqliDb.php');
require_once ('config/config.php');

//OPHALEN VAN DE BESTELLINGEN
$bestellingen = $db->objectBuilder()->rawQuery("SELECT bestelling.*, menuitem.menuitem, menuitem.gerechtcode FROM bestelling INNER JOIN menuitem ON bestelling.menuitemcode = menuitem.menuitemcode WHERE klaar = 1 ORDER BY bestelling.datum_tijd ASC");

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ExcellentTaste</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header>
    <div class="title">
        Overzichten
    </div>
</header>
<div class="container">
    <div class="menu">
        <?php include('inc/menu.php') ?>
    </div>

    <div class="content">
        <div class="orders">
            <ul>
                <?php if (isset($bestellingen)) : ?>
                <?php $totaal = 0.0 ?>
                    <?php foreach ($bestellingen as $bestelling) :?>
                        <?php
                        $totaal += (float) $bestelling->prijs;

                        ?>
                    <?php endforeach; ?>
                <h1>Totale omzet: <?php echo '€ '. (float) $totaal ?> </h1>

                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
