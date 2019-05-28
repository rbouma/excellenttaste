<?php
require_once ('db/MysqliDb.php');
require_once ('config/config.php');
$db = MysqliDb::getInstance();

//MAAK NIEUWE RESERVERING AAN
if (isset($_POST['nieuw']) && (int) $_POST['nieuw'] === 1) {
    $datum = htmlspecialchars($_POST['datum']);
    $tijd = htmlspecialchars($_POST['tijd']);
    $datetime = date('Y-m-d H:i', strtotime("$datum $tijd"));
    $klantcode = 0;

//    klanten
    $klantdb = (array) [
        'klantnaam' => htmlspecialchars($_POST['naam']),
        'telefoon' => htmlspecialchars($_POST['telefoon']),
    ];

//    CHECK OF KLANT AL IN DE DATABASE STAAT
    $klanten = $db->objectBuilder()->rawQuery('SELECT * FROM klant WHERE telefoon = '.$klantdb["telefoon"].' LIMIT 1');
    if($klanten) {
        $db->where ('telefoon', $klantdb['telefoon']);
        $klant = $db->getOne('klant');
        $klantcode = $klant['klantcode'];
    }

//    BESTAAT NIET IN KLANT TABEL MAAK NIEUWE AAN
    if ($klantcode === 0) {
        $id = $db->insert ('klant', $klantdb);
        if($id){
            $klantcode = $id;
        }
    }

//    MAAK NIEUWE RESERVERING AAN
    $reserveringdb = (array) [
        'aantalpersonen' => htmlspecialchars($_POST['personen']),
        'datum_tijd' => $datetime,
        'klantcode' => $klantcode,
        'opmerkingen' => htmlspecialchars($_POST['opmerkingen'])
    ];

//    VOEG RESERVERING TOE AAN DATABASE
    $reservering = $db->insert ('reservering', $reserveringdb);
    if($reservering){
        echo '<script>window.location.replace("/reservering_overzicht.php");</script>';
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
            <?php

//            KIJK OF KLANT IN HET VERLEDEN GEEN GEBRUIK HEEFT GEMAAKT VAN DE RESERVERING

            if (isset($_POST['check']) && (int) $_POST['check'] === 1) {

                $klantcode = 0 ;
                $telefoon = htmlspecialchars($_POST['telefoon']);
                $klanten = $db->objectBuilder()->rawQuery('SELECT * FROM klant WHERE telefoon = '.$telefoon.' LIMIT 1');
                if($klanten) {
                    $db->where ('telefoon', $telefoon);
                    $klant = $db->getOne('klant');
                    $klantcode = $klant['klantcode'];
                    $klantnaam = $klant['klantnaam'];
                }
                $reserveringenTabel = $db->objectBuilder()->rawQuery('SELECT * FROM reservering WHERE klantcode = '.$klantcode.' ORDER BY datum_tijd DESC LIMIT 1');

                if($reserveringenTabel) {
                    $newDateTime = new DateTime();
                    $dateNow = $newDateTime->format('Y-m-d');
                    foreach ($reserveringenTabel as $item) {
                        $dateReservering = new DateTime($item->datum_tijd);
                        $dateReservering = $dateReservering->format('Y-m-d');

//                        GEEF MELDING WANNEER KLANT VORIGE KEER NIET GEBRUIKT HEEFT GEMAAKT VAN RESERVERING
                        if($dateReservering < $dateNow && $item->betaald === 0){
                            echo "<p style='color:red'>Deze persoon heeft in het verleden geen gebruik gemaakt van de reservering!</p>";
                        } else {
                            echo "<p style='color:green'>Alles OK</p>";
                        }
                    }
                } else {
                    echo "<p style='color:green'>Nog geen eerdere gemaakte reserveringen</p>";
                }
            }
            ?>
            <form accept-charset="UTF-8" autocomplete="off" method="POST">
                <fieldset>
                    <legend>Check reservering:</legend>
                    <label for="telefoon">Telefoon</label><br />
                    <input type="hidden" name="check" value="1" />
                    <input name="telefoon" type="text" placeholder="0612345678" value="<?php if(isset($telefoon)){ echo $telefoon; }else{echo '';} ?>" required /> <br /><br />

                    <button type="submit" value="submit">Check Reservering</button>
                </fieldset>
            </form>
            <form accept-charset="UTF-8" autocomplete="off" method="POST">
                <fieldset>
                    <legend>Nieuwe reservering:</legend>
                    <input type="hidden" name="nieuw" value="1" />
                    <label for="naam">Naam klant</label><br />
                    <input name="naam" type="text" placeholder="john" value="<?php if(isset($klantnaam)){ echo $klantnaam; }else{echo '';} ?>" required /> <br /><br />

                    <label for="telefoon">Telefoon</label><br />
                    <input name="telefoon" type="text" placeholder="0612345678" value="<?php if(isset($telefoon)){ echo $telefoon; }else{echo '';} ?>" required /> <br /><br />

                    <label for="personen">Aantal personen</label><br />
                    <input name="personen" type="number" min="1" max="99" step="1" value="1" required/> <br /><br />

                    <label for="datum">Datum</label><br />
                    <input name="datum" type="date" value="<?= date('Y-m-d') ?>" required/> <br /><br />

                    <label for="tijd">Tijd</label><br />
                    <input name="tijd" type="time" value="<?= date('H:i') ?>" required/> <br /><br />

                    <label for="opmerkingen">Opmerkingen: bijv. allergieën</label><br />
                    <textarea name="opmerkingen" cols="40" rows="5" value="<?= date('H:i') ?>" required></textarea> <br /><br />

                    <button type="submit" value="submit">Plaats reservering</button>
                </fieldset>
            </form>
        </div>
    </div>
</div>
</body>
</html>
