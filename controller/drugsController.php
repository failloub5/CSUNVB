<?php
/**
 * Auteur: David Roulet / Fabien Masson + h
 * Date: Avril 2020 + 2020/11
 **/

//Affiche la page de selection de la semaine pour une base choisie
function listDrugSheets($baseID = null) {
    $bases = getbases();
    if (is_null($baseID))
        $baseID = $_SESSION["base"]["id"];
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
    $drugs = getDrugs();
    $site = getbasebyid($drugsheet['base_id'])['name'];
    require_once VIEW . 'drugs/show.php';
}

// récupérer la valeur de $item puis transférer les valeurs
function newDrugSheet($base) {
    $lastWeek = readLastDrugSheet($base);
    insertDrugSheet($base, $lastWeek['lastWeek']);
    listDrugSheets($base);
}

//TODO: replace with switch
function openDrugSheet() {
    updateSheetState($_POST["baseID"], $_POST["week"], "open");
    require_once VIEW . 'main/home.php';
}

function closeDrugSheet() {
    updateSheetState($_POST["baseID"], $_POST["week"], "closed");
    require_once VIEW . 'main/home.php';
}

function reopenDrugSheet() {
    updateSheetState($_POST["baseID"], $_POST["week"], "reopened");
    require_once VIEW . 'main/home.php';
}
