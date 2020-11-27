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

<?php foreach ($listSections as $section): ?>
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
        $actions = getActionFromSection($section["id"]);
        foreach ($actions as $action): ?>
            <tr>
                <?php
                $guardContent = getGuardContent($action['id'], $shiftid);
                ?>

                <td class="actionname"><?= $action['text'] ?></td>
                <td class="ackcell">
                    <?php
                    $number = 0;
                    foreach ($guardContent as $data){
                        if(isset($data['dayInitials'])){
                            if($number > 0) echo "<br>";
                            echo $data['dayInitials'];
                            $number ++;
                        }
                    }
                    ?>
                </td>
                <td class="ackcell">
                    <?php
                    $number = 0;
                    foreach ($guardContent as $data){
                        if(isset($data['nightInitials'])){
                            if($number > 0) echo "<br>";
                            echo $data['nightInitials'];
                            $number ++;
                        }
                    }
                    ?>
                </td>
                <td>
                    <?php
                    $number = 0;
                    foreach ($guardContent as $data){
                        if($number > 0)echo "<br>";
                        echo $data['comment'];
                        $number ++;
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endforeach; ?>

<?php
$content = ob_get_clean();
require GABARIT;
?>
