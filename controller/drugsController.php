<?php
/**
 * Auteur: David Roulet / Fabien Masson + h
 * Date: Avril 2020 + 2020/11
 **/

//Affiche la page de selection de la semaine pour une base choisie
function listDrugSheets($baseID = null) {
    if (is_null($baseID))
        $baseID = $_SESSION["base"]["id"];
    $bases = getbases();
    $drugSheetList = getDrugSheets($baseID);
    require_once VIEW . 'drugs/list.php';
}

// Affichage de la page finale
function showDrugSheet($drugSheetID) {
    $drugsheet = getDrugSheetById($drugSheetID);
    $dates = getDaysForWeekNumber($drugsheet["week"]);
    $novas = getNovasForSheet($drugSheetID);
    $BatchesForSheet = getBatchesForSheet($drugSheetID); // Obtient la liste des batches utilisées par cette feuille
    foreach ($BatchesForSheet as $p) {
        $batchesByDrugId[$p["drug_id"]][] = $p;
    }
    $drugs = getDrugsInDrugSheet($drugSheetID);
    $site = getbasebyid($drugsheet['base_id'])['name'];
    $buttonState = getDrugSheetStateButton(getDrugSheetState($drugsheet["base_id"], $drugsheet["week"])["state"]);
    require_once VIEW . 'drugs/show.php';
}

function newDrugSheet($base) {
    $oldSheet = getLatestDrugSheetWeekNb($base);
    cloneLatestDrugSheet(insertDrugSheet($base, $oldSheet['week']), $oldSheet['id']);
    redirect("listDrugSheets", $base);
}

function hasOpenDrugSheet($baseID) {
    return boolval(getOpenDrugSheet($baseID));
}

//TODO: replace with switch
function openDrugSheet($base) {
    updateSheetState($base, $_GET["week"], "open");
    redirect("listDrugSheets", $base);
}

function closeDrugSheet($base) {
    updateSheetState($base, $_GET["week"], "closed");
    redirect("listDrugSheets", $base);
}

function reopenDrugSheet($base) {
    updateSheetState($base, $_GET["week"], "reopened");
    redirect("listDrugSheets", $base);
}
