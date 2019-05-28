<?php
include_once('config/config.php');

$db = MysqliDb::getInstance();
$gerechten = $db->get('menuitem');

//ALLE DRANKEN OPHALEN
if(isset($_POST['bestelitem'])) {
    if ((int) $_POST['gerechtcode'] === 4) {
        $data = Array (
            "datum_tijd" => date("Y-m-d H:i:s"),
            "tafel" => htmlspecialchars($_GET['tafelnummer']),
            "menuitemcode" => htmlspecialchars($_POST['bestelitem']),
            "aantal" => 1,
            "prijs" => htmlspecialchars($_POST['prijs'])
        );

        $id = $db->insert ('bestelling', $data);
        if($id) {
            echo 'Bestelling geplaatst met id: '. $id;
        }
    }
}

?>

<!--DRANKEN VERDELEN-->
<ul class="dranken">
    <?php foreach ($gerechten as $item): ?>
        <?php if($item['gerechtcode'] === 4): ?>
            <li>
                <form method="post">
                    <input type="hidden" name="gerechtcode" value="<?= $item['gerechtcode'] ?>">
                    <input type="hidden" name="bestelitem" value="<?= $item['menuitemcode'] ?>">
                    <input type="hidden" name="prijs" value="<?= $item['prijs'] ?>">
                    <input type="submit" name="submit" value="<?= $item['menuitem']. ' €' .$item['prijs'] ?>">
                </form>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>
