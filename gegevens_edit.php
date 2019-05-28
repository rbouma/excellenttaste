<?php
require_once ('db/MysqliDb.php');
require_once ('config/config.php');
$db = MysqliDb::getInstance();

if(isset($_GET['menuitem'])){
    $menuItemID = htmlspecialchars($_GET['menuitem']);
}

//ALLE CATEGORIEEN, SUB CATEGORIEN EN GERECHTEN OPHHALEN
$categories = $db->objectBuilder()->get('gerecht');
$subcategories = $db->objectBuilder()->get('subgerecht');
$gerecht = $db->objectBuilder()->rawQuery("SELECT * FROM menuitem INNER JOIN subgerecht ON menuitem.subgerechtcode = subgerecht.subgerechtcode WHERE menuitemcode = $menuItemID LIMIT 1");

if ($gerecht) {
   $gerecht = $gerecht[0];
}

if (isset($_POST['nieuw']) && (int) $_POST['nieuw'] === 1) {
    $categorieID = htmlspecialchars($_POST['categorie']);
    $subCategorieID = htmlspecialchars($_POST['subcategorie']);
    $gerechtNaam = htmlspecialchars($_POST['gerechtnaam']);
    $gerechtPrijs = htmlspecialchars($_POST['prijs']);

    $data = (array) [
        'gerechtcode' => htmlspecialchars($_POST['categorie']),
        'subgerechtcode' => htmlspecialchars($_POST['subcategorie']),
        'menuitem' => htmlspecialchars($_POST['gerechtnaam']),
        'prijs' => htmlspecialchars($_POST['prijs'])
    ];

//    MENU ITEM WIJZIGEN
    $db->where('menuitemcode', $menuItemID);
    $id = $db->update ('menuitem', $data);
    if($id){
        echo '<script>window.location.replace("/gegevens_overzicht.php");</script>';
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
       Gerecht aanpassen
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
        <?php if(isset($_GET['menuitem'])): ?>
        <?php $menuItemID = htmlspecialchars($_GET['menuitem']); ?>
        <div class="orders">
            <form accept-charset="UTF-8" autocomplete="off" method="POST">
                <fieldset>
                    <legend>Nieuwe drank toevoegen:</legend>
                    <input type="hidden" name="nieuw" value="1" />

                    <br>
                    <label for="categorie">Kies een categorie:</label>
                    <select name="categorie" id="categorieSelect" onchange="getSubCategories(this.value)">
                        <option>Selecteer een optie</option>
                        <?php foreach ($categories as $categorie): ?>
                            <?php if ((int) $categorie->gerechtcode === (int) $gerecht->gerechtcode): ?>
                                <option value="<?= $categorie->gerechtcode ?>" selected="selected"><?= $categorie->gerecht ?></option>
                            <?php else: ?>
                                <option value="<?= $categorie->gerechtcode ?>"><?= $categorie->gerecht ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select><br><br>

                    <label for="subcategorie">Kies een subcategorie:</label>
                    <select name="subcategorie" id="subcategorie" class="subcategorie">
                        <option value="<?= $gerecht->subgerechtcode ?>"><?= $gerecht->subgerecht ?></option>
                    </select><br><br>

                    <label for="gerechtnaam">Gerechtnaam:</label>
                    <input type="text" name="gerechtnaam" value="<?= $gerecht->menuitem ?>" /><br><br>

                    <label for="prijs">Prijs:</label>
                    <input type="text" name="prijs" value="<?= $gerecht->prijs ?>"/><br><br>


                    <button type="submit" value="submit">Wijzigen</button>
                </fieldset>
            </form>
        </div>
        <?php endif; ?>
    </div>
    <script>
        // VERKRIJG SUBCATEGORIEN OP BASIS VAN HOOFDCATEGORIE
        function getSubCategories(categorie) {
            if (categorie == "") {
                document.getElementById("subcategorie").innerHTML = "<option>Please choose from above</option>";
                return;
            } else {
                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("subcategorie").innerHTML = xmlhttp.responseText;
                    }
                };
                xmlhttp.open("GET","/db/xhr.php?q="+categorie,true);
                xmlhttp.send();
            }
        }
    </script>
</div>
</body>
</html>
