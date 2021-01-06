<?php
/**
 * Auteur: Thomas Grossmann / Mounir Fiaux
 * Date: Mars 2020
 **/

/**
 * function to modify the status of the shiftsheet to open based on the sheet ID
 * @param $id : the id of the sheet
 * @return bool|null
 */
function openShiftPage($id){
    return execute("update shiftsheets set status_id= (select id from status where slug = 'open') WHERE id=:id",["id" => $id]);
}

/**
 * function to modify the status of the shiftsheet to reopen based on the sheet ID
 * @param $id : the id of the sheet
 * @return bool|null
 */
function reopenShiftPage($id)
{
    return execute("update shiftsheets set status_id= (select id from status where slug = 'open') WHERE id=:id",["id" => $id]);
}

function closeShiftPage($id)
{
    return execute("update shiftsheets set status_id= (select id from status where slug = 'close') WHERE id=:id",["id" => $id]);
}


function getshiftchecksForAction($action_id, $shiftsheet_id, $day)
{
    $checks = selectMany('SELECT shiftchecks.time, users.initials as initials FROM shiftchecks inner join users on users.id = shiftchecks.user_id where shiftaction_id =:action_id and shiftsheet_id =:shiftsheet_id and day=:day', ['action_id' => $action_id, 'shiftsheet_id' => $shiftsheet_id, 'day' => $day]);
    return $checks;
}

function getShiftCommentsForAction($action_id, $shiftsheet_id, $base_id)
{
    $comments = selectMany('SELECT shiftcomments.message, shiftcomments.carryOn, shiftcomments.id, shiftcomments.time, users.initials ,shiftsheets.date,shiftcomments.endOfCarryOn
FROM shiftcomments 
inner join users on users.id = shiftcomments.user_id
inner join shiftsheets on shiftsheets.id = shiftcomments.shiftsheet_id
WHERE shiftaction_id = :action_id AND (shiftsheets.id = :shiftsheet_id or ((carryOn = 1  AND ( (endOfCarryOn IS NULL AND :date > shiftsheets.date) OR :date BETWEEN shiftsheets.date AND endOfCarryOn)) and shiftsheets.base_id = :base_id))', ['action_id' => $action_id, 'shiftsheet_id' => $shiftsheet_id,'base_id' => $base_id, 'date' => getshiftsheetByID($shiftsheet_id)["date"]]);
    return $comments;
}

function getSelectedActions($sectionID,$model_id)
{
    $sectionActions = selectMany('SELECT shiftactions.* FROM shiftmodel_has_shiftaction
INNER JOIN shiftactions
ON shiftactions.id = shiftmodel_has_shiftaction.shiftaction_id
WHERE shiftmodel_id = :model_id AND shiftsection_id = :sectionID', ['sectionID' => $sectionID, 'model_id' => $model_id]);
    return $sectionActions;
}

function getNotSelectedActions($sectionID,$model_id)
{

}

function getshiftsections($shiftSheetID, $baseID)
{
    $shiftsections = selectMany('SELECT * FROM shiftsections', []);
    foreach ($shiftsections as &$section){
        $section["actions"] = getSelectedActions($section["id"],getshiftsheetByID($shiftSheetID)["shiftmodel_id"]);
        $section["unusedActions"] = getNotSelectedActions($section["id"],getshiftsheetByID($shiftSheetID)["shiftmodel_id"]);
        foreach ($section["actions"]  as &$action){
            $action['checksDay'] = getshiftchecksForAction($action["id"], $shiftSheetID,1);
            $action['checksNight'] = getshiftchecksForAction($action["id"], $shiftSheetID,0);
            $action["comments"] = getShiftCommentsForAction($action["id"], $shiftSheetID, $baseID);
        }
    }
    return $shiftsections;
}


function getshiftsheetForBase($base_id)
{
    return selectMany('SELECT shiftsheets.id, shiftsheets.date, shiftsheets.base_id, status.displayname AS status, status.slug AS statusslug,novaDay.number AS novaDay, novaNight.number AS novaNight, bossDay.initials AS bossDay, bossNight.initials AS bossNight,teammateDay.initials AS teammateDay, teammateNight.initials AS teammateNight
FROM shiftsheets
INNER JOIN status ON status.id = shiftsheets.status_id
LEFT JOIN novas novaDay ON novaDay.id = shiftsheets.daynova_id
LEFT JOIN novas novaNight ON novaNight.id = shiftsheets.nightnova_id
LEFT JOIN users bossDay ON bossDay.id = shiftsheets.dayboss_id
LEFT JOIN users bossNight ON bossNight.id = shiftsheets.nightboss_id
LEFT JOIN users teammateDay ON teammateDay.id = shiftsheets.dayteammate_id
LEFT JOIN users teammateNight ON teammateNight.id = shiftsheets.nightteammate_id
WHERE shiftsheets.base_id =:base_id order by date DESC;', ["base_id" => $base_id]);
}

function getshiftsheetByID($id)
{
    return selectOne('SELECT bases.name as baseName,bases.id as baseID, shiftsheets.id, shiftsheets.date, shiftsheets.base_id,shiftsheets.shiftmodel_id, status.slug AS status,novaDay.number AS novaDay, novaNight.number AS novaNight, bossDay.initials AS bossDay, bossNight.initials AS bossNight,teammateDay.initials AS teammateDay, teammateNight.initials AS teammateNight
FROM shiftsheets
INNER JOIN bases ON bases.id = shiftsheets.base_id
INNER JOIN status ON status.id = shiftsheets.status_id
LEFT JOIN novas novaDay ON novaDay.id = shiftsheets.daynova_id
LEFT JOIN novas novaNight ON novaNight.id = shiftsheets.nightnova_id
LEFT JOIN users bossDay ON bossDay.id = shiftsheets.dayboss_id
LEFT JOIN users bossNight ON bossNight.id = shiftsheets.nightboss_id
LEFT JOIN users teammateDay ON teammateDay.id = shiftsheets.dayteammate_id
LEFT JOIN users teammateNight ON teammateNight.id = shiftsheets.nightteammate_id
WHERE shiftsheets.id =:id;', ["id" => $id]);
}


function addNewShiftSheet($baseID)
{
    try {
        $date = getNewDate($baseID);
        if($date == null) {
            $insertshiftsheet = execute("INSERT INTO shiftsheets (shiftmodel_id,status_id,base_id) VALUES (1,1,:base)", ['base' => $baseID]);
        }else{
            $insertshiftsheet = execute("INSERT INTO shiftsheets (date,shiftmodel_id,status_id,base_id) VALUES (:date,1,1,:base)", ['date' => $date, 'base' => $baseID]);
        }
        if ($insertshiftsheet == false) {
            throw new Exception("L'enregistrement ne s'est pas effectué correctement");
        }
        $dbh = null;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
    return true;
}

function getDateOfLastSheet($baseID){
    $lastDate = selectOne("SELECT MAX(date) FROM shiftsheets where base_id = :baseID",['baseID'=>$baseID])["MAX(date)"];
    return $lastDate;
}

function getNewDate($baseID){
    $newDate = selectOne("SELECT DATE_ADD( :lastDate, INTERVAL 1 DAY) as newDate" ,['lastDate' => getDateOfLastSheet($baseID) ])["newDate"];
    return $newDate;
}

function getNbshiftsheet($status,$base_id){
    return selectOne("SELECT COUNT(shiftsheets.id) as number FROM  shiftsheets inner join status on status.id = shiftsheets.status_id where status.slug = :status and shiftsheets.base_id =:base_id", ['status' => $status, 'base_id' => $base_id])["number"];
}

function checkActionForShift($action_id,$shiftSheet_id,$day){
    return execute("Insert into shiftchecks(day,shiftsheet_id,shiftaction_id,user_id)values(:day,:shiftSheet_id,:action_id,:user_id)", ["day" => $day,"user_id" => $_SESSION['user']['id'],"shiftSheet_id" => $shiftSheet_id, "action_id" => $action_id]);
}

function commentActionForShift($action_id,$shiftSheet_id,$message){
    return execute("Insert into shiftcomments(shiftsheet_id,shiftaction_id,user_id,message)values(:shiftSheet_id,:action_id,:user_id,:message)", ["user_id" => $_SESSION['user']['id'],"shiftSheet_id" => $shiftSheet_id, "action_id" => $action_id, "message" => $message]);
}

function updateDataShift($id,$novaDay,$novaNight,$bossDay,$bossNight,$teammateDay,$teammateNight){
    if($novaDay=="NULL")$novaDay=null;
    if($novaNight=="NULL")$novaNight=null;
    if($bossDay=="NULL")$bossDay=null;
    if($bossNight=="NULL")$bossNight=null;
    if($teammateDay=="NULL")$teammateDay=null;
    if($teammateNight=="NULL")$teammateNight=null;
    return execute("update shiftsheets set daynova_id =:novaDay, nightnova_id =:novaNight, dayboss_id =:bossDay, nightboss_id =:bossNight, dayteammate_id =:teammateDay, nightteammate_id =:teammateNight WHERE id=:id",["id" => $id,"novaDay" => $novaDay,"novaNight" => $novaNight,"bossDay" => $bossDay,"bossNight" => $bossNight,"teammateDay" => $teammateDay,"teammateNight" => $teammateNight]);
}

function getStateFromSheet($id){
    return execute("SELECT status.slug FROM status LEFT JOIN shiftsheets ON shiftsheets.status_id = status.id WHERE shiftsheets.id =:sheetID", ["sheetID"=>$id]);
}

function addCarryOnComment($commentID){
    return execute("update shiftcomments set carryON = 1, endOfCarryOn = null where id=:commentID",["commentID"=>$commentID]);
}

function carryOffComment(){
    return execute("update shiftcomments set endOfCarryOn = :carryOff where id= :commentID",["commentID"=>$_POST["commentID"],"carryOff"=>$_POST["carryOff"]]);
}

