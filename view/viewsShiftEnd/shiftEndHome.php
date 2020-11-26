<?php
/**
 * Auteur: Thomas Grossmann / Mounir Fiaux
 * Date: Mars 2020
 **/

ob_start();
$title = "CSU-NVB - Remise de garde";
?>
<script src="js/shiftEnd.js"></script>
<div class="row m-2">
    <h1>Remise de Garde</h1>
</div>

<div class="row">
    <FORM action="?action=listShiftEnd" method="post" class="col">
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
<div class="row m-2">
    <?php
    foreach ($list as $item) {
        if ($item["base_id"] == $_SESSION["Selectsite"]) {//todo michael
            $weeks[] = $item;

        }
    } ?>
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
    <?php foreach ($guardsheets as $guardSheet) { ?>
        <tr>
            <td><a href='?action=showGuardSheet&id=<?= $guardSheet['id'] ?>'
                   class="btn"><?= date('d.m.Y', strtotime($guardSheet['date'])) ?>  </a></td>
            <td><?php
                switch ($guardSheet['state']) {
                    case 'open' :
                        echo 'Ouvert';
                        break;
                    case 'blank' :
                        echo 'En préparation';
                        break;
                    case 'reopen' :
                        echo 'Réouvert';
                        break;
                    case 'close' :
                        echo 'Fermé';
                        break;
                    default :
                        echo 'Statut Inconnu';
                        break;
                }
                ?></td>
            <td>Jour : <?= $guardSheet['novaDay'] ?><br>Nuit : <?= $guardSheet['novaNight'] ?></td>
            <td>Jour :<?= $guardSheet['bossDay'] ?><br>Nuit :<?= $guardSheet['bossNight'] ?> </td>
            <td>Jour : <?= $guardSheet['teammateDay'] ?><br>Nuit : <?= $guardSheet['teammateNight'] ?></td>
            <td>
                <?php if (($_SESSION['username']['admin'] == true || $guardSheet['state'] == 'open' || $guardSheet['state'] == 'reopen')) { ?>

                    <form action="?action=alterGuardSheetStatus" method="post">
                        <input type = hidden name="id" value = <?= $guardSheet['id'] ?>>
                        <button class="btn btn-primary btn-sm" name="status" value="<?= $guardSheet['state'] ?>"
                        </button>
                        <?php
                        switch ($guardSheet['state']) {
                            case 'open' :
                            case 'reopen' :
                                echo 'Fermer';
                                break;
                            case 'blank' :
                                echo 'Ouvrir';
                                break;
                            case 'close' :
                                echo 'Rouvrir';
                                break;
                            default :
                                echo 'erreur';
                                break;
                        }?>
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
