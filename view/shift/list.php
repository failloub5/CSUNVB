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
    <FORM action="?action=listshift" method="post" class="col">
        <SELECT onchange="this.form.submit()" name="selectedBase" size="1">
            <?php foreach ($Bases as $base) { ?>
            <OPTION value="<?= $base['id'] ?>" <?php if ($baseID == $base['id']) { ?>
                selected="selected"
            <?php } ?>
                    name="site"><?= $base['name'] ?>
                <?php } ?>
        </SELECT>
    </FORM>

    <form action="?action=newSheet" method="post">
        <?php if (($_SESSION['username']['admin'] == true)) { ?>
            <div class="col">
                <input type="hidden" name="site" value="<?= $baseID ?>">
                <button type="submit" class='btn btn-primary m-1 float-right'>Nouvelle Feuille de garde</button>
            </div>
        <?php } ?>
    </form>
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
            <td><a href='?action=showshiftsheet&id=<?= $shiftsheet['id'] ?>'
                   class="btn"><?= date('d.m.Y', strtotime($shiftsheet['date'])) ?>  </a></td>
            <td>
                <?= $shiftsheet['status'] ?>
            </td>
            <td>Jour : <?= $shiftsheet['novaDay'] ?><br>Nuit : <?= $shiftsheet['novaNight'] ?></td>
            <td>Jour : <?= $shiftsheet['bossDay'] ?><br>Nuit : <?= $shiftsheet['bossNight'] ?> </td>
            <td>Jour : <?= $shiftsheet['teammateDay'] ?><br>Nuit : <?= $shiftsheet['teammateNight'] ?></td>
            <td>
                <?php if ((($_SESSION['username']['admin'] == true and getNbshiftsheet('open',$baseID) == 0) ||
                    ($_SESSION['username']['admin'] == true and $shiftsheet['status'] == 'close') ||
                    $shiftsheet['status'] == 'open' ||
                    $shiftsheet['status'] == 'reopen')) { ?>
                    <form action="?action=altershiftsheetStatus" method="post">
                        <input type=hidden name="id" value= <?= $shiftsheet['id'] ?>>
                        <button class="btn btn-primary btn-sm" name="status" value="<?= $shiftsheet['status'] ?>"
                        </button>
                        <?= $shiftsheet['status'] ?>
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
