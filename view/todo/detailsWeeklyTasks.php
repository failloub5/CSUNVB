<?php
ob_start();
$title = "CSU-NVB - Tâches hebdomadaires";
?>
<div>
    <h1>Tâches hebdomadaires</h1>
    <h2>Semaine <?= $week['week']?> - Base de <?= $base['name'] ?></h2>

    <div>
        <form action="?action=homeWeeklyTasks" method="POST">
            <input type="hidden" name="selectBaseID" value="<?= $base['id'] ?>">
            <button type="submit" class='btn btn-primary m-1 float-right'>Retour à la liste</button>
        </form>
        <?php if ($_SESSION['username']['admin'] == 1 && $alreadyOpen == false && $week['state'] == "close"): ?>
            <form action="?action=openWeek" method="POST">
                <input type="hidden" name="weekID" value="<?= $week['id'] ?>">
                <input type="hidden" name="baseID" value="<?= $base['id'] ?>">
                <button type="submit" class='btn btn-primary m-1 float-right'>Ouvrir</button>
            </form>
        <?php elseif ($_SESSION['username']['admin'] == 1 && $week['state'] == "open"): ?>
            <form action="?action=closeWeek" method="POST">
                <input type="hidden" name="weekID" value="<?= $week['id'] ?>">
                <input type="hidden" name="baseID" value="<?= $base['id'] ?>">
                <button type="submit" class='btn btn-primary m-1 float-right'>Clôturer</button>
            </form>
        <?php endif; ?>
    </div>
</div>
<div>
    <table class="table">
        <thead class="thead-dark">
        <?php foreach($dates as $date){ ?>
            <th><?= $date ?> </th>
        <?php } ?>
        </thead>
        <tbody>
            <tr>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
<?php
$content = ob_get_clean();
require GABARIT;
?>

