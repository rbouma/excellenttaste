<?php
require_once ('db/MysqliDb.php');
require_once ('config/config.php');

if(isset($_POST['soort'])){
    $soort = htmlspecialchars($_POST['soort']);

//    ALLE MENU ITEMS OPHALEN

    $menuItems = $db->objectBuilder()->rawQuery('
        SELECT * FROM menuitem
        INNER JOIN gerecht ON menuitem.gerechtcode = gerecht.gerechtcode
        INNER JOIN subgerecht ON menuitem.subgerechtcode = subgerecht.subgerechtcode
    ');
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

    <form method="POST">
        <select name="soort" id="soortkaartt">
            <option value="1">Dranken kaart</option>
            <option value="2">Gerechten kaart</option>
        </select>
        <input type="submit" value="Aanpassen">
    </form>
    <div class="content">
        <?php if(isset($_POST['soort'])): ?>
            <ul>
                <?php if ((int) $soort === 1): ?>
<!--                DRANKEN KAARTT-->
                <h1>Dranken</h1>
                <h3>Warme dranken</h3>
                <?php foreach ($menuItems as $menuItem): ?>
                    <?php if ((int) $soort === 1): ?>
                        <?php if($menuItem->gerechtcode === 4): ?>
                            <?php if($menuItem->subgerechtcode === 5): ?>
                                <li><?= $menuItem->menuitem ?> - €<?= $menuItem->prijs ?></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <h3>Bieren</h3>
                <?php foreach ($menuItems as $menuItem): ?>
                    <?php if ((int) $soort === 1): ?>
                        <?php if($menuItem->gerechtcode === 4): ?>
                            <?php if($menuItem->subgerechtcode === 7): ?>
                                <li><?= $menuItem->menuitem ?> - €<?= $menuItem->prijs ?></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <h3>Huiswijnen</h3>
                <?php foreach ($menuItems as $menuItem): ?>
                    <?php if ((int) $soort === 1): ?>
                        <?php if($menuItem->gerechtcode === 4): ?>
                            <?php if($menuItem->subgerechtcode === 8): ?>
                                <li><?= $menuItem->menuitem ?> - €<?= $menuItem->prijs ?></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <h3>Frisdranken</h3>
                <?php foreach ($menuItems as $menuItem): ?>
                    <?php if ((int) $soort === 1): ?>
                        <?php if($menuItem->gerechtcode === 4): ?>
                            <?php if($menuItem->subgerechtcode === 9): ?>
                                <li><?= $menuItem->menuitem ?> - €<?= $menuItem->prijs ?></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php endif; ?>

<!--                EINDE DRANKEN KAART-->



<!--                GERECHTEN KAARRT-->
                <?php if ((int) $soort === 2): ?>
                <h1>Voorgerechten</h1>
                <h3>Warme voorgerechten</h3>
                <?php foreach ($menuItems as $menuItem): ?>
                    <?php if ((int) $soort === 2): ?>
                        <?php if($menuItem->gerechtcode === 1): ?>
                            <?php if($menuItem->subgerechtcode === 1): ?>
                            <li><?= $menuItem->menuitem ?> - €<?= $menuItem->prijs ?></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <h3>Koude voorgerechten</h3>
                <?php foreach ($menuItems as $menuItem): ?>
                    <?php if ((int) $soort === 2): ?>
                        <?php if($menuItem->gerechtcode === 1): ?>
                            <?php if($menuItem->subgerechtcode === 2): ?>
                                <li><?= $menuItem->menuitem ?> - €<?= $menuItem->prijs ?></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <h1>Hoofdgerechten</h1>

                <h3>Visgerechten</h3>
                <?php foreach ($menuItems as $menuItem): ?>
                    <?php if ((int) $soort === 2): ?>
                        <?php if($menuItem->gerechtcode === 2): ?>
                            <?php if($menuItem->subgerechtcode === 3): ?>
                                <li><?= $menuItem->menuitem ?> - €<?= $menuItem->prijs ?></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <h3>Vleesgerechten</h3>
                <?php foreach ($menuItems as $menuItem): ?>
                    <?php if ((int) $soort === 2): ?>
                        <?php if($menuItem->gerechtcode === 2): ?>
                            <?php if($menuItem->subgerechtcode === 4): ?>
                                <li><?= $menuItem->menuitem ?> - €<?= $menuItem->prijs ?></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <h3>Vegetarische gerechten</h3>
                <?php foreach ($menuItems as $menuItem): ?>
                    <?php if ((int) $soort === 2): ?>
                        <?php if($menuItem->gerechtcode === 2): ?>
                            <?php if($menuItem->subgerechtcode === 4): ?>
                                <li><?= $menuItem->menuitem ?> - €<?= $menuItem->prijs ?></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <h1>Bijgerechten</h1>

                <h3>Warrme hapjes</h3>
                <?php foreach ($menuItems as $menuItem): ?>
                    <?php if ((int) $soort === 2): ?>
                        <?php if($menuItem->gerechtcode === 5): ?>
                            <?php if($menuItem->subgerechtcode === 10): ?>
                                <li><?= $menuItem->menuitem ?> - €<?= $menuItem->prijs ?></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <h3>Koude hapjes</h3>
                <?php foreach ($menuItems as $menuItem): ?>
                    <?php if ((int) $soort === 2): ?>
                        <?php if($menuItem->gerechtcode === 5): ?>
                            <?php if($menuItem->subgerechtcode === 11): ?>
                                <li><?= $menuItem->menuitem ?> - €<?= $menuItem->prijs ?></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <h1>Nagerechten</h1>

                <h3>ijs</h3>
                <?php foreach ($menuItems as $menuItem): ?>
                    <?php if ((int) $soort === 2): ?>
                        <?php if($menuItem->gerechtcode === 3): ?>
                            <?php if($menuItem->subgerechtcode === 13): ?>
                                <li><?= $menuItem->menuitem ?> - €<?= $menuItem->prijs ?></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <h3>Mousse</h3>
                <?php foreach ($menuItems as $menuItem): ?>
                    <?php if ((int) $soort === 2): ?>
                        <?php if($menuItem->gerechtcode === 3): ?>
                            <?php if($menuItem->subgerechtcode === 14): ?>
                                <li><?= $menuItem->menuitem ?> - €<?= $menuItem->prijs ?></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php endif; ?>

<!--                EINDE GERECHTEN KAART-->

            </ul>
        <?php endif; ?>
    </div>
</body>
</html>
