<?php
require_once ('db/MysqliDb.php');
require_once ('config/config.php');
$db = MysqliDb::getInstance();

//OPHALEN CATEGORIES EN SUBCATEGORIES
$categories = $db->objectBuilder()->get('gerecht');
$subcategories = $db->objectBuilder()->get('subgerecht');

// NIEUW MENU ITEM AANMAKEN
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

//    NIEUW MENU ITEM INVOEGEN IN DATABASE
    $id = $db->insert ('menuitem', $data);
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
        Nieuw menuitem toevoegen
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
            <form accept-charset="UTF-8" autocomplete="off" method="POST">
                <fieldset>
                    <legend>Nieuwe menuitem toevoegen:</legend>
                    <input type="hidden" name="nieuw" value="1" />

                    <br>
                    <label for="categorie">Kies een categorie:</label>
                    <select name="categorie" id="categorieSelect" onchange="getSubCategories(this.value)">
                        <option selected>Selecteer een optie</option>
                        <?php foreach ($categories as $categorie): ?>
                            <option value="<?= $categorie->gerechtcode ?>"><?= $categorie->gerecht ?></option>
                        <?php endforeach; ?>
                    </select><br><br>

                    <label for="subcategorie">Kies een subcategorie:</label>
                    <select name="subcategorie" id="subcategorie" class="subcategorie">
                        <option selected>Selecteer een optie</option>
                    </select><br><br>

                    <label for="gerechtnaam">Gerechtnaam:</label>
                    <input type="text" name="gerechtnaam" /><br><br>

                    <label for="prijs">Prijs:</label>
                    <input type="text" name="prijs" /><br><br>


                    <button type="submit" value="submit">Maak aan</button>
                </fieldset>
            </form>
        </div>
    </div>
    <script>
        // Via ajax subcategorien ophalen afhankelijk van de hoofdcategorie
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
