<?php
ob_start();
$title = "CSU-NVB - Remise de garde";
?>
<script src="js/shift.js"></script>
<div class="row m-2">
    <h1>Remise de Garde</h1>
</div>

<div class="row">
    <form action="?action=listshift" class="col">
        <input type="hidden" name="action" value="listshiftforbase">
        <select onchange="diplayShiftForBase()" name="id" size="1" id="id">
            <?php foreach ($Bases as $base) : ?>
                <option value="<?= $base['id'] ?>" <?= ($baseID == $base['id']) ? 'selected' : '' ?>
                        name="site"><?= $base['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </form>
    <?php if (($_SESSION['user']['admin'] == true)) : ?>
        <div class="col">
            <form>
                <input type="hidden" name="action" value="newShiftSheet">
                <input type="hidden" name="id" value="<?= $baseID ?>">
                <button type="submit" class='btn btn-primary m-1 float-right'>Nouvelle Feuille de garde</button>
            </form>
        </div>
    <?php endif; ?>
</div>
<div id="tableContent">
    <?php displayShift(); ?>
</div>
<?php
$content = ob_get_clean();
require GABARIT;
?>
