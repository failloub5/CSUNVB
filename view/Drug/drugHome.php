<?php
/**
 * Auteur: David Roulet / Fabien Masson
 * Date: Aril 2020
 **/

ob_start();
$title = "CSU-NVB - Stupéfiants";

?>

<div class="row m-2">
    <h1>Stupéfiants</h1>
</div>

<div class="row">
    <FORM action="?action=drugs" method="post" class="col">
        <SELECT onchange="this.form.submit()" name="site" size="1">
            <?php
            foreach ($bases as $base){ ?>
            <OPTION value="<?= $base["id"] ?>" <?php if ($base_id == $base["id"]) { ?> selected="selected"  <?php } ?>
                    name="site"><?= $base["name"] ?>

                <?php }
                ?>
        </SELECT>
    </FORM>

    <div class="row m-2">
        <?php
        foreach ($liste as $item) {
            if ($item["base_id"] == $base_id) {
                $weeks[] = $item;
            }
        } ?>
    </div>

    <div class="col">
        <form action="?action=addNewStup" method="post">
            <input type="hidden" name="baseStup" value="<?= $base_id ?>">
            <?php if ($_SESSION['username']['admin'] == 1) { ?>
                <button type="submit" class='btn btn-primary m-1 float-right'>Nouvelle feuille de stupéfiants</button>
            <?php } ?>
        </form>
    </div>
</div>

<div class="row">
    <table class="table table-bordered">
        <thead class="thead-dark">
        <th>Date</th>
        <th>État</th>
        <th>Action</th>
        </thead>
        <tbody>
        <?php
        foreach ($weeks as $week) { ?>
            <tr>
                <form action="?action=drugSiteTable&site=<?= $base_id ?>" method="post">
                    <td>
                        <button class="btn" name="semaine" value="<?= $week["week"] ?>"> <?php echo "Semaine " . $week["week"] ?> </button>
                    </td>
                    <td><?= $week['state'] ?></td>
                </form>
                <?php if ($_SESSION['username']['admin'] == 1) { ?>
                    <td>
                        <div class="row">
                        <?php if ($week['state'] == "closed") { ?>
                            <form action="?action=reopenStup" method="post">
                                <button class="btn btn-primary btn-sm ml-3" name="reopen" value="<?= $week['id'] ?>">Reopen</button>
                            </form>
                        <?php } else if (($week['state'] == "open") || ($week['state'] == "reopen")) { ?>
                            <form action="?action=closedStup" method="post">
                                <button class="btn btn-primary btn-sm ml-3" name="close" value="<?= $week['id'] ?>">Close</button>
                            </form>
                        <?php } else if ($week['state'] == "vierge") { ?>
                            <form action="?action=activateStup" method="post">
                                <button class="btn btn-primary btn-sm ml-3" name="activate" value="<?= $week['id'] ?>">Activate</button>
                            </form>
                        <?php } ?>
                        </div>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require GABARIT;
?>
