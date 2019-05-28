<?php
require_once ('db/MysqliDb.php');
require_once ('config/config.php');
$db = MysqliDb::getInstance();

//VERANDER RESERVERING

if (isset($_POST['verander']) && (int) $_POST['verander'] === 1) {
    $klantcode = htmlspecialchars($_POST['klantcode']);
    $reserveringscode = htmlspecialchars($_POST['reserveringscode']);
    $datum = htmlspecialchars($_POST['datum']);
    $tijd = htmlspecialchars($_POST['tijd']);
    $datetime = date('Y-m-d H:i', strtotime("$datum $tijd"));

    $klant = Array (
        'klantnaam' => htmlspecialchars($_POST['naam']),
        'telefoon' => htmlspecialchars($_POST['telefoon'])
    );

    $reservering = Array (
        'aantalpersonen' => htmlspecialchars($_POST['personen']),
        'datum_tijd' => $datetime,
        'klantcode' => $klantcode
    );

//    UPDATE IN DATABASE
    $db->where ('klantcode', $klantcode);
    $db->update ('klant', $klant);

    $db->where ('reserveringscode', $reserveringscode);
    $updated = $db->update ('reservering', $reservering);

    if($updated){
        echo '<script>window.location.replace("/reservering_overzicht.php");</script>';
    }

}

//VERKRIJG DE RESERVERING INFORMATIE
if (isset($_GET['reserveringscode'])) {
    $reserveringscode = $_GET['reserveringscode'];

    $reservering = $db->objectBuilder()->rawQuery("SELECT * FROM reservering INNER JOIN klant ON reservering.klantcode = klant.klantcode WHERE reserveringscode = $reserveringscode LIMIT 1");
    $reservering = $reservering[0];
    $dt = new DateTime($reservering->datum_tijd);
    $datum = $dt->format('Y-m-d');
    $tijd = $dt->format('H:i');
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
            <form accept-charset="UTF-8" autocomplete="off" method="POST">
                <fieldset>
                    <legend>Verander reservering:</legend>
                    <input type="hidden" name="verander" value="1" />
                    <input type="hidden" name="klantcode" value="<?= $reservering->klantcode ?>" />
                    <input type="hidden" name="reserveringscode" value="<?= $reservering->reserveringscode ?>" />
                    <label for="naam">Naam klant</label><br />
                    <input name="naam" type="text" value="<?= $reservering->klantnaam ?>" /> <br /><br />

                    <label for="telefoon">Telefoon</label><br />
                    <input name="telefoon" type="text" value="<?= $reservering->telefoon ?>" /> <br /><br />

                    <label for="personen">Aantal personen</label><br />
                    <input name="personen" type="number" min="1" max="99" step="1" value="<?= $reservering->aantalpersonen ?>" /> <br /><br />

                    <label for="datum">Datum</label><br />
                    <input name="datum" type="date" value="<?= $datum ?>" /> <br /><br />

                    <label for="tijd">Tijd</label><br />
                    <input name="tijd" type="time" value="<?= $tijd ?>" /> <br /><br />

                    <button type="submit" value="submit">Verander reservering</button>
                </fieldset>
            </form>
        </div>
    </div>
</div>
</body>
</html>
<?php } ?>
