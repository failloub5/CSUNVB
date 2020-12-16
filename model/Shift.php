<?php
/**
 * Auteur: Thomas Grossmann / Mounir Fiaux
 * Date: Mars 2020
 **/


function openShiftPage($id){
    return execute("update shiftsheets set status_id= (select id from status where slug = 'open') WHERE id=:id",["id" => $id]);
}

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

function getShiftCommentsForAction($action_id, $shiftsheet_id)
{
    $comments = selectMany('SELECT shiftcomments.message, shiftcomments.carryOn, shiftcomments.id, shiftcomments.time, users.initials FROM shiftcomments inner join users on users.id = shiftcomments.user_id where shiftaction_id =:action_id and shiftsheet_id =:shiftsheet_id', ['action_id' => $action_id, 'shiftsheet_id' => $shiftsheet_id]);
    return $comments;
}

function getActionsFromSection($sectionID)
{
    $sectionActions = selectMany('SELECT id, text FROM shiftactions WHERE shiftsection_id =:sectionID', ['sectionID' => $sectionID]);
    return $sectionActions;
}

function getshiftsections($shiftSheetID)
{
    $shiftsections = selectMany('SELECT * FROM shiftsections', []);
    foreach ($shiftsections as &$section){
        $section["actions"] = getActionsFromSection($section["id"]);
        foreach ($section["actions"]  as &$action){
            $action['checksDay'] = getshiftchecksForAction($action["id"], $shiftSheetID,1);
            $action['checksNight'] = getshiftchecksForAction($action["id"], $shiftSheetID,0);
            $action["comments"] = getShiftCommentsForAction($action["id"], $shiftSheetID);
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
    return selectOne('SELECT bases.name as baseName, shiftsheets.id, shiftsheets.date, shiftsheets.base_id, status.slug AS status,novaDay.number AS novaDay, novaNight.number AS novaNight, bossDay.initials AS bossDay, bossNight.initials AS bossNight,teammateDay.initials AS teammateDay, teammateNight.initials AS teammateNight
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


function addNewShiftSheet($idBase)
{
    try {
        $date = getNewDate($idBase);
        if($date == null) {
            $insertshiftsheet = execute("Insert into shiftsheets(date,status_id,base_id)
        values(current_timestamp(),:status_id,:idBase)", ['status_id' => 1, 'idBase' => $idBase]);
        }else{
            $insertshiftsheet = execute("Insert into shiftsheets(date,status_id,base_id)
        values(:date,:status_id,:idBase)", ['date' => $date, 'status_id' => 1, 'idBase' => $idBase]);
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