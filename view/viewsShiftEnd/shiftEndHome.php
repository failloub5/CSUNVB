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
        <?php if ($admin['admin'] == 1) { ?>
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

    <?php if ($admin['admin'] == 1) { ?>
        <th>Action</th><?php } ?>
    </thead>
    <?php ?>
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


            <?php if ($admin['admin'] == 1) { ?>
                <td>
                    <?php if ($guardSheet['state'] == 'close') : ?>
                        <form action="?action=reOpenShift" method="post">
                            <button class="btn btn-primary btn-sm" name="reOpen" value="<?= $guardSheet['id'] ?>"
                            </button>Rouvrir
                        </form>
                    <?php endif; ?>
                    <?php if ($guardSheet['state'] == 'blank') : ?>
                        <form action="?action=openShift" method="post">
                            <button class="btn btn-primary btn-sm" name="open" value="<?= $guardSheet['id'] ?>"
                            </button>Ouvrir
                        </form>
                    <?php endif; ?>
                    <?php if ($guardSheet['state'] == 'open' || $guardSheet['state'] == 'reopen') : ?>
                        <form action="?action=closedShift" method="post">
                            <button class="btn btn-primary btn-sm" name="close" value="<?= $guardSheet['id'] ?>"
                            </button>Fermer
                        </form>
                    <?php endif; ?>
                </td>
            <?php } ?>
        </tr>
    <?php } ?>
</table>

<?php

$content = ob_get_clean();
require GABARIT;
?>
