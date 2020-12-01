<?php
ob_start();
$title = "CSU-NVB - Remise de garde";
?>
<div class="row m-2">
    <h1>Remise de Garde du <?= date('d.m.Y', strtotime($guardSheet['date'])) ?> à <?= $guardSheet['base'] ?></h1>
</div>

<div class="row">
    <div class="col-3 text-right"></div>
    <div class="col-1 text-center">Jour</div>
    <div class="col-1 text-center">Nuit</div>
</div>
<?php if ($enableGuardSheetUpdate): //TODO à modifier pour pour pouvoir modifier les valeurs ?>
    <div class="row">
        <div class="col-3 text-right">Novas</div>
        <div class="col-1 text-center">todo</div>
        <div class="col-1 text-center">todo</div>
    </div>
    <div class="row">
        <div class="col-3 text-right">Responsable</div>
        <div class="col-1 text-center">todo</div>
        <div class="col-1 text-center">todo</div>
    </div>
    <div class="row">
        <div class="col-3 text-right">Equipier</div>
        <div class="col-1 text-center">todo</div>
        <div class="col-1 text-center">todo</div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-3 text-right">Novas</div>
        <div class="col-1 text-center"><?= $guardSheet['novaDay'] ?></div>
        <div class="col-1 text-center"><?= $guardSheet['novaNight'] ?></div>
    </div>
    <div class="row">
        <div class="col-3 text-right">Responsable</div>
        <div class="col-1 text-center"><?= $guardSheet['bossDay'] ?></div>
        <div class="col-1 text-center"><?= $guardSheet['bossNight'] ?></div>
    </div>
    <div class="row">
        <div class="col-3 text-right">Equipier</div>
        <div class="col-1 text-center"><?= $guardSheet['teammateDay'] ?></div>
        <div class="col-1 text-center"><?= $guardSheet['teammateNight'] ?></div>
    </div>
<?php endif; ?>

<?php foreach ($sections as $section): ?>
    <div class="row sectiontitle"><?= $section["title"] ?></div>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
        <th class="actionname"></th>
        <th class="ackcell">Jour</th>
        <th class="ackcell">Nuit</th>
        <th>Remarques</th>
        </thead>
        <tbody>

        <?php
        foreach ($section["actions"] as $action): ?>
            <tr>
                <td class="actionname">
                    <?= $action['text'] ?>
                </td>
                <?php if ($enableGuardSheetFilling): //TODO à modifier pour pour pouvoir modifier les valeurs ?>
                    <td class="ackcell">
                        todo
                    </td>
                    <td class="ackcell">
                        todo
                    </td>
                    <td>
                        todo
                    </td>
                <?php else: ?>
                    <td class="ackcell">
                        <?= $action["lines"][0]["dayInitials"]?>
                    </td>
                    <td class="ackcell">
                        <?= $action["lines"][0]["nightInitials"]?>
                    </td>
                    <td>
                        <?= $action["lines"][0]["comment"]?>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endforeach; ?>

<?php
$content = ob_get_clean();
require GABARIT;
?>
