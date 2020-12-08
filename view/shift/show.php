<?php
ob_start();
$title = "CSU-NVB - Remise de garde";
?>
<div class="row m-2">
    <h1>Remise de Garde du <?= date('d.m.Y', strtotime($shiftsheet['date'])) ?> à <?= $shiftsheet['baseName'] ?></h1>
</div>

    <form>
        <input type=hidden name="id" value= <?= $shiftsheet['id'] ?>>
        <div style="display:flex; align-content: space-between; align-items: center;">
        <div class="row" style="
            padding-left:40px;
            margin-top:20px;
            margin-bottom:20px;
            margin-right:60px;">
            <table>
                <tr>
                    <td class="col-3 text-right"></td>
                    <td class="col-1 text-right">Jour</td>
                    <td class="col-1 text-right">Nuit</td>
                </tr>
                <tr>
                    <td class="col-3 text-right">Novas</td>
                    <td class="col-1 text-center"><?= $shiftsheet['novaDay'] ?></td>
                    <td class="col-1 text-center"><?= $shiftsheet['novaNight'] ?></td>
                </tr>
                <tr>
                    <td class="col-3 text-right">Responsable</td>
                    <td class="col-1 text-center"><?= $shiftsheet['bossDay'] ?></td>
                    <td class="col-1 text-center"><?= $shiftsheet['bossNight'] ?></td>
                </tr>
                <tr>
                    <td class="col-3 text-right">Novas</td>
                    <td class="col-1 text-center"><?= $shiftsheet['teammateDay'] ?></td>
                    <td class="col-1 text-center"><?= $shiftsheet['teammateNight'] ?></td>
                </tr>
            </table>
            <!--<div class="col-3 text-right"></div>
            <div class="col-3 text-center">Jour</div>
            <div class="col-1 text-center">Nuit</div>-->
        </div>

        <div >
            <a href="?action=validateData" class="btn btn-primary m-1 pull-right">Valider</a>
        </div>
        <?php /*if ($enableshiftsheetUpdate): //TODO à modifier pour pour pouvoir modifier les valeurs */ ?><!--
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
    --><?php /*else: */ ?>
        <!-- <div class="row">
        <div class="col-3 text-right">Novas</div>
        <div class="col-1 text-center"><? /*= $shiftsheet['novaDay'] */ ?></div>
        <div class="col-1 text-center"><? /*= $shiftsheet['novaNight'] */ ?></div>
    </div>
    <div class="row">
        <div class="col-3 text-right">Responsable</div>
        <div class="col-1 text-center"><? /*= $shiftsheet['bossDay'] */ ?></div>
        <div class="col-1 text-center"><? /*= $shiftsheet['bossNight'] */ ?></div>
    </div>
    <div class="row">
        <div class="col-3 text-right">Equipier</div>
        <div class="col-1 text-center"><? /*= $shiftsheet['teammateDay'] */ ?></div>
        <div class="col-1 text-center"><? /*= $shiftsheet['teammateNight'] */ ?></div>
    </div>
    --><?php /*/*endif; */ ?>
        </div>
    </form>

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
                    <td class="ackcell" style="padding : 3px; width: 110px;">
                        <?php if (count($action["checksDay"])==0): ?>
                            <button type="submit" class="btn btn-warning" style=" width: 100%;">Valider
                                <div class="text-success bg-white rounded mt-1">
                                </div>
                            </button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-success">Re-Valider
                                <div class="text-success bg-white rounded mt-1">
                                    <?php foreach ($action["checksDay"] as $check):?>
                                        <?=$check["initials"]?>
                                    <?php endforeach; ?>
                                </div>
                            </button>
                        <?php endif; ?>
                    </td>
                    <td class="ackcell" style="padding : 3px; width: 110px;">
                        <?php if (count($action["checksNight"])==0): ?>
                            <button type="submit" class="btn btn-warning" style=" width: 100%;">Valider
                                <div class="text-success bg-white rounded mt-1">
                                </div>
                            </button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-success">Re-Valider
                                <div class="text-success bg-white rounded mt-1">
                                    <?php foreach ($action["checksNight"] as $check):?>
                                        <?=$check["initials"]?>
                                    <?php endforeach; ?>
                                </div>
                            </button>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php foreach ($action["comments"] as $comment):?>
                            [ <?= $comment['initials']?>, <?= $comment['time']?> ] : <?= $comment['message'] ?>
                            <br>
                        <?php endforeach; ?>
                        <button type="submit" class="btn bg-white btn-block m-1" style="width:50px;">+</button>
                    </td>
                <?php else: ?>
                    <td class="ackcell">
                        <?php foreach ($action["checksDay"] as $check): ?>
                            <?= $check["initials"] ?>
                        <?php endforeach; ?>
                    </td>
                    <td class="ackcell">
                        <?php foreach ($action["checksNight"] as $check): ?>
                            <?= $check["initials"] ?>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?php foreach ($action["comments"] as $comment): ?>
                            [ <?= $comment['initials'] ?>, <?= $comment['time'] ?> ] : <?= $comment['message'] ?>
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
