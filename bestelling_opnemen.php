<?php
require_once ('db/MysqliDb.php');
require_once ('config/config.php');
$db = MysqliDb::getInstance();

//RESERVERING OPHALEN VOOR HET BEKIJKEN OF DIE GENE VORIGE KEER GEBRUIKT HEEFT GEMAAKT VAN DE RESERVERING
if (isset($_GET['tafelnummer'])){
    $tafelnummer = $_GET['tafelnummer'];
    $reservering = $db->objectBuilder()->rawQuery("SELECT * FROM reservering WHERE tafel = $tafelnummer AND betaald = 0 LIMIT 1");
    if($reservering){
        $reservering = $reservering[0];
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
        Bestelling opnemen
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
            <?php if($reservering && $reservering->betaald === 0): ?>
            <div class="opnemen" style="color:white;">
                <h3 id="tafelnummertitle">tafelnummer: <?= (int) htmlspecialchars($_GET['tafelnummer']) ?></h3>
                <div class="gerechten">
                    <h3>Gerechten</h3>
                    <?php include('inc/gerechten.php')?>
                </div>
                <div class="dranken">
                    <h3>Dranken</h3>
                    <?php include('inc/dranken.php')?>
                </div>
            </div>
            <?php else: ?>
            <p>Er staat geen reservering open voor deze tafel.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>


