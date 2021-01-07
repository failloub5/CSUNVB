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
        <select onchange="this.form.submit()" name="id" size="1" id="id">
            <?php foreach ($Bases as $base) : ?>
                <option value="<?= $base['id'] ?>" <?= ($baseID == $base['id']) ? 'selected' : '' ?>
                        name="site"><?= $base['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </form>
    <?php if (($_SESSION['user']['admin'] == true)) : ?>
        <div class="col">
            <button class='btn btn-primary m-1 float-right' onclick="newShiftSheet()">Nouvelle Feuille de garde</button>
        </div>
    <?php endif; ?>
</div>
<div id="tableContent">
    <?php displayShift($baseID); ?>
</div>
<?= listSheet("shift") ?>
<?php
$content = ob_get_clean();
require GABARIT;
?>
