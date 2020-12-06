<?php
/**
 * Auteur: Thomas Grossmann / Mounir Fiaux
 * Date: Mars 2020
 **/

ob_start();
$title = "CSU-NVB - Remise de garde";
?>
<script src="js/shift.js"></script>
<div class="row m-2">
    <h1>Remise de Garde</h1>
</div>

<div class="row">
    <form action="?action=listshift" class="col">
        <input type="hidden" name="action" value="listshiftforbase">
        <select onchange="this.form.submit()" name="id" size="1">
            <?php foreach ($Bases as $base) : ?>
                <option value="<?= $base['id'] ?>" <?= ($baseID == $base['id']) ? 'selected' : '' ?> name="site"><?= $base['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if (($_SESSION['user']['admin'] == true) && ($_SESSION['base']['id'] == $baseID)) : ?>
        <div class="col">
            <form>
                <input type="hidden" name="action" value="newShiftSheet">
                <input type="hidden" name="id" value="<?= $baseID ?>">
                <button type="submit" class='btn btn-primary m-1 float-right'>Nouvelle Feuille de garde</button>
            </form>
        </div>
    <?php endif; ?>
</div>


<table class="table table-bordered  table-striped" style="text-align: center">
    <thead class="thead-dark">
    <th>Date</th>
    <th>État</th>
    <th>Véhicule</th>
    <th>Responsable</th>
    <th>Équipage</th>


    <th>Action</th>
    </thead>
    <?php foreach ($shiftsheets as $shiftsheet) { ?>
        <tr>
            <td><a href='?action=showshift&id=<?= $shiftsheet['id'] ?>'
                   class="btn"><?= date('d.m.Y', strtotime($shiftsheet['date'])) ?>  </a></td>
            <td>
                <?= $shiftsheet['status'] ?>
            </td>
            <td>Jour : <?= $shiftsheet['novaDay'] ?><br>Nuit : <?= $shiftsheet['novaNight'] ?></td>
            <td>Jour : <?= $shiftsheet['bossDay'] ?><br>Nuit : <?= $shiftsheet['bossNight'] ?> </td>
            <td>Jour : <?= $shiftsheet['teammateDay'] ?><br>Nuit : <?= $shiftsheet['teammateNight'] ?></td>
            <td>
                <!-- TODO (XCL): faire un helper qui donne l'action correspondante à l'état actuel -->
                <?php if ((($_SESSION['user']['admin'] == true and getNbshiftsheet('open', $baseID) == 0) ||
                    ($_SESSION['user']['admin'] == true and $shiftsheet['statusslug'] == 'close') ||
                    $shiftsheet['statusslug'] == 'open' ||
                    $shiftsheet['statusslug'] == 'reopen')) { ?>
                    <form>
                        <input type="hidden" name="action" value="altershiftsheetStatus">
                        <input type=hidden name="id" value= <?= $shiftsheet['id'] ?>>
                        <button class="btn btn-primary btn-sm"><?= $shiftsheet['status'] ?></button>
                    </form>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>

<?php

$content = ob_get_clean();
require GABARIT;
?>
