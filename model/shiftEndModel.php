<?php
/**
 * Auteur: Thomas Grossmann / Mounir Fiaux
 * Date: Mars 2020
 **/


function openShiftPage($id){
    try {
        $dbh = getPDO();
        $query = "update guardsheets set status_id= 2 WHERE id=:id";
        $statement = $dbh->prepare($query);//prepare query
        $statement->execute(["id" => $id]);//execute query
        //$queryResult = $statement->fetch(PDO::FETCH_ASSOC);//prepare result for client
        $dbh = null;
        return true;
        //return $queryResult;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return null;
    }
}

function reopenShiftPage($id)
{
    try {
        $dbh = getPDO();
        $query = "update guardsheets set status_id= 4 WHERE id=:id";
        $statement = $dbh->prepare($query);//prepare query
        $statement->execute(["id" => $id]);//execute query
        //$queryResult = $statement->fetch(PDO::FETCH_ASSOC);//prepare result for client
        $dbh = null;
        return true;
        //return $queryResult;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return null;
    }
}

function closeShiftPage($id)
{
    try {
        $dbh = getPDO();
        $query = "update guardsheets set status_id= 3 WHERE id=:id";
        $statement = $dbh->prepare($query);//prepare query
        $statement->execute(["id" => $id]);//execute query
        //$queryResult = $statement->fetchAll(PDO::FETCH_ASSOC);//prepare result for client
        $dbh = null;
        return true;
        //return $queryResult;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return null;
    }
}


function getGuardChecksForAction($action_id, $guardsheet_id, $day)
{
    $checks = selectMany('SELECT guardchecks.time, users.initials as initials FROM guardchecks inner join users on users.id = guardchecks.user_id where guardaction_id =:action_id and guardsheet_id =:guardsheet_id and day=:day', ['action_id' => $action_id, 'guardsheet_id' => $guardsheet_id, 'day' => $day]);
    return $checks;
}

function getGuardCommentsForAction($action_id, $guardsheet_id)
{
    $comments = selectMany('SELECT guardcomments.message, guardcomments.time, users.initials FROM guardcomments inner join users on users.id = guardcomments.user_id where guardaction_id =:action_id and guardsheet_id =:guardsheet_id', ['action_id' => $action_id, 'guardsheet_id' => $guardsheet_id]);
    return $comments;
}

function getActionsFromSection($sectionID)
{
    $sectionActions = selectMany('SELECT id, text FROM guardactions WHERE guardsection_id =:sectionID', ['sectionID' => $sectionID]);
    return $sectionActions;
}

function getGuardSections($shiftSheetID)
{
    $guardSections = selectMany('SELECT * FROM guardsections', []);
    foreach ($guardSections as &$section){
        $section["actions"] = getActionsFromSection($section["id"]);
        foreach ($section["actions"]  as &$action){
            $action['checksDay'] = getGuardChecksForAction($action["id"], $shiftSheetID,1);
            $action['checksNight'] = getGuardChecksForAction($action["id"], $shiftSheetID,0);
            $action["comments"] = getGuardCommentsForAction($action["id"], $shiftSheetID);
        }
    }
    return $guardSections;
}


function getGuardsheetForBase($base_id)
{
    return selectMany('SELECT guardsheets.id, guardsheets.date, guardsheets.base_id, status.displayname AS status,novaDay.number AS novaDay, novaNight.number AS novaNight, bossDay.initials AS bossDay, bossNight.initials AS bossNight,teammateDay.initials AS teammateDay, teammateNight.initials AS teammateNight
FROM guardsheets
INNER JOIN status ON status.id = guardsheets.status_id
LEFT JOIN novas novaDay ON novaDay.id = guardsheets.daynova_id
LEFT JOIN novas novaNight ON novaNight.id = guardsheets.nightnova_id
LEFT JOIN users bossDay ON bossDay.id = guardsheets.dayboss_id
LEFT JOIN users bossNight ON bossNight.id = guardsheets.nightboss_id
LEFT JOIN users teammateDay ON teammateDay.id = guardsheets.dayteammate_id
LEFT JOIN users teammateNight ON teammateNight.id = guardsheets.nightteammate_id
WHERE guardsheets.base_id =:base_id order by date DESC;', ["base_id" => $base_id]);
}

function getGuardsheetByID($id)
{
    return selectOne('SELECT bases.name as baseName, guardsheets.id, guardsheets.date, guardsheets.base_id, status.name AS status,novaDay.number AS novaDay, novaNight.number AS novaNight, bossDay.initials AS bossDay, bossNight.initials AS bossNight,teammateDay.initials AS teammateDay, teammateNight.initials AS teammateNight
FROM guardsheets
INNER JOIN bases ON bases.id = guardsheets.base_id
INNER JOIN status ON status.id = guardsheets.status_id
LEFT JOIN novas novaDay ON novaDay.id = guardsheets.daynova_id
LEFT JOIN novas novaNight ON novaNight.id = guardsheets.nightnova_id
LEFT JOIN users bossDay ON bossDay.id = guardsheets.dayboss_id
LEFT JOIN users bossNight ON bossNight.id = guardsheets.nightboss_id
LEFT JOIN users teammateDay ON teammateDay.id = guardsheets.dayteammate_id
LEFT JOIN users teammateNight ON teammateNight.id = guardsheets.nightteammate_id
WHERE guardsheets.id =:id;', ["id" => $id]);
}


function addNewShiftSheet($idBase)
{
    try {
        $date = getNewDate($idBase);
        if($date == null) {
            $insertGuardSheet = execute("Insert into guardsheets(date,status_id,base_id)
        values(current_timestamp(),:status_id,:idBase)", ['status_id' => 1, 'idBase' => $idBase]);
        }else{
            $insertGuardSheet = execute("Insert into guardsheets(date,status_id,base_id)
        values(:date,:status_id,:idBase)", ['date' => $date, 'status_id' => 1, 'idBase' => $idBase]);
        }
        if ($insertGuardSheet == false) {
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
    $lastDate = selectOne("SELECT MAX(date) FROM guardsheets where base_id = :baseID",['baseID'=>$baseID])["MAX(date)"];
    return $lastDate;
}

function getNewDate($baseID){
    $newDate = selectOne("SELECT DATE_ADD( :lastDate, INTERVAL 1 DAY) as newDate" ,['lastDate' => getDateOfLastSheet($baseID) ])["newDate"];
    return $newDate;
}

function getNbGuardSheet($status,$base_id){
    return selectOne("SELECT COUNT(guardsheets.id) as number FROM  guardsheets inner join status on status.id = guardsheets.status_id where status.slug = :status and guardsheets.base_id =:base_id", ['status' => $status, 'base_id' => $base_id])["number"];
}

