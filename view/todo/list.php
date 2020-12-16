<!--
 * Projet   : CSUNVB
 * Fichier  : homeToDo.php
 * Auteur   : Vicky BUTTY
 * Version  : 2.0
 * Description: Permet l'affichage sous forme de liste des différents rapports hebdomadaires.
 * Page basée sur le travail précédement réalisé par Marwan ALHELO [13.02.2020] & Gatien JAYME [27.08.2020]
-->
<?php
ob_start();
$title = "CSU-NVB - Tâches hebdomadaires";
?>
<div>
    <h1>Tâches hebdomadaires</h1>
</div>

<div>
    <form>
        <input type="hidden" name="action" value="listtodoforbase">
        <select onchange="this.form.submit()" name="id">
            <?php
                foreach ($baseList as $base) {
                    echo "<option value='".$base['id']."'";
                    if($base['id'] == $selectedBaseID){
                        echo "selected='selected'";
                    }
                    echo ">".$base['name'];
                }?>
        </select>
    </form>
    <br>
    <?php if ($_SESSION['user']['admin'] == 1 && ($_SESSION['base']['id'] == $selectedBaseID)) : ?>
        <form method="POST" action="?action=addWeek" class="pull-right">
            <select name="selectModel">
                <?php if (!is_null($maxID['id'])): ?> <!-- valeur modifiée dans Todo -->
                    <option value='lastValue' selected=selected>Dernière semaine en date</option>
                <?php endif; ?>
                <?php foreach ($templates as $template) : ?>
                    <option value='<?= $template['template_name'] ?>'><?= $template['template_name'] ?></option>
                <?php endforeach;?>
            </select>
            <button type="submit" class="btn btn-primary m-1 pull-right">Nouvelle semaine</button>
        </form>
    <?php endif; ?>
</div>
<div>
    <h3>Semaine en cours</h3>
    <?php if(isset($activeWeek['week'])): ?>
        <table class="table">
            <thead class="thead-dark">
                <th>Semaine n°</th>
                <th>Statut</th>
                <th>Actions</th>
            </thead>
            <tbody>
            <tr>
                <td>Semaine <?=$activeWeek['week']?>
                    <?php if ($_SESSION['user']['admin'] == 1 && (isset($activeWeek['template_name']))) { ?>
                        <i class="fas fa-file-alt" title="<?=$activeWeek['template_name']?>"></i>
                    <?php } ?>
                </td>
                <td>En cours</td>
                <td>
                    <form>
                        <input type="hidden" name="action" value="showtodo">
                        <input type="hidden" name="id" value="<?= $activeWeek['id'] ?>">
                        <button type="submit" class='btn btn-primary m-1 float-right'>Détails</button>
                    </form>
                </td>
            </tr>
            </tbody>
        </table>
    <?php else :?>
        <div>
            <p>Aucune feuille de tâche n'est actuellement ouverte.</p>
        </div>
    <?php endif; ?>
</div>
<div>
    <h3>Semaines précédentes</h3>
    <?php if(!empty($weeksNbrList)): ?>
        <table class="table">
            <thead class="thead-dark">
            <th>Semaine n°</th>
            <th>Statut</th>
            <th>Actions</th>
            </thead>
            <tbody>
            <?php foreach ($weeksNbrList as $week):?>
                <tr>
                    <td>Semaine <?=$week['week']?>
                        <?php if ($_SESSION['user']['admin'] == 1 && (isset($week['template_name']))) { ?>
                            <i class="fas fa-file-alt" title="<?=$activeWeek['template_name']?>"></i>
                        <?php } ?>
                    </td>
                    <td>Cloturé</td>
                    <td>
                        <form>
                            <input type="hidden" name="action" value="showtodo">
                            <input type="hidden" name="id" value="<?= $week['id'] ?>">
                            <button type="submit" class='btn btn-primary m-1 float-right'>Détails</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else :?>
        <div>
            <p>Aucune feuille de tâche n'est encore cloturée.</p>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require GABARIT;
?>
