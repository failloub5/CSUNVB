<?php
/**
 * Title: CSUNVB
 * USER: marwan.alhelo
 * DATE: 13.02.2020
 * Time: 11:29
 **/

ob_start();
$title = "CSU-NVB - Accueil";
?>

<div class="container ">
    <div class="row m-4 d-flex justify-content-center">
        <?php if ($_SESSION['user']['admin'] == true): ?>
            <a class="col-4 bigfont btn btn-primary btn-large p-5 m-5 font-weight-bolder" href="?action=adminHome">Administration</a>
        <?php endif; ?>
        <a class="col-4 bigfont btn btn-primary btn-large p-5 m-5 font-weight-bolder" href="?action=listshift">Remise de garde</a>
        <a class="col-4 bigfont btn btn-primary btn-large p-5 m-5 font-weight-bolder" href="?action=listtodo">Tâches hebdomadaires</a>
        <a class="col-4 bigfont btn btn-primary btn-large p-5 m-5 font-weight-bolder" href="?action=listDrugSheets">Stupéfiants</a>
    </div>

</div>

<?php
$content = ob_get_clean();
require GABARIT;
?>
