<?php
ob_start();
$title = "CSU-NVB - Remise de garde";
?>
<div class="row m-2">
    <h1>Remise de Garde du <?= date('d.m.Y', strtotime($shiftsheet['date'])) ?> à <?= $shiftsheet['baseName'] ?></h1>
</div>

<div class="row">
    <div class="col-3 text-right"></div>
    <div class="col-1 text-center">Jour</div>
    <div class="col-1 text-center">Nuit</div>
</div>
<?php if ($enableshiftsheetUpdate): //TODO à modifier pour pour pouvoir modifier les valeurs ?>
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
        <div class="col-1 text-center"><?= $shiftsheet['novaDay'] ?></div>
        <div class="col-1 text-center"><?= $shiftsheet['novaNight'] ?></div>
    </div>
    <div class="row">
        <div class="col-3 text-right">Responsable</div>
        <div class="col-1 text-center"><?= $shiftsheet['bossDay'] ?></div>
        <div class="col-1 text-center"><?= $shiftsheet['bossNight'] ?></div>
    </div>
    <div class="row">
        <div class="col-3 text-right">Equipier</div>
        <div class="col-1 text-center"><?= $shiftsheet['teammateDay'] ?></div>
        <div class="col-1 text-center"><?= $shiftsheet['teammateNight'] ?></div>
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
                <?php if ($enableshiftsheetFilling): //TODO à modifier pour pour pouvoir modifier les valeurs ?>
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
                        <?php foreach ($action["checksDay"] as $check):?>
                        <?=$check["initials"]?>
                        <?php endforeach; ?>
                    </td>
                    <td class="ackcell">
                        <?php foreach ($action["checksNight"] as $check):?>
                            <?=$check["initials"]?>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?php foreach ($action["comments"] as $comment):?>
                            [ <?= $comment['initials']?>, <?= $comment['time']?> ] : <?= $comment['message'] ?>
                            <br>
                        <?php endforeach; ?>
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
