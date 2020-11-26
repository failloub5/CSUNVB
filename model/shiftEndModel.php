<?php
/**
 * Auteur: Thomas Grossmann / Mounir Fiaux
 * Date: Mars 2020
 **/


function getGuardSheets()
{
    return selectMany('SELECT * FROM guardsheets', []);
}

/**
 * Retourne un item précis, identifié par son id
 * ...
 */

function getGuardsheet($id)
{
    return selectOne("SELECT * FROM guardsheets where id =:id", ['id' => $id]);
}


function updateGuardsheet($id)
{
    return execute("UPDATE bases SET date = :date,state=:state,base_id=:base_id where id = :id", [$id]);

}

/**
 * Modifie un item précis
 * Le paramètre $item est un item complet (donc un tableau associatif)
 * ...
 */
function updateShiftEndItem($item)
{
    $items = getShiftEndItems();
    // TODO: retrouver l'item donnée en paramètre et le modifier dans le tableau $items
    saveShiftEndItem($items);
}

function openShiftPage($id){
    try {
        $dbh = getPDO();
        $query = "update guardsheets
set state='open' WHERE id=:id";
        $statement = $dbh->prepare($query);//prepare query
        $statement->execute(["id" => $id]);//execute query
        //$queryResult = $statement->fetch(PDO::FETCH_ASSOC);//prepare result for client
        $dbh = null;
        return true;
        //return $queryResult;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        return null;
    }
}

function reopenShiftPage($id)
{
    try {
        $dbh = getPDO();
        $query = "update guardsheets
set state='reopen' WHERE id=:id";
        $statement = $dbh->prepare($query);//prepare query
        $statement->execute(["id" => $id]);//execute query
        //$queryResult = $statement->fetch(PDO::FETCH_ASSOC);//prepare result for client
        $dbh = null;
        return true;
        //return $queryResult;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        return null;
    }
}

function closeShiftPage($id)
{
    try {
        $dbh = getPDO();
        $query = "update guardsheets
set state='close' WHERE id=:id";
        $statement = $dbh->prepare($query);//prepare query
        $statement->execute(["id" => $id]);//execute query
        //$queryResult = $statement->fetchAll(PDO::FETCH_ASSOC);//prepare result for client
        $dbh = null;
        return true;
        //return $queryResult;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        return null;
    }
}


/**
 * Détruit un item précis, identifié par son id
 * ...
 */
function destroyGuardsheet($id)
{
    return execute("DELETE * From guardsheets where id = :id", [$id]);

}

/**
 * Ajoute un nouvel item
 * Le paramètre $item est un item complet (donc un tableau associatif), sauf que la valeur de son id n'est pas valable
 * puisque le modèle ne l'a pas encore traité
 * ...
 */

function createGuardSheet($item)
{
    $items = getGuardsheets();
    $newid = max(array_keys($items)) + 1;
    $item["id"] = $newid;
    $items[] = $item;
    updateGuardsheet($items);
    return $item;
}

function createShiftEndItem($item)
{
    try {
        $dbh = getPDO();
        $query = "INSERT INTO guardsheets (base_id,state,date)
VALUES (:base_id,:state,:date)";
        $statement = $dbh->prepare($query);//prepare query
        $statement->execute($item);//execute query
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        return false;
    }
    /* $items = getShiftEndItems();
     // TODO: trouver un id libre pour le nouvel id et ajouter le nouvel item dans le tableau
     saveShiftEndItem($items);
     return ($item); // Pour que l'appelant connaisse l'id qui a été donné
    */
}

function getRemises()
{
    return selectMany('SELECT * FROM guardsections', []);
}

function getSectionsTitles()
{
    return selectMany('SELECT * FROM guardsections', []);
}

function getGuardLines()
{
    return selectMany('SELECT * FROM guardlines', []);
}

function getGuardContent($ligneID, $guardSheetID)
{
    return selectMany('SELECT guardcontents.comment, userDay.initials AS dayInitials, userNignt.initials AS nightInitials
    FROM guardcontents
    LEFT JOIN users userDay
    ON userDay.id = guardcontents.day_check_user_id
    LEFT JOIN users userNignt
    ON userNignt.id = guardcontents.night_check_user_id
    where guardcontents.guardsheet_id =:guardSheetID and guardcontents.guard_line_id =:ligneID', ['guardSheetID' => $guardSheetID, 'ligneID' => $ligneID]);
}

function getActionFromSection($sectionID)
{
    return selectMany('SELECT * FROM guardlines where guard_sections_id =:sectionID', ['sectionID' => $sectionID]);
}

function getGuardSections()
{
    return selectMany('SELECT id, title FROM guardsections', []);
}


function getGuardSheetsByBase($base_id)
{
    return selectOne('select * from guardsheets where base_id=:base_id', ['base_id' => $base_id]);
}

function getGuradSheetWeek($week, $base)
{
    return selectOne('SELECT * FROM guardsheets INNER JOIN bases ON bases.id=base_id WHERE week =:week AND base_id=:base', ['week' => $week, 'base' => $base]);
}

function Guardsheet()
{

    return selectMany('SELECT * FROM guardsheets;', []);

}

function getGuardsheetForBase($base_id)
{
    return selectMany('select
	date,
    state,
    base_id,
    id,
    (select initials from users inner join crews on users.id = crews.user_id where crews.guardsheet_id = guardsheets.id and crews.day = 1 and crews.boss = 1) as bossDay,
    (select initials from users inner join crews on users.id = crews.user_id where crews.guardsheet_id = guardsheets.id and crews.day = 0 and crews.boss = 1) as bossNight,
    (select initials from users inner join crews on users.id = crews.user_id where crews.guardsheet_id = guardsheets.id and crews.day = 1 and crews.boss = 0) as teammateDay,
    (select initials from users inner join crews on users.id = crews.user_id where crews.guardsheet_id = guardsheets.id and crews.day = 0 and crews.boss = 0) as teammateNight,
    (select number from novas inner join guard_use_nova on novas.id = guard_use_nova.nova_id where guard_use_nova.guardsheet_id = guardsheets.id and  guard_use_nova.day = 0) as novaNight,
    (select number from novas inner join guard_use_nova on novas.id = guard_use_nova.nova_id where guard_use_nova.guardsheet_id = guardsheets.id and  guard_use_nova.day = 1) as novaDay
    from guardsheets where base_id=:base_id', ["base_id" => $base_id]);
}

function getGuardsheetDetails($guardsheet_id)
{
    return selectOne('select
	date,
    state,
    base_id,
    name as base,
    (select initials from users inner join crews on users.id = crews.user_id where crews.guardsheet_id = guardsheets.id and crews.day = 1 and crews.boss = 1) as bossDay,
    (select initials from users inner join crews on users.id = crews.user_id where crews.guardsheet_id = guardsheets.id and crews.day = 0 and crews.boss = 1) as bossNight,
    (select initials from users inner join crews on users.id = crews.user_id where crews.guardsheet_id = guardsheets.id and crews.day = 1 and crews.boss = 0) as teammateDay,
    (select initials from users inner join crews on users.id = crews.user_id where crews.guardsheet_id = guardsheets.id and crews.day = 0 and crews.boss = 0) as teammateNight,
    (select number from novas inner join guard_use_nova on novas.id = guard_use_nova.nova_id where guard_use_nova.guardsheet_id = guardsheets.id and  guard_use_nova.day = 0) as novaNight,
    (select number from novas inner join guard_use_nova on novas.id = guard_use_nova.nova_id where guard_use_nova.guardsheet_id = guardsheets.id and  guard_use_nova.day = 1) as novaDay
    from guardsheets inner join bases on base_id = bases.id where guardsheets.id=:id', ["id" => $guardsheet_id]);
}

function addNewShiftSheet($idBase)
{
$dbh = getPDO();
    try {
        $insertGuardSheet = execute("Insert into guardsheets(date,state,base_id)
        values(current_timestamp(),:state,:idBase)", ['state' => "blank", 'idBase' => $idBase]);

        $gid = selectOne("SELECT MAX(id) FROM guardsheets",[]);
        $gid = $gid["MAX(id)"];
        $insertGuardUseNova = execute("Insert into guard_use_nova(nova_id,guardsheet_id,day)
        values(NULL,:guardsheetId,1), (NULL,:guardsheetId,0)", ['guardsheetId'=>$gid]);

        $insertCrews = execute("Insert into crews(boss,day,guardsheet_id,user_id)
values(NULL,0,:guardsheetId,NULL), (NULL,1,:guardsheetId,NULL)", ['guardsheetId'=>$gid]);
        if ($insertCrews == false || $insertGuardSheet == false || $insertGuardUseNova == false) {
            throw new Exception("L'enregistrement ne s'est pas effectué correctement");
        }
        $dbh = null;
    } catch (Exception $e) {
        echo 'Attention: ', $e->getMessage(), "\n";
        return false;
    }
    return true;


    /*Insert into guardsheets(date,state,base_id)
values(current_timestamp(),"blank",1)
;
Insert into guard_use_nova(nova_id,guardsheet_id,day)
values(1,151,1)
;
Insert into guard_use_nova(nova_id,guardsheet_id,day)
values(1,151,0)
;
Insert into crews(boss,day,guardsheet_id,user_id)
values(0,0,151,1)
;
Insert into crews(boss,day,guardsheet_id,user_id)
values(1,1,151,1)
;
*/
}


