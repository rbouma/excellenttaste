<?php
require_once ('MysqliDb.php');
require_once ('../config/config.php');

//OPHALEN VAN ALLE SUBCATEGORIEN AFHANKELIJK VAN DE HOOFD CATEGORIE

if(isset($_GET['q'])){
    $categorieCode = htmlspecialchars($_GET['q']);

    $db->where('gerechtcode', (int) $categorieCode);
    $subCategorien = $db->objectBuilder()->get('subgerecht');

    foreach ($subCategorien as $subCategorie) {
        echo '<option value="'.$subCategorie->subgerechtcode .'">'. $subCategorie->subgerecht .'</option>';
    }
}



