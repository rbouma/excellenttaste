<?php
require_once ('db/MysqliDb.php');
require_once ('config/config.php');

//VERWIJDER RESERVERING
if (isset($_POST['reserveringscode']) && isset($_POST['verwijder'])) {
    $reserveringscode = htmlspecialchars($_POST['reserveringscode']);
    $klantcode = htmlspecialchars($_POST['klantcode']);

    $db->where ('reserveringscode', $reserveringscode);
    $db->delete ('reservering');

}

//WIJS EEN TAFEL TOE
if (isset($_POST['reserveringscode']) && isset($_POST['wijstafeltoe'])) {
    $reserveringscode = htmlspecialchars($_POST['reserveringscode']);
    $klantcode = htmlspecialchars($_POST['klantcode']);
    $tafelnummer = htmlspecialchars($_POST['tafelnummer']);

    $data = Array (
        'tafel' => (int) $tafelnummer,
    );
    $db->where ('reserveringscode', (int) $reserveringscode);
    $db->where ('klantcode', (int) $klantcode);
    $db->update ('reservering', $data);

}

//HAAL ALLE RESERVERINGEN OP VOOR VANDAAG
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
                <?php if (isset($reserveringen)) : ?>
                    <?php foreach ($reserveringen as $reservering) :?>
                        <?php if($reservering->betaald === 0): ?>
                            <?php
                            $dt = new DateTime($reservering->datum_tijd);
                            $today_dt = new DateTime();
                            $date = $dt->format('m-d-Y');
                            $today_date = $today_dt->format('m-d-Y');
                            $time = $dt->format('H:i');
                            ?>
                            <?php if ($today_date == $date) : ?>
                            <li>
                                <div class="bestellingen">
                                    <div><?= $reservering->datum_tijd . ' - naam: ' . $reservering->klantnaam . ' - telefoon: ' . $reservering->telefoon ?></div>
                                    <div class="actions">

<!--                                        WIJS EEN NIEUWE TAFEL TOE-->

                                        <?php if(!isset($_POST['tafel'])): ?>
                                            <?php if ((int) $reservering->tafel !== 0): ?>
                                                <div style="margin-right: 50px;">Tafelnummer: <?= $reservering->tafel ?> </div>
                                            <?php endif; ?>
                                        <form method="post">
                                            <input type="submit" name="tafel" value="Tafel toewijzen">
                                        </form>
                                        <?php else: ?>
                                            <form method="post">
                                                <input type="hidden" name="reserveringscode" value="<?= $reservering->reserveringscode ?>">
                                                <input type="hidden" name="klantcode" value="<?= $reservering->klantcode ?>">
                                                <label for="tafelnummer">Tafelnummer:</label>
                                                <input type="number" name="tafelnummer" min="1" max="11" value="<?= $reservering->tafel ?>" />
                                                <input type="submit" name="wijstafeltoe" value="Tafel toewijzen" />
                                            </form>
                                        <?php endif; ?>
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
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
