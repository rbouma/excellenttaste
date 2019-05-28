<?php
include_once('config/config.php');

$db = MysqliDb::getInstance();
$gerechten = $db->get('menuitem');
$counter = 0;

if (isset($_POST['bestelitem'])) {
    if ((int) $_POST['gerechtcode'] === 1 || (int) $_POST['gerechtcode'] === 2 || (int) $_POST['gerechtcode'] === 3 || (int) $_POST['gerechtcode'] === 5) {
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

<ul class="gerechten">
    <?php foreach ($gerechten as $item): ?>
    <?php if($item['gerechtcode'] === 1 || $item['gerechtcode'] === 2 || $item['gerechtcode'] === 3 || $item['gerechtcode'] === 5): ?>
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
