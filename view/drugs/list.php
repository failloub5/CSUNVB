<?php
/**
 * Auteur: David Roulet / Fabien Masson + h
 * Date: Aril 2020 + 2020/11
 **/
ob_start();
$title = "CSU-NVB - Stupéfiants";
?>
<div class="row m-2">
    <h1>Stupéfiants</h1>
</div>
<div class="row">
    <form class="col">
        <input type="hidden" name="action" value="listDrugSheets">
        <label>
            <select onchange="this.form.submit()" name="id" size="1">
                <?php foreach($bases as $base): ?>
                    <option value="<?= $base["id"] ?>"
                    <?php if ($baseID == $base["id"]): ?>
                         selected
                    <?php endif; ?>
                    name='site'>
                    <?= $base["name"] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </form>
    <div class="row m-2">
    </div>
    <a class="col">
        <?php if ($_SESSION['user']['admin'] == 1): ?>
            <a href="?action=newDrugSheet&id=<?= $baseID ?>">
                <button class='btn btn-primary m-1 float-right'>Nouvelle feuille de stupéfiants</button>
            </a>
        <?php endif; ?>
    </a>
</div>
<div class="row">
    <table class="table table-bordered">
        <thead class="thead-dark">
        <th>Date</th>
        <th>État</th>
        <?php if ($_SESSION['user']['admin'] == 1): ?>
            <th>Action</th>
        <?php endif; ?>
        </thead>
        <tbody>
            <?php foreach ($drugSheetList as $week): ?>
                <tr>
                    <td>
                        <a href='?action=showDrugSheet&id=<?= $week['id'] ?>'>
                            Semaine <?= $week["week"] ?>
                        </a>
                    </td>
                    <td>
                        <?= $week['state'] ?>
                    </td>
                    <?php if ($_SESSION['user']['admin'] == 1): ?>
                        <td>
                            <div class='row'>
                                <a href='?action=<?= getDrugStateButton($week['state']) ?>DrugSheet&site=<?= $baseID ?>&week=<?= $week["week"]?>'>
                                    <button class='btn btn-primary btn-sm ml-3'><?= $action ?></button>
                                </a>
                            </div>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require GABARIT;
?>
