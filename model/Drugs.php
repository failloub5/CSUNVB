<?php
//-------------------------------------- admin --------------------------------------------
/**
 * Retourne la liste des médicaments connus (table 'drugs')
 */
function getDrugs() {
    return selectMany("SELECT * FROM drugs");
}

function addNewDrug($drugName) {
    return insert("INSERT INTO drugs (name) values ('$drugName')");
}

function updateDrugName($updatedName, $drugID) {
    return execute("UPDATE drugs SET name='$updatedName' WHERE id='$drugID'");
}

//-------------------------------------- drugs --------------------------------------------

/**
 *  Retourne les sheet en fonction de la semaine et de la base
 */
function getSheetByWeek($week, $base) {
    return selectMany("SELECT drugsheets.id AS drugsheet_id FROM drugsheets INNER JOIN bases ON bases.id=base_id WHERE week ='$week' AND base_id='$base'");
}

/**
 *  Retourne une sheet précise
 */
function getDrugSheetById($sheet_id) {
    return selectOne("SELECT * FROM drugsheets WHERE id = :id",['id' => $sheet_id]);
}

/**
 * Retourne la liste des drugsheets pour une base donnée.
 */
function getDrugSheets($base_id) {
    return selectMany("SELECT id, week, state FROM drugsheets WHERE base_id=:id", ['id' => $base_id]);
}

/**
 * Retourne la liste des novas 'utilisées' par cette feuille
 * Les données retournées sont dans un tableau indexé par id (i.e: [ 12 => [ "id" => 12, "value" => ...], 17 => [ "id" => 17, "value" => ...] ]
 */
function getNovasForSheet($drugSheetID) {
    return selectMany("SELECT novas.id as id, number FROM novas INNER JOIN drugsheet_use_nova ON nova_id = novas.id WHERE drugsheet_id ='$drugSheetID'");
}

function getBatches() {
    return selectOne("SELECT * FROM batches");
}

/**
 * Retourne les batches de médicaments utilisés sur un rapport précis
 * @param $drugSheetID
 * @return array|mixed|null
 */
function getBatchesForSheet($drugSheetID) {
    return selectMany("SELECT batches.id AS id, number, drug_id FROM batches INNER JOIN drugsheet_use_batch ON batches.id = batch_id WHERE drugsheet_id='$drugSheetID'");
}

/**
 * Retourne un item précis, identifié par son id
 */
function readSheet($id) {
    return getDrugSheets($_GET["base"])[$id];
}

/**
 * Retourne un lot par son id
 */
function readBatch($id) {
    return selectOne("SELECT * FROM batches WHERE batches.id ='$id'");
}

/**
 * Retourne un item précis, identifié par son id
 */
//TODO: utiliser du SQL et pas un foreach ca serait quand meme pas mal
function readDrug($id) {
    $SheetsArray = getDrugs();
    $Sheet = $SheetsArray[$id];
    $batches = getBatches();
    foreach ($batches as $batch) {
        if ($id == $batch["drug_id"]) {
            $Sheet["batches"][] = $batch["number"];
        }
    }
    return $Sheet;
}

/**
 * Retourne le pharmacheck du jour donné pour un batch précis lors de son utilisation dans une drugsheet
 */
function getPharmaCheckByDateAndBatch($date, $batch, $drugSheetID) {
    $res = selectOne("SELECT start,end FROM pharmachecks WHERE date='$date' AND batch_id='$batch' AND drugsheet_id='$drugSheetID'");
    return $res;
}

/**
 * Retourne le novacheck du jour donné pour un médicament précis dans une nova lors de son utilisation dans une drugsheet
 */
function getNovaCheckByDateAndBatch($date, $drug, $nova, $drugSheetID) {
    return selectOne("SELECT start,end FROM novachecks WHERE date='$date' AND drug_id='$drug' AND nova_id='$nova' AND drugsheet_id='$drugSheetID'");
}

/**
 * Retourne le restock du jour donné pour un batch précis dans une nova lors de son utilisation dans une drugsheet
 */
function getRestockByDateAndDrug($date, $batch, $nova) {
    $res = selectOne("SELECT quantity FROM restocks WHERE date='$date' AND batch_id='$batch' AND nova_id='$nova'");
    return $res ? $res['quantity'] : ''; // chaîne vide si pas de restock
}

/**
 * Retourne un item précis, identifié par son id
 */
function readPharmaCheck($id) {
    $SheetsArray = getPharmaChecks();
    if (isset($SheetsArray[$id])) {
        $base = $SheetsArray[$id];
        return $base;
    } else {
        return false;
    }
}

/**
 * Crée un enrgistrement d un item precis
 */
function createPharmaCheck($item) {
    $items = getPharmaChecks();
    $newid = max(array_keys($items)) + 1;
    $item["id"] = $newid;
    $items[] = $item;
    updatepharmachecks($items);
    return $item;
}

/**
 * ??? un item precis
 */
function updatePharmaCheck($item) {
    $sheets = getPharmaChecks();
    $sheets[$item["id"]]["id"] = $item["id"];
    $sheets[$item["id"]]["date"] = $item["date"];
    $sheets[$item["id"]]["start"] = $item["start"];
    $sheets[$item["id"]]["end"] = $item["end"];
    $sheets[$item["id"]]["batch_id"] = $item["batch_id"];
    $sheets[$item["id"]]["user_id"] = $item["user_id"];
    $sheets[$item["id"]]["drugSheetID"] = $item["drugSheetID"];
    updatepharmachecks($sheets);
}

/**
 * Obtiens tout la liste des items
 */
function getPharmaChecks() {
    selectMany('SELECT * FROM pharmachecks');
}

function readLastWeekDrug($base_id) {
    return selectOne("SELECT base_id, MAX(week) as 'last_week' FROM drugsheets WHERE base_id ='$base_id' GROUP BY base_id");
}

function insertDrugSheet($base_id, $lastWeek) {
    $lastWeek++; //TODO: UTILISER LA FONCTION DE VICKY
    return insert("INSERT INTO drugsheets (base_id,state,week) VALUES ('$base_id', 'vierge', '$lastWeek')");
}

function updateSheetState($baseID, $week, $state) {
    return execute("UPDATE drugsheets SET state='$state' WHERE base_id='$baseID' AND week='$week'");
}
