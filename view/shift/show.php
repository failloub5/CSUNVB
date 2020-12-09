<?php
ob_start();
$title = "CSU-NVB - Remise de garde";
?>
<div class="row m-2">
    <h1>Remise de Garde du <?= date('d.m.Y', strtotime($shiftsheet['date'])) ?> à <?= $shiftsheet['baseName'] ?></h1>
</div>
<?php if ($enableshiftsheetUpdate) : ?>
<form>
    <input type=hidden name="id" value= <?= $shiftsheet['id'] ?>>
    <div class="flex" >
        <div class="rollingList">
            <table>
                <tr>
                    <td class="col-3 text-center"></td>
                    <td class="col-1 text-center">Jour</td>
                    <td class="col-1 text-center">Nuit</td>
                </tr>

                <tr>
                    <td class="col-3 text-right">Novas</td>
                    <td class="col-1 text-center">
                        <select name="novaDay" class="formTeamNova">
                            <?= ($shiftsheet['novaDay']==NULL) ? '<option value="NULL" selected></option>':''?>
                            <?php foreach ($novas as $nova): ?>
                                <option value="<? $nova['id'] ?>" <?= ($shiftsheet['novaDay'] == $nova['number']) ? 'selected' : '' ?>><?= $nova['number'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="col-1 text-center">
                        <select name="novaNight" class="formTeamNova">
                            <?= ($shiftsheet['novaNight']==NULL) ? '<option value="NULL" selected></option>':''?>
                            <?php foreach ($novas as $nova): ?>
                                <option value="<? $nova['id'] ?>" <?= ($shiftsheet['novaNight'] == $nova['number']) ? 'selected' : '' ?>><?= $nova['number'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td class="col-3 text-right">Responsable</td>
                    <td class="col-1 text-center">
                        <select name="bossDay" class="formTeamNova">
                            <?= ($shiftsheet['bossDay']==NULL) ? '<option value="NULL" selected></option>':''?>
                            <?php foreach ($users as $user): ?>
                                <option value="<? $user['id'] ?>" <?= ($shiftsheet['bossDay'] == $user['initials']) ? 'selected' : '' ?>><?= $user['initials'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="col-1 text-center">
                        <select name="bossNight" class="formTeamNova">
                            <?= ($shiftsheet['bossNight']==NULL) ? '<option value="NULL" selected></option>':''?>
                            <?php foreach ($users as $user): ?>
                                <option value="<? $user['id'] ?>" <?= ($shiftsheet['bossNight'] == $user['initials']) ? 'selected' : '' ?>><?= $user['initials'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="col-3 text-right">Novas</td>
                    <td class="col-1 text-center">
                        <select name="teammateDay" class="formTeamNova">
                            <?= ($shiftsheet['teammateDay']==NULL) ? '<option value="NULL" selected></option>':''?>
                            <?php foreach ($users as $user): ?>
                                <option value="<? $user['id'] ?>" <?= ($shiftsheet['teammateDay'] == $user['initials']) ? 'selected' : '' ?>><?= $user['initials'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="col-1 text-center">
                        <select name="teammateNight" class="formTeamNova">
                            <?= ($shiftsheet['teammateNight']==NULL) ? '<option value="NULL" selected></option>':''?>
                            <?php foreach ($users as $user): ?>
                                <option value="<? $user['id'] ?>" <?= ($shiftsheet['teammateNight'] == $user['initials']) ? 'selected' : '' ?>><?= $user['initials'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>
        </div>

        <div>
            <a href="?action=validateData" class="btn btn-primary m-1 pull-right">Valider</a>
        </div>
    </div>
</form>
<?php else : ?>
<div class="row rollingList">
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
</div>

<?php endif;?>

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
                        <?php if (count($action["checksDay"]) == 0): ?>
                            <button type="submit" class="btn btn-warning toggleShiftModal" style=" width: 100%;">Valider
                                <div class="text-success bg-white rounded mt-1">
                                </div>
                            </button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-success toggleShiftModal">Re-Valider
                                <div class="text-success bg-white rounded mt-1">
                                    <?php foreach ($action["checksDay"] as $check): ?>
                                        <?= $check["initials"] ?>
                                    <?php endforeach; ?>
                                </div>
                            </button>
                        <?php endif; ?>
                    </td>
                    <td class="ackcell" style="padding : 3px; width: 110px;">
                        <?php if (count($action["checksNight"]) == 0): ?>
                            <button type="submit" class="btn btn-warning toggleShiftModal" style=" width: 100%;">Valider
                                <div class="text-success bg-white rounded mt-1">
                                </div>
                            </button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-success toggleShiftModal">Re-Valider
                                <div class="text-success bg-white rounded mt-1">
                                    <?php foreach ($action["checksNight"] as $check): ?>
                                        <?= $check["initials"] ?>
                                    <?php endforeach; ?>
                                </div>
                            </button>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php foreach ($action["comments"] as $comment): ?>
                            [ <?= $comment['initials'] ?>, <?= $comment['time'] ?> ] : <?= $comment['message'] ?>
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

<div class="modal fade" id="shiftModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="get" action="?action=          -- à faire --           ">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-content"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="js/shift.js"></script>
<?php
$content = ob_get_clean();
require GABARIT;
?>
