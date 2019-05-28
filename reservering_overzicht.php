<?php
require_once ('db/MysqliDb.php');
require_once ('config/config.php');

//VERWIJDEREN VAN EEN RERSERVERING
if (isset($_POST['reserveringscode']) && isset($_POST['klantcode']) && isset($_POST['verwijder'])) {
    $reserveringscode = htmlspecialchars($_POST['reserveringscode']);
    $klantcode = htmlspecialchars($_POST['klantcode']);

    $db->where ('reserveringscode', $reserveringscode);
    $db->delete ('reservering');

}

//HAAL ALLE RESERVERINGEN OP
$reserveringen = $db->objectBuilder()->rawQuery("SELECT * FROM reservering INNER JOIN klant ON reservering.klantcode = klant.klantcode ORDER BY datum_tijd DESC");

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
            <li><a href="reservering_overzicht.php">
                    <button>Reserveringen Overzicht</button>
                </a></li>
            <li><a href="reservering_vandaag.php">
                    <button>Reserveringen vandaag</button>
                </a></li>
            <li><a href="reservering_nieuw.php">
                    <button>Nieuwe reservering</button>
                </a></li>
        </ul>
    </div>

    <div class="content">
        <div class="reserveringen">
            <ul>

<!--                RESERVERINGEN DUIDELIJK WEERGEVEN-->
                <?php if (isset($reserveringen)) : ?>
                    <?php foreach ($reserveringen as $reservering) :?>
                    <?php if($reservering->betaald === 0): ?>
                                    <li>
                                        <div class="item">
                                            <div><?= $reservering->datum_tijd . ' - naam: ' . $reservering->klantnaam . ' - telefoon: ' . $reservering->telefoon ?></div>
                                            <div class="actions">
                                                <form action="reservering_verander.php" method="get">
                                                    <input type="hidden" name="reserveringscode" value="<?= $reservering->reserveringscode ?>">
                                                    <input type="hidden" name="klantcode" value="<?= $reservering->klantcode ?>">
                                                    <input type="submit" name="verander" value="Verander">
                                                </form>
                                                <form method="post">
                                                    <input type="hidden" name="reserveringscode" value="<?= $reservering->reserveringscode ?>">
                                                    <input type="hidden" name="klantcode" value="<?= $reservering->klantcode ?>">
                                                    <input type="submit" name="verwijder" value="Verwijder">
                                                </form>
                                            </div>
                                        </div>
                                        <hr>
                                    </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
