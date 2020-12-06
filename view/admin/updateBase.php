<?php
/**
 * Auteur: Thomas Grossmann / Mounir Fiaux
 * Date: Avril 2020
 **/

ob_start();
$title = "CSU-NVB - Administration - Modifier Base";
?>

<form class="form form-group" action="?action=updateBase" method="POST">
    <input type="hidden" name="id" value="<?= $base['id'] ?>">
    <label class="form-group">Renommer</label>
    <input type="text" class="form-group form-control" name="updateNameBase" required value="<?= $base['name'] ?>">
    <input type="submit" class="btn btn-primary">
</form>

<?php
$content = ob_get_clean();
require GABARIT;
?>
