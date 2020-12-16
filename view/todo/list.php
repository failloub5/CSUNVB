<!--
 * Projet   : CSUNVB
 * Fichier  : homeToDo.php
 * Auteur   : Vicky BUTTY
 * Version  : 2.0
 * Description: Permet l'affichage sous forme de liste des différents rapports hebdomadaires.
 * Page basée, en partie, sur le travail précédement réalisé par Marwan ALHELO [13.02.2020] & Gatien JAYME [27.08.2020]
-->
<?php
ob_start();
$title = "CSU-NVB - Tâches hebdomadaires";
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
    <div> <!-- Liste déroulante pour le choix du modèle et bouton de nouvelle semaine -->
        <?php if ($_SESSION['user']['admin'] == 1 && ($_SESSION['base']['id'] == $selectedBaseID)) : ?>
            <form method="POST" action="?action=addWeek" class="pull-right">
                <select name="selectModel">
                    <?php if (!is_null($maxID['id'])): ?>
                        <option value='lastValue' selected=selected>Dernière semaine en date</option>
                    <?php endif; ?>
                    <?php foreach ($templates as $template) : ?>
                        <option value='<?= $template['template_name'] ?>'><?= $template['template_name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary m-1 pull-right">Nouvelle semaine</button>
            </form>
        <?php endif; ?>
    </div>
</div>
<div> <!-- Sections d'affichage des différentes feuilles -->
    <div> <!-- Feuilles ouvertes -->
        <h3>Semaine <?= showSlugs("open") ?></h3>
        <?php if (!empty($openWeeks)): ?>
            <table class="table">
                <thead class="thead-dark">
                <th>Semaine n°</th>
                <th>Actions</th>
                </thead>
                <tbody>
                <?php foreach ($openWeeks as $week): ?>
                    <tr>
                        <td>Semaine <?= $week['week'] ?>
                            <!-- Affichage du logo de template si nécessaire -->
                            <?php if ($_SESSION['user']['admin'] == 1 && (isset($week['template_name']))) : ?>
                                <i class="fas fa-file-alt" title="<?= $week['template_name'] ?>"></i>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= showDetailsButton("showtodo", $week['id']) ?>
                            <?= slugsButtonTodo("open", $week['id']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tr>
                </tbody>
            </table>
        <?php else : ?>
            <div>
                <p>Aucune feuille de tâche n'est actuellement <?= showSlugs("open") ?>.</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php
$content = ob_get_clean();
require GABARIT;
?>