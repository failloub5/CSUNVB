<?php
ob_start();
$title = "CSU-NVB - Remise de garde";
?>
<script src="js/shift.js"></script>
<div class="row m-2">
    <h1>Remise de Garde</h1>
</div>

<div class="row">
    <form>
        <input type="hidden" name="action" value="listshift">
        <select onchange="this.form.submit()" name="id" size="1">
            <?php foreach ($bases as $base) : ?>
                <option value="<?= $base['id'] ?>" <?= ($baseID == $base['id']) ? 'selected' : '' ?>
                        name="base"><?= $base['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </form>
    <?php if (($_SESSION['user']['admin'] == true)) : ?>
        <div class="col">
            <form action="?action=newShiftSheet&id=<?=$baseID?>" method="post">
                <button class='btn btn-primary m-1 float-right'>Nouvelle Feuille de garde</button>
            </form>
        </div>
    <?php endif; ?>
</div>
<div id="tableContent">
    <?= listSheet("shift",$sheets) ?>
</div>
<?php
$content = ob_get_clean();
require GABARIT;
?>
