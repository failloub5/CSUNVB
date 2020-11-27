<?php
ob_start();
$title = "CSU-NVB - Remise de garde";
?>
<div class="row m-2">
    <h1>Remise de Garde du <?= date('d.m.Y', strtotime($guardsheet['date'])) ?> à <?= $guardsheet['base'] ?></h1>
</div>

<div class="row">
    <div class="col-3 text-right"></div>
    <div class="col-1 text-center">Jour</div>
    <div class="col-1 text-center">Nuit</div>
</div>
<div class="row">
    <div class="col-3 text-right">Novas</div>
    <div class="col-1 text-center"><?= $guardsheet['novaDay'] ?></div>
    <div class="col-1 text-center"><?= $guardsheet['novaNight'] ?></div>
</div>
<div class="row">
    <div class="col-3 text-right">Responsable</div>
    <div class="col-1 text-center"><?= $guardsheet['bossDay'] ?></div>
    <div class="col-1 text-center"><?= $guardsheet['bossNight'] ?></div>
</div>
<div class="row">
    <div class="col-3 text-right">Equipier</div>
    <div class="col-1 text-center"><?= $guardsheet['teammateDay'] ?></div>
    <div class="col-1 text-center"><?= $guardsheet['teammateNight'] ?></div>
</div>

<?php foreach ($sections as $title => $section): ?>
    <div class="row sectiontitle"><?= $title ?></div>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
        <th class="actionname"></th>
        <th class="ackcell">Jour</th>
        <th class="ackcell">Nuit</th>
        <th>Remarques</th>
        </thead>
        <tbody>
        <?php foreach ($section as $action): ?>
            <tr>
                <td class="actionname"><?= $action['text'] ?></td>
                <td class="ackcell"></td>
                <td class="ackcell"></td>
                <td></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endforeach; ?>

<?php
$content = ob_get_clean();
require GABARIT;
?>
