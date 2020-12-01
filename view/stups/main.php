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
    <form action="?action=showStupSheetList" method="post" class="col">
        <select onchange="this.form.submit()" name="site" size="1">
            <?php
                foreach ($bases as $base) {
                    echo "<option value=" . $base["id"];
                    if($baseID == $base["id"]) echo " selected='selected'";
                    echo " name='site'>" . $base["name"];
                }
            ?>
        </select>
    </form>
    <div class="row m-2">
    </div>
    <div class="col">
        <form action="?action=newStupSheet" method="post">
            <input type="hidden" name="baseStup" value="<?= $baseID ?>">
            <?php
                if ($_SESSION['username']['admin'] == 1)
                    echo "<button type='submit' class='btn btn-primary m-1 float-right'>Nouvelle feuille de stupéfiants</button>";
            ?>
        </form>
    </div>
</div>
<div class="row">
    <table class="table table-bordered">
        <thead class="thead-dark">
        <th>Date</th>
        <th>État</th>
        <?php
            if ($_SESSION['username']['admin'] == 1)
                echo "<th>Action</th>"
        ?>
        </thead>
        <tbody>
        <?php
        foreach ($stupSheetList as $week) {
            echo "<tr>";
            echo "<td><a href='?action=showStupSheet&site=" . $baseID . "&week=". $week["week"] . "'>Semaine " . $week["week"] . "</a></td>";
            echo "<td>" . $week['state'] . "</td>";
            if ($_SESSION['username']['admin'] == 1) {
                echo "<td><div class='row'>";
                if ($week['state'] == "closed")
                    $action = "reopen";
                else if (($week['state'] == "open") || ($week['state'] == "reopened"))
                    $action = "close";
                else
                    $action = "open";
                echo "<a href='?action=" . $action . "StupSheet&site=" . $baseID . "&week=" . $week["week"] . "'>";
                echo "<button class='btn btn-primary btn-sm ml-3'>" . $action . "</button>";
                echo "</a></div></td>";
            }
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require GABARIT;
?>
