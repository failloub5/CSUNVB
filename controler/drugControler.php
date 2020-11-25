<?php
/**
 * Auteur: David Roulet / Fabien Masson
 * Date: Aril 2020
 **/

function drugHomePage($base_id) //Affiche la page de selection de la semaine
{
    $bases = getbases();
    $liste = getStupSheets();
    require_once VIEW . 'drug/drugHome.php';
}

function drugSiteTable()
{ // Affichage de la page finale

    $jourDebutSemaine = getdate2($_POST["semaine"]); // récupère les jours de la semaine en fonction de la date sélectionnée
    $stupSheetId = GetSheetbyWeek($_POST["semaine"], $_GET["site"])["stupsheet_id"]; // la feuille de stupéfiants à afficher
    $novas = getNovasForSheet($stupSheetId); // Obtient la liste des ambulances utilisées par cette feuille

    $drugs = getDrugs(); // Obtient la liste des drugs
    $BatchesForSheet = getBatchesForSheet($stupSheetId); // Obtient la liste des batches utilisées par cette feuille
    // preparer un tableau de batchs par médicament pour faciliter le travail de la vue
    foreach ($BatchesForSheet as $p) {
        $batchesByDrugId[$p["drug_id"]][] = $p;
    }

    $listofbaseid = getListOfStupSheets($_GET["site"]);
    $date = strtotime($jourDebutSemaine);
    $site = getbasebyid($_GET["site"])["name"];
    $semaine = $_POST["semaine"];
    $jours = array("Lundi", "Mardi", "Mercredi", "Jeudi", "vendredi", "samedi", "dimanche");
    require_once VIEW . 'drug/drugSiteTable.php';
}

function getdate2($semaine) //Donne les jours de la semaine Selectionée
{
    $anneeChoix = 2000 + substr($semaine, 0, 2);

    $semChoix = substr($semaine, 2, 2);

    $timeStampPremierJanvier = strtotime($anneeChoix . '-01-01');
    $jourPremierJanvier = date('w', $timeStampPremierJanvier);

//-- recherche du N° de semaine du 1er janvier -------------------
    $numSemainePremierJanvier = date('W', $timeStampPremierJanvier);

//-- nombre à ajouter en fonction du numéro précédent ------------
    $decallage = ($numSemainePremierJanvier == 1) ? $semChoix - 1 : $semChoix;
//-- timestamp du jour dans la semaine recherchée ----------------
    $timeStampDate = strtotime('+' . $decallage . ' weeks', $timeStampPremierJanvier);
//-- recherche du lundi de la semaine en fonction de la ligne précédente ---------
    $jourDebutSemaine = ($jourPremierJanvier == 1) ? date('d-m-Y', $timeStampDate) : date('d-m-Y', strtotime('last monday', $timeStampDate));
    return $jourDebutSemaine;
}

function LogStup()
{
    //affiche la page des logs avec les bonnes données
    $LogSheets = getLogsBySheet($_POST["LogStup"]);
    require_once VIEW . 'drug/LogStup.php';
}

function reopenStup()
{
    reopenStupPage($_POST["reopen"]);
    require_once VIEW . 'main/home.php';
}
function closedStup()
{
    closeStup($_POST["close"]);
    require_once VIEW . 'main/home.php';
}

function pharmacheck()
{ // Affiche le formulaire des pharmacheck et donne tout les ^donnée nessaiare
        $batch = $_GET["batch_id"];
        $sheet = $_GET["stupsheet_id"];
        $date = $_GET["date"];
    $batch = readbatche($batch);
    $sheet = readSheet($sheet);
    $druguse = readDrug($batch["drug_id"]);
    $base = getbasebyid($sheet["base_id"]);
    $user = $_SESSION["username"];
    $pharmacheck = getpharmacheckbydateandbybatch($date, $batch["id"], $sheet["id"]);
    $date = strtotime("$date");
    $datefrom = date("Y-m-d", $date);
    $date = date("j F Y", $date);
    require_once VIEW . 'drug/pharmacheck.php';
}

function PharmaUpdate()
{ // Met a jours é'enrigstrem des pharmacheck et vas sois mettre a jours sois crée un nouvelle enrgstment
    $batchtoupdate = $_GET["batchtoupdate"];
    $PharmaUpdateuser = $_GET["PharmaUpdateuser"];
    $sheetid = $_GET["sheetid"];
    $Pharmaend = $_POST["Pharmaend"];
    $Pharmastart = $_POST["Pharmastart"];
    $date = $_GET["date"];
    $pharmacheck = getpharmacheckbydateandbybatch($date, $batchtoupdate, $sheetid);

    if ($pharmacheck == false) {
        $itemnew["date"] = $date;
        $itemnew["start"] = $Pharmastart;
        $itemnew["end"] = $Pharmaend;
        $itemnew["stupsheet_id"] = $sheetid;
        $itemnew["user_id"] = $PharmaUpdateuser;
        $itemnew["batch_id"] = $batchtoupdate;
        createpharmacheck($itemnew);
    } else {
        $itemtoupdate = readpharmacheck($pharmacheck["id"]);
        $itemtoupdate["date"] = $date;
        $itemtoupdate["start"] = $Pharmastart;
        $itemtoupdate["end"] = $Pharmaend;
        $itemtoupdate["stupsheet_id"] = $sheetid;
        $itemtoupdate["user_id"] = $PharmaUpdateuser;
        $itemtoupdate["batch_id"] = $batchtoupdate;
        updatepharmacheck($itemtoupdate);
    }
    $sheet = readSheet($sheetid);
    //drugSiteTable($sheet["week"]);
}

function closedStupFromTable($baseId, $week)
{
    closeStupFromTable($baseId, $week);
    require_once VIEW . 'main/home.php';
}

function createSheetStup() {
    // récupérer la valeur de $item puis transférer les valeurs

    $lastWeek = readLastWeekStup($_POST['baseStup']);
    createStupsheet($_POST['baseStup'], $lastWeek['last_week']);
    unset($_POST['site']);
    unset($_POST['base']);
    require_once VIEW. 'main/home.php';
}

function activateStup()
{
    activateStupPage($_POST["activate"]);
    require_once VIEW . 'main/home.php';
}

function activateStupFromTable()
{
    activateStupPageFromTable($_GET['stupBaseId'], $_GET['stupPageWeek']);
    require_once VIEW . 'main/home.php';
}