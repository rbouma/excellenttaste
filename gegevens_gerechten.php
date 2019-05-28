<?php
require_once ('db/MysqliDb.php');
require_once ('config/config.php');

//VERWIJDEREN VAN EEN MENU ITEM
if (isset($_POST['menuitem']) && isset($_POST['verwijder']) && (int) $_POST['verwijder'] === 1) {
    $menuitemcode = htmlspecialchars($_POST['menuitem']);

    $db->where ('menuitemcode', $menuitemcode);
    $db->delete ('menuitem');
}

//OPHALEN VAN GERECHTEN MET GERECHTCODE 1, 2, 3 of 5
$gerechten = $db->objectBuilder()->rawQuery('SELECT * FROM menuitem WHERE gerechtcode = 1 OR gerechtcode = 2 OR gerechtcode = 3 OR gerechtcode = 5')


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
        Gerechten
    </div>
</header>
<div class="container">
    <div class="menu">
        <?php include('inc/menu.php') ?>
    </div>
    <div class="submenu">
        <ul>
            <li><a href="gegevens_dranken.php">
                    <button>Dranken</button>
                </a></li>
            <li><a href="gegevens_gerechten.php">
                    <button>Gerechten</button>
                </a></li>
            <li><a href="gegevens_overzicht.php">
                    <button>Volledig overzicht</button>
                </a></li>
            <li><a href="menu_kaart.php">
                    <button>Menu Kaart</button>
                </a></li>
        </ul>
    </div>

    <div class="content">
        <div class="orders">
            <a href="gegevens_nieuw.php"><button>Nieuwe gerecht toevoegen</button></a>
            <ul>
                <?php if($gerechten): ?>
                    <?php foreach ($gerechten as $gerecht): ?>
                        <li>
                            <div class="bestellingen">
                                <div><?= $gerecht->menuitem. ' --- €'. $gerecht->prijs ?></div>
                                <div class="actions">
                                    <form method="get" action="gegevens_edit.php">
                                        <input type="hidden" name="menuitem" value="<?= (int) $gerecht->menuitemcode ?>">
                                        <input type="submit" value="Aanpassen">
                                    </form>
                                    <form method="post">
                                        <input type="hidden" name="verwijder" value="1">
                                        <input type="hidden" name="menuitem" value="<?= (int) $gerecht->menuitemcode ?>">
                                        <input type="submit" name="submit" value="Verwijder" onclick="javascript: return confirm('Weet je zeker dat je deze drank wilt verwijderen?')">
                                    </form>
                                </div>
                            </div>
                            <hr>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
