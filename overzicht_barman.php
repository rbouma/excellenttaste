<?php
require_once ('db/MysqliDb.php');
require_once ('config/config.php');

if (isset($_POST['bestellingscode'])) {
    $bestellingscode = htmlspecialchars($_POST['bestellingscode']);

    $data = Array (
        'klaar' => 1,
    );
//    UPDATE BESTELLING OM TE SERVEREN
    $db->where ('bestellingscode', $bestellingscode);
    $db->update ('bestelling', $data);
}

//OPHALEN VAN DE BESTELLINGEN
$bestellingen = $db->objectBuilder()->rawQuery("SELECT bestelling.*, menuitem.menuitem, menuitem.gerechtcode FROM bestelling INNER JOIN menuitem ON bestelling.menuitemcode = menuitem.menuitemcode WHERE DATE(bestelling.datum_tijd) = CURDATE() AND MINUTE(TimeDiff( NOW(), bestelling.datum_tijd )) < 420 ORDER BY bestelling.datum_tijd ASC");

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
    <div class="submenu">
        <ul>
            <li><a href="overzicht_kok.php">
                    <button>Overzicht kok</button>
                </a></li>
            <li><a href="overzicht_barman.php">
                    <button>Overzicht Barman</button>
                </a></li>
        </ul>
    </div>

    <div class="content">
        <div class="orders">
            <ul>
                <?php if (isset($bestellingen)) : ?>
                    <?php foreach ($bestellingen as $bestelling) :?>
                        <?php
                        $dt = new DateTime($bestelling->datum_tijd);
                        $today_dt = new DateTime();
                        $date = $dt->format('m-d-Y');
                        $today_date = $today_dt->format('m-d-Y');
                        $time = $dt->format('H:i');
                        ?>
                        <?php if ($today_date == $date) : ?>
                            <?php if ((int) $bestelling->gerechtcode === 4 ) : ?>
                                <?php if((int) $bestelling->klaar === 0): ?>
                                    <li>
                                        <div class="bestellingen">
                                            <div><?= $date. ' '. $time . ' - ' . $bestelling->menuitem . ' - Tafelnummer: ' . $bestelling->tafel ?></div>
                                            <div>
                                                <form method="post">
                                                    <input type="hidden" name="bestellingscode" value="<?= $bestelling->bestellingscode ?>">
                                                    <input type="submit" name="submit" value="Serveer">
                                                </form>
                                            </div>
                                        </div>
                                        <hr>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
