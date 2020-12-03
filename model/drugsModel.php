<?php
//-------------------------------------- admin --------------------------------------------
/**
 * Retourne la liste des médicaments connus (table 'drugs')
 */
function getDrugs() {
    return execQuery("SELECT * FROM drugs", true, true);
}

function addNewDrug($drugName) {
    return execQuery("INSERT INTO drugs (name) values ('$drugName')");
}

function updateDrugName($updatedName, $drugID) {
    return execQuery("UPDATE drugs SET name='$updatedName' WHERE id='$drugID'");
}

//-------------------------------------- drugs --------------------------------------------

/**
 *  Retourne les sheet en fonction de la semaine et de la base
 */
function getSheetByWeek($week, $base) {
    return execQuery("SELECT drugsheets.id AS drugsheet_id FROM drugsheets INNER JOIN bases ON bases.id=base_id WHERE week ='$week' AND base_id='$base'", true, true);
}

/**
 * Retourne la liste des drugsheets pour une base donnée.
 */
function getDrugSheets($base) {
    return execQuery("SELECT * FROM drugsheets INNER JOIN bases ON bases.id=base_id WHERE base_id='$base'", true, true);
}

/**
 * Retourne la liste des novas 'utilisées' par cette feuille
 * Les données retournées sont dans un tableau indexé par id (i.e: [ 12 => [ "id" => 12, "value" => ...], 17 => [ "id" => 17, "value" => ...] ]
 */
function getNovasForSheet($drugSheetID) {
    return execQuery("SELECT novas.id as id, number FROM novas INNER JOIN nova_use_drugsheet ON nova_id = novas.id WHERE drugsheet_id ='$drugSheetID'", true, true);
}

function getBatches() {
    return execQuery("SELECT * FROM batches", true);
}

/**
 * Retourne la liste des batches 'utilisés' par cette feuille
 * Les données retournées sont dans un tableau indexé par drug_id
 * [
 *     12 => [
 *         ["id" => 32, "number" => "345543", "drug_id" => 12],
 *         ["id" => 12, "number" => "989966", "drug_id" => 12]
 *     ],
 *     15 => [
 *         ["id" => 6, "number" => "46328", "durg_id" => 15]
 *     ]
 * ]
 */
function getBatchesForSheet($drugSheetID) {
    return execQuery("SELECT * FROM batches INNER JOIN drugsheet_use_batch ON batches.id = batch_id WHERE drugsheet_id='$drugSheetID'", true, true);
}

/**
 * Retourne un item précis, identifié par son id
 */
function readSheet($id) {
    return getDrugSheets()[$id];
}

/**
 * Retourne un lot par son id
 */
function readBatch($id) {
    return execQuery("SELECT * FROM batches WHERE batches.id ='$id'", true);
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
    return execQuery("SELECT start,end FROM pharmachecks WHERE date='$date' AND batch_id='$batch' AND drugsheet_id='$drugSheetID'", true);
}

/**
 * Retourne le novacheck du jour donné pour un médicament précis dans une nova lors de son utilisation dans une drugsheet
 */
function getNovaCheckByDateAndBatch($date, $drug, $nova, $drugSheetID) {
    return execQuery("SELECT start,end FROM novachecks WHERE date='$date' AND drug_id='$drug' AND nova_id='$nova' AND drugsheet_id='$drugSheetID'", true);
}

/**
 * Retourne le restock du jour donné pour un batch précis dans une nova lors de son utilisation dans une drugsheet
 */
function getRestockByDateAndDrug($date, $batch, $nova) {
    return execQuery("SELECT quantity FROM restocks WHERE date='$date' AND batch_id='$batch' AND nova_id='$nova'", true);
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
    execQuery('SELECT * FROM pharmachecks', true, true);
}

function readLastWeekDrug($base_id) {
    return execQuery("SELECT base_id, MAX(week) as 'last_week' FROM drugsheets WHERE base_id ='$base_id' GROUP BY base_id", true);
}

function insertDrugSheet($base_id, $lastWeek) {
    $lastWeek++; //TODO: UTILISER LA FONCTION DE VICKY
    return execQuery("INSERT INTO drugsheets (base_id,state,week) VALUES ('$base_id', 'vierge', '$lastWeek')");
}

function updateSheetState($baseID, $week, $state) {
    return execQuery("UPDATE drugsheets SET state='$state' WHERE base_id='$baseID' AND week='$week'");
}

//TODO: A supprimer pour faire une/des fonctions commune(s) a tous les modeles, a voir avec les autres groupes
function execQuery($query, $isSelect = false, $fetchAll = false) {
    try {
        echo "Executing query " . $query . "...<br />";
        $statement = getPDO()->prepare($query);
        $statement->execute();
        if($isSelect) {
            if ($fetchAll) {
                return $statement->fetchAll(PDO::FETCH_ASSOC);
            }
            else {
                return $statement->fetch(PDO::FETCH_ASSOC);
            }
        }
        return true;
    }
    catch(PDOException $pdoe) {
        echo "Error ! " . $pdoe->getMessage();
        return false;
    }
}