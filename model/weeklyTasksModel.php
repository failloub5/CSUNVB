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
    return selectOne("SELECT MAX(week) as 'last_week', id
                            FROM todosheets
                            Where base_id =:base_id
                            GROUP BY base_id",["base_id" => $base_id]);
}

function weeknew($base,$week)
{
    execute("INSERT INTO todosheets(week,state ,base_id)
                   VALUES('$week','close','$base')", []);

    $query = "SELECT id FROM todosheets 
                WHERE week = :week AND base_id = :base";

    return selectOne($query, ['week'=> $week, 'base'=> $base]);
}

function readTodoThingsForDay($sid, $day, $dayOfWeek)
{
    $res = selectMany("SELECT description, type, u.initials AS 'initials'
                             FROM todos 
                             INNER JOIN todothings t ON todos.todothing_id = t.id
                             LEFT JOIN users u ON todos.user_id = u.id
                             WHERE todosheet_id=:sid AND daything = :daything AND day_of_week = :dayofweek", ["sid" => $sid, "daything" => $day, "dayofweek" => $dayOfWeek]);
    return $res;
}

function readTodoForASheet($sheetID){
    $query =  "SELECT todothing_id, daything, day_of_week
                FROM todos
                INNER JOIN todothings ON todos.todothing_id = todothings.id
                INNER JOIN todosheets ON todos.todosheet_id = todosheets.id
                WHERE todosheet_id = :id";

    return selectMany($query, ['id' => $sheetID]);
}

function addtoDo($todoID, $weekID, $dayOfWeek){
    $query = "INSERT INTO todos (todothing_id, todosheet_id, day_of_week) VALUE (:todoID, :sheetID, :day)";
    execute($query, ['todoID' =>$todoID, 'sheetID' =>$weekID, 'day'=>$dayOfWeek]);
}

/**
 * Modifie un item précis
 * Le paramètre $item est un item complet (donc un tableau associatif)
 * ...
 */
function updateTodoSheet($id, $template_name)
{
    return execute(
        "UPDATE todosheets SET template_name=:template_name WHERE id =:id",['template_name'=>$template_name,'id'=>$id]);
}

function createTodoSheet($base_id, $lastWeek)
{
    return insert("INSERT INTO todosheets (base_id,state,week) VALUES (:base_id, 'blank', :lastWeek)", ["base_id" => $base_id, "lastWeek" => $lastWeek + 1]);
}

?>
