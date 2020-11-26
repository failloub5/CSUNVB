<?php

/**
 * Fonction permettant de récupérer l'ensemble des informations d'une semaine grâce à son ID
 * @param $id : l'ID de la semaine à retrouver
 * @return array|mixed|null
 */
function getTodosheetsByID($id){
    return selectOne("SELECT * FROM todosheets where id =:id", ['id' => $id]);
}

/**
 * Fonction permettant de rechercher dans la base de données lu numéro et l'id des semaines fermées pour une base spécifique
 * @param $baseID : l'ID de la base dont on cherche les semaines fermées
 * @return array|mixed|null
 */
function getClosedWeeks($baseID)
{
    $query = "SELECT t.week, t.id FROM todosheets t JOIN bases b ON t.base_id = b.id WHERE b.id = :baseID AND t.state = 'close' ORDER BY t.week DESC;";
    return selectMany($query, ['baseID' => $baseID]);
}

/**
 * Fonction permettant de rechercher dans la base de données lu numéro et l'id de la semaine ouverte pour une base spécifique
 * @param $baseID : l'ID de la base dont on cherche la semaine ouverte
 * @return array|mixed|null
 */
function getOpenedWeeks($baseID)
{
    $query = "SELECT t.week, t.id FROM todosheets t JOIN bases b ON t.base_id = b.id WHERE b.id = :baseID AND t.state = 'open';";
    return selectOne($query, ['baseID' => $baseID]);
}

/**
 * Fonction permettant de fermer une semaine
 * @param $id : l'ID de la semaine à fermer
 */
function closeWeeklyTasks($id)
{
    execute("UPDATE todosheets set state='close' WHERE id=:id", ['id' => $id]);
}

/**
 * Fonction permettant d'ouvrir une semaine
 * @param $id : l'ID de la semaine à ouvrir
 */
function openWeeklyTasks($id)
{
    execute("UPDATE todosheets set state='open' WHERE id=:id", ['id' => $id]);
}

function readLastWeek($base_id)
{
    return selectOne("SELECT MAX(week) as 'last_week'  
                            FROM todosheets
                            Where base_id =:base_id
                            GROUP BY base_id",["base_id" => $base_id]);
}

function weeknew($base,$week)
{
    execute("INSERT INTO todosheets(week,state ,base_id)
                   VALUES('$week','close','$base')", []);
}

/** ================== Fonctions à vérifier =============== */
/** Crées par marwan.alhelo, David.Roulet & Gatien.Jayme */


function readTodoSheets()
{

    return selectMany("SELECT * FROM todosheets;", []);
}

/**
 * Retourne un item précis, identifié par son id
 * ...
 */

function readTodoSheet($id)
{
    return selectMany("SELECT * FROM todosheets where id='$id';", []);
}

/**
 * Modifie un item précis
 * Le paramètre $item est un item complet (donc un tableau associatif)
 * ...
 */
function updateTodoSheet($item)
{
    return execute("UPDATE todosheets SET
                    base_id=:base_id,
             state=:state,
                    week=:week,
                    WHERE id =:id", $item);
}


/**
 * Détruit un item précis, identifié par son id
 * ...
 */
function destroyTodoSheet($id)
{

    return execute("DELETE FROM todosheet WHERE id=:id", ["id" => $id]);
}


function createTodoSheet($base_id, $lastWeek)
{
    return insert("INSERT INTO todosheets (base_id,state,week) VALUES (:base_id, 'blank', :lastWeek)", ["base_id" => $base_id, "lastWeek" => $lastWeek + 1]);
}

function readTodoSheetsForBase($base_id)
{
    return selectMany("SELECT * FROM todosheets WHERE todosheets.base_id=:base_id", ["base_id" => $base_id]);
}

/** ------------------TODOS---------------------- */

/**
 * Retourne tous les todos
 *
 */
function todos()
{
    return selectMany("SELECT * FROM todos", []);
}

/** ------------------TODOTHINGS---------------------- */

/**
 * Retourne tous les todothings
 *
 */
function readTodoThings()
{
    return selectMany("SELECT * FROM todothings;", []);
}

/**
 * Retourne un item précis, identifié par son id
 * ...
 */
function readTodoThing($id)
{
    return selectMany("SELECT * FROM todothings where id=:id;", ["id" => $id]);
}

/**
 * Modifie un item précis
 * Le paramètre $item est un item complet (donc un tableau associatif)
 * ...
 */
function updateTodoThing($item)
{

    return execute("UPDATE todothings SET
                    daything=:daything,
             description=:description,
                    type=:type,
                    display_order=:display_order
                    WHERE id =:id", $item);
}

/**
 * Détruit un item précis, identifié par son id
 * ...
 */
function destroyTodoThing($id)
{
    return execute("DELETE FROM todothing WHERE id=:id", ["id" => $id]);
}

/**
 * Ajoute un nouvel item
 * Le paramètre $item est un item complet (donc un tableau associatif), sauf que la valeur de son id n'est pas valable
 * puisque le modèle ne l'a pas encore traité
 * ...
 */
function createTodoThing($item)
{
    return insert("INSERT INTO todothing (daything,description,type,display_order) VALUES (:daything,:description,:type,:display_order)", $item);
}


// WIP
function readTodoThingsForDay($sid, $day, $dayOfWeek)
{
    $res = selectMany("SELECT description, type 
                             FROM todos 
                             INNER JOIN todothings t on todothing_id = t.id where todosheet_id=:sid AND daything = :daything AND day_of_week = :dayofweek", ["sid" => $sid, "daything" => $day, "dayofweek" => $dayOfWeek]);
    return $res;
}

/* SELECT description, week, base_id
 * FROM todos
 * INNER JOIN todothings ON todos.todothing_id = todothings.id
 * INNER JOIN todosheets ON todos.todosheet_id = todosheets.id
 */

?>