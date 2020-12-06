<?php
/**
 * Auteur: David Roulet / Fabien Masson
 * Date: Aril 2020
 **/

$title = "CSU-NVB - Stupéfiants";
ob_start();
?>
<div class="row m-2">
    <h1>Stupéfiants</h1>
</div>
<h2>Site de <?= $site ?>, Semaine N°<?= $drugsheet["week"] ?></h2>
<?php //TODO: RAJOUTER LE BOUT DE CODE POUR GENERER BOUTON?>
<?php foreach ($dates as $date): ?>
    <table border="1" class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th colspan="6" <?= (date("Y-m-d", $date) == date("Y-m-d")) ? "class='today'" : "" ?>>
                    <?= date("l j M Y", $date) ?>
                </th>
            </tr>
            <tr>
            <th>
                <?php //TODO: th a supprimer? ?>
            </th>
            <th>Pharmacie (matin)</th>
            <?php foreach ($novas as $nova): ?>
                <th><?= $nova["number"] ?></th>
            <?php endforeach; ?>
            <th>Pharmacie (soir)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($drugs as $drug): ?>
                <tr>
                    <td class="font-weight-bold"><?= $drug["name"] ?></td>
                    <td><?php //TODO: td a supprimer? ?></td>
                    <?php foreach ($novas as $nova): ?>
                        <td>
                            <div>
                                <?= getNovaCheckByDateAndBatch(date("Y-m-d", $date), $drug['id'], $nova['id'], $drugsheet['id'])["start"] ?>
                            </div>
                            <div>
                                <?= getNovaCheckByDateAndBatch(date("Y-m-d", $date), $drug['id'], $nova['id'], $drugsheet['id'])["end"] ?>
                            </div>
                        </td>
                    <?php endforeach; ?>
                    <td><?php //TODO: td a supprimer? ?></td>
                </tr>
                <!-- Plusieurs lignes avec les batches nom de ce médicament, les restocks et les pharmachecks -->
                <?php foreach ($batchesByDrugId[$drug["id"]] as $batch): ?>
                    <tr>
                        <td class="text-right"><?= $batch['number'] ?></td>
                        <td class="text-center"><?= $pharmacheck['start'] ?></td>
                        <?php foreach ($novas as $nova): ?>
                            <td class="text-center"><?= $quantity ?></td>
                        <?php endforeach; ?>
                        <td class="text-center"><?= $pharmacheck['end'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <tr>
                <td>Signature</td>
                <td colspan="5"></td>
            </tr>
        </tbody>
    </table>
    <?php
    $date = strtotime(date("Y-m-d", $date) . " +1 day");
    ?>
<?php endforeach; ?>

<?php
$content = ob_get_clean();
require GABARIT;
?>
