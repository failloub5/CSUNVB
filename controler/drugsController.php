<?php
/**
 * Auteur: David Roulet / Fabien Masson + h
 * Date: Avril 2020 + 2020/11
 **/

//Affiche la page de selection de la semaine pour la base par défaut (du login)
function listdrug() {
    listdrugforbase($_SESSION['base']['id']);
}

//Affiche la page de selection de la semaine pour une base choisie
function listdrugforbase($baseID) {
    $bases = getbases();
    $drugSheetList = getDrugSheets($baseID);
    require_once VIEW . 'drugs/list.php';
}

// Affichage de la page finale
function showdrug($drugsheet_id) {
    $drugsheet = getDrugSheetById($drugsheet_id);
    $dates = getDaysForWeekNumber($drugsheet["week"]);
    $novas = getNovasForSheet($drugsheet_id);
    $BatchesForSheet = getBatchesForSheet($drugsheet_id); // Obtient la liste des batches utilisées par cette feuille
    foreach ($BatchesForSheet as $p) {
        $batchesByDrugId[$p["drug_id"]][] = $p;
    }
    $drugs = getDrugs();
    $site = getbasebyid($drugsheet['base_id'])['name'];
    require_once VIEW . 'drugs/show.php';
}

// Affiche le formulaire des pharmacheck et donne tout les ^donnée nessaiare
function pharmacheck() {
    $batch = $_GET["batch_id"];
    $sheet = $_GET["drugsheet_id"];
    $date = $_GET["date"];
    $batch = readBatch($batch);
    $sheet = readSheet($sheet);
    $druguse = readDrug($batch["drug_id"]);
    $base = getbasebyid($sheet["base_id"]);
    $user = $_SESSION['user'];
    $pharmacheck = getPharmaCheckByDateAndBatch($date, $batch["id"], $sheet["id"]);
    $date = strtotime("$date");
    $datefrom = date("Y-m-d", $date);
    $date = date("j F Y", $date);
    require_once VIEW . 'drugs/pharmacheck.php';
}

function PharmaUpdate()
{ // Met a jours é'enrigstrem des pharmacheck et vas sois mettre a jours sois crée un nouvelle enrgstment
    $batchtoupdate = $_GET["batchtoupdate"];
    $PharmaUpdateuser = $_GET["PharmaUpdateuser"];
    $sheetid = $_GET["sheetid"];
    $Pharmaend = $_POST["Pharmaend"];
    $Pharmastart = $_POST["Pharmastart"];
    $date = $_GET["date"];
    $pharmacheck = getPharmaCheckByDateAndBatch($date, $batchtoupdate, $sheetid);

    if ($pharmacheck == false) {
        $itemnew["date"] = $date;
        $itemnew["start"] = $Pharmastart;
        $itemnew["end"] = $Pharmaend;
        $itemnew["drugsheet_id"] = $sheetid;
        $itemnew["user_id"] = $PharmaUpdateuser;
        $itemnew["batch_id"] = $batchtoupdate;
        createPharmaCheck($itemnew);
    } else {
        $itemtoupdate = readPharmaCheck($pharmacheck["id"]);
        $itemtoupdate["date"] = $date;
        $itemtoupdate["start"] = $Pharmastart;
        $itemtoupdate["end"] = $Pharmaend;
        $itemtoupdate["drugsheet_id"] = $sheetid;
        $itemtoupdate["user_id"] = $PharmaUpdateuser;
        $itemtoupdate["batch_id"] = $batchtoupdate;
        updatePharmaCheck($itemtoupdate);
    }
    $sheet = readSheet($sheetid);
}

// récupérer la valeur de $item puis transférer les valeurs
function createDrugSheet() {
    $lastWeek = readLastWeekDrug($_GET['base']);
    insertDrugSheet($_GET['base'], $lastWeek['last_week']);
    unset($_POST['site']);
    unset($_POST['base']);
    require_once VIEW . 'main/home.php';
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
