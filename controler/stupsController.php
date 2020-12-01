<?php
/**
 * Auteur: David Roulet / Fabien Masson + h
 * Date: Avril 2020 + 2020/11
 **/

//Affiche la page de selection de la semaine
function showStupSheetList($baseID) {
    $bases = getbases();
    $stupSheetList = getStupSheets($baseID);
    require_once VIEW . 'stups/main.php';
}

// Affichage de la page finale
function showStupSheet() {
    $jourDebutSemaine = getDatesFromWeekNumber($_GET["week"]);
    $stupSheetID = getSheetByWeek($_GET["week"], $_GET["site"])["stupsheet_id"]; //TODO: site or base??
    $novas = getNovasForSheet($stupSheetID);

    $BatchesForSheet = getBatchesForSheet($stupSheetID); // Obtient la liste des batches utilisées par cette feuille

    foreach ($BatchesForSheet as $p) {
        $batchesByDrugId[$p["drug_id"]][] = $p;
    }
    $listofbaseid = getStupSheets($_GET["site"]);
    $stups = getStups();
    $site = getbasebyid($_GET["site"])["name"];
    require_once VIEW . 'stups/table.php';
}

// Affiche le formulaire des pharmacheck et donne tout les ^donnée nessaiare
function pharmacheck() {
    $batch = $_GET["batch_id"];
    $sheet = $_GET["stupsheet_id"];
    $date = $_GET["date"];
    $batch = readBatch($batch);
    $sheet = readSheet($sheet);
    $druguse = readStup($batch["drug_id"]);
    $base = getbasebyid($sheet["base_id"]);
    $user = $_SESSION["username"];
    $pharmacheck = getPharmaCheckByDateAndBatch($date, $batch["id"], $sheet["id"]);
    $date = strtotime("$date");
    $datefrom = date("Y-m-d", $date);
    $date = date("j F Y", $date);
    require_once VIEW . 'stups/pharmacheck.php';
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
        $itemnew["stupsheet_id"] = $sheetid;
        $itemnew["user_id"] = $PharmaUpdateuser;
        $itemnew["batch_id"] = $batchtoupdate;
        createPharmaCheck($itemnew);
    } else {
        $itemtoupdate = readPharmaCheck($pharmacheck["id"]);
        $itemtoupdate["date"] = $date;
        $itemtoupdate["start"] = $Pharmastart;
        $itemtoupdate["end"] = $Pharmaend;
        $itemtoupdate["stupsheet_id"] = $sheetid;
        $itemtoupdate["user_id"] = $PharmaUpdateuser;
        $itemtoupdate["batch_id"] = $batchtoupdate;
        updatePharmaCheck($itemtoupdate);
    }
    $sheet = readSheet($sheetid);
}

// récupérer la valeur de $item puis transférer les valeurs
function createStupSheet() {
    $lastWeek = readLastWeekStup($_POST['baseStup']);
    insertStupSheet($_POST['baseStup'], $lastWeek['last_week']);
    unset($_POST['site']);
    unset($_POST['base']);
    require_once VIEW . 'main/home.php';
}

//TODO: replace with switch
function openStupSheet() {
    updateSheetState($_POST["baseID"], $_POST["week"], "open");
    require_once VIEW . 'main/home.php';
}

function closeStupSheet() {
    updateSheetState($_POST["baseID"], $_POST["week"], "closed");
    require_once VIEW . 'main/home.php';
}

function reopenStupSheet() {
    updateSheetState($_POST["baseID"], $_POST["week"], "reopened");
    require_once VIEW . 'main/home.php';
}

//Fonction groupe todolist
//TODO: remplacer par une seule fonction unifiée dans un même fichier
function getDatesFromWeekNumber($weekNumber){
    // ToDo : Valeurs en dur à enlever !
    $year = 2000 + intdiv($weekNumber,100);
    $week = $weekNumber%100;

    $dates = Array();
    $time = strtotime(sprintf("%4dW%02d", $year, $week));

    for($i = 0; $i < 7; $i++){
        $day = date(strtotime("+".$i." day", $time));
        $fullDate = strftime(' %A %e %b %Y', $day);
        $dates[] = $fullDate;
    }

    return $dates;
}
