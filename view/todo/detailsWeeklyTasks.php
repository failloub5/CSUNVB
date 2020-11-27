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
    <div class="week text-center hour p-0">
        <?php foreach ($dates as $index => $onedate) : ?>
            <div class='bg-primary text-white col-md font-weight-bold'><?= $days[$index+1] ?><br><?= $onedate ?></div>
        <?php endforeach; ?>
    </div>
    <div class="week text-center hour">
        <div class="col-md font-weight-bold bg-info text-white">Journée</div>
    </div>
    <div class="row week hour">
        <?php foreach ($dates as $index => $onedate) : ?>
            <div class="col p-1">
                <?php foreach ($todoThings[1][$index+1] as $todothing): ?>
                    <div class="todothing mb-1"><?= $todothing['description'] ?></div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="week text-center hour">
        <div class="col-md font-weight-bold bg-info text-white">Nuit</div>
    </div>
    <div class="row week hour">
        <?php foreach ($dates as $index => $onedate) : ?>
            <div class="col p-1">
                <?php foreach ($todoThings[0][$index+1] as $todothing): ?>
                    <div class="todothing mb-1"><?= $todothing['description'] ?></div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
require GABARIT;
?>

