<?php
//SELECT *, count(menuitemcode) AS aantal_new FROM `taste`.`bestelling` WHERE MINUTE(TimeDiff( NOW(), datum_tijd )) < 30 GROUP BY menuitemcode ORDER BY datum_tijd DESC
require_once ('db/MysqliDb.php');
include_once('config/config.php');
$db = MysqliDb::getInstance();

if (isset($_GET['tafelnummer'])) {
    $tafelnummer = htmlspecialchars($_GET['tafelnummer']);

//    OPHALEN VAN DE BESTELLINGEN MET RESERVERING
    $gerechten = $db->objectBuilder()->rawQuery("
        SELECT bestelling.*, count(bestelling.menuitemcode) AS aantal_new, menuitem.menuitem, reservering.* FROM bestelling 
        INNER JOIN menuitem ON bestelling.menuitemcode = menuitem.menuitemcode
        INNER JOIN reservering ON bestelling.tafel = reservering.tafel
        WHERE reservering.betaald = 0
        AND DATE(bestelling.datum_tijd) = CURDATE() 
        AND bestelling.datum_tijd > reservering.datum_tijd
        AND bestelling.tafel = $tafelnummer
        AND klaar = 1 
        GROUP BY bestelling.menuitemcode 
        ORDER BY bestelling.datum_tijd DESC
    ");

//    NIEUWE DATETIME OBJECT
    $dt = new DateTime();
    $date = $dt->format('m-d-Y');
    $time = $dt->format('H:i');

    if (isset($_POST['betalen']) && (int) $_POST['betalen'] === 1) {
        $wijze = htmlspecialchars($_POST['wijze']);
        $bonID = 0;

//        WIJZE VAN BETALING
        if((int) $wijze === 1){
            $wijze = 'PIN';
        }elseif ((int) $wijze === 2) {
            $wijze = 'CONTANT';
        }

        $bonData = Array (
                "datum_tijd" => $dt->format('Y-m-d H:i:s'),
                "tafel" => $tafelnummer,
                "wijze" => $wijze
        );

//        INVOEGEN IN BON TABEL
        $id = $db->insert ('bon', $bonData);
        if($id){
            $bonID = $id;
        }

        if (isset($bonID)){
            $reserveringdb = (array) [
                'betaald' => 1,
                'boncode' => $bonID
            ];
            $db->where('betaald', 0);
            $db->where('tafel', $tafelnummer);
            $db->update('reservering', $reserveringdb, 1);

            echo '<h2 style="color: green">Heeft betaald</h2>';
            echo '<meta http-equiv="refresh" content="3" />';
        }
    }
}

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
        Bon maken voor klant
    </div>
</header>
<div class="container">
    <div class="menu">
        <?php include('inc/menu.php') ?>
    </div>
    <div class="submenu">
        <ul>
            <li><a href="bestelling_opnemen.php">
                    <button>Bestelling opnemen</button>
                </a></li>
            <li><a href="bon.php">
                    <button>Bon maken</button>
                </a></li>
        </ul>
    </div>
    <div class="tafelmenu">
        <?php include('inc/tafelnummers.php') ?>
    </div>

    <div class="content">
        <?php if(isset($_GET['tafelnummer'])): ?>
            <?php if($gerechten): ?>
            <div class="orders">
                <form accept-charset="UTF-8" autocomplete="off" method="POST" class="betalingswijze">
                    <input type="hidden" name="betalen" value="1" />
                    <input type="hidden" name=""

                    <label for="wijze">Wijze van betaling: </label>
                    <select name="wijze" required>
                        <option value="1" selected>Pin</option>
                        <option value="2">Contant</option>
                    </select>

                    <button type="submit" value="submit">Bon Betalen</button>
                </form>
                <div class="top-info">
                    <p>Tafel: <?= (int) htmlspecialchars($_GET['tafelnummer']) ?></p>
                    <p>Datum: <?= $date ?></p>
                    <p>Tijd: <?= $time ?></p>
                </div>
                <br>
                <div class="bon">
                    <ul>

                        <?php
                        if (isset($gerechten)) :
                            $total = 0;
                            foreach ($gerechten as $gerecht) :?>
                            <?php if ((int) $gerecht->klaar === 1): ?>
                                    <?php
                                        if ((int) $gerecht->aantal_new > 1) {
                                            $total += (float) ((int) $gerecht->aantal_new * (float) $gerecht->prijs);
                                        } else {
                                            $total += $gerecht->prijs;
                                        }
                                    ?>
                                    <li>
                                        <p><?= $gerecht->aantal_new. 'x '. $gerecht->menuitem . ' €' . $gerecht->prijs. ' - €'. number_format(((int) $gerecht->aantal_new * (float) $gerecht->prijs), 2, ',', '.')?></p>
                                    </li>
                                <?php endif; ?>
                                <?php endforeach; ?>
                                <br>
                                    <p>Totaal: €<?= number_format($total, 2, ',', '.') ?></p>
                                    <p>Betaald: €<?= number_format(0, 2, ',', '.') ?></p>
                                    <p>Terug: €<?= number_format(0, 2, ',', '.') ?></p>

                           <?php endif; ?>
                    </ul>
                </div>
            </div>
            <?php else: ?>
            <p>Er is geen openstaande reservering of de gerechten zijn nog niet geserveerd aan de tafel.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
