<?php
ob_start();
$title = "CSU-NVB - Drogues hebdomadaires";
?>
<div>
    <h1>Tâches hebdomadaires</h1>
</div>
<div>
    <div> <!-- Liste déroulante pour le choix de la base -->
        <form>
            <input type="hidden" name="action" value="listtodoforbase">
            <select onchange="this.form.submit()" name="id" size="1">
                <?php foreach ($baseList as $base) : ?>
                    <option value="<?= $base['id'] ?>" <?= ($selectedBaseID == $base['id']) ? 'selected' : '' ?>
                            name="site"><?= $base['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    <div class="newSheetZone"> <!-- Bouton de nouvelle semaine -->
        <?php if (ican('createsheet') && ($_SESSION['base']['id'] == $selectedBaseID)) : ?>
            <form method="POST" action="?action=addWeek" class="float-right">
                <button type="submit" class="btn btn-primary m-1 pull-right">Nouvelle semaine</button>
            </form>
        <?php endif; ?>
    </div>
</div>
<div> <!-- Sections d'affichage des différentes feuilles -->
    <div> <!-- Feuilles ouvertes -->
        <?= showDrugSheetsByStatus("open", $openWeeks) ?>
    </div>
    <br>
    <div> <!-- Feuilles en préparation -->
        <?= showDrugSheetsByStatus("blank", $blankWeeks) ?>
    </div>
    <br>
    <div> <!-- Feuilles en correction -->
        <?= showDrugSheetsByStatus("reopen", $reopenWeeks) ?>
    </div>
    <br>
    <div> <!-- Feuilles fermées -->
        <?= showDrugSheetsByStatus("close", $closeWeeks) ?>
    </div>
    <br>
</div>

<?php
$content = ob_get_clean();
require GABARIT;
?>