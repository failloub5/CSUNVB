<?php

/**
 * Fonction permettant de récupérer l'ensemble des informations d'une semaine grâce à son ID
 * @param $id : l'ID de la semaine à retrouver
 * @return array|mixed|null
 */
function getTodosheetByID($id)
{
    return selectOne("SELECT * FROM todosheets where id =:id", ['id' => $id]);
}

/**
 * Fonction permettant de récupérer l'ensemble des informations d'une semaine pour une base et une semaine précise
 * @param $id : l'ID de la semaine à retrouver
 * @return array|mixed|null
 */
function getTodosheetByBaseAndWeek($base_id, $weeknb)
{
    return selectOne("SELECT * FROM todosheets where base_id =:id and week = :weeknb", ['id' => $base_id, 'week' => $weeknb]);
}

/**
 * Fonction permettant de rechercher dans la base de données lu numéro et l'id des semaines fermées pour une base spécifique
 * @param $baseID : l'ID de la base dont on cherche les semaines fermées
 * @return array|mixed|null
 */
function getClosedWeeks($baseID)
{
    $query = "SELECT t.week, t.id, t.template_name FROM todosheets t JOIN bases b ON t.base_id = b.id WHERE b.id = :baseID AND t.state = 'close' ORDER BY t.week DESC;";
    return selectMany($query, ['baseID' => $baseID]);
}

/**
 * Fonction permettant de rechercher dans la base de données lu numéro et l'id de la semaine ouverte pour une base spécifique
 * @param $baseID : l'ID de la base dont on cherche la semaine ouverte
 * @return array|mixed|null
 */
function getOpenedWeeks($baseID)
{
    $query = "SELECT t.week, t.id, t.template_name FROM todosheets t JOIN bases b ON t.base_id = b.id WHERE b.id = :baseID AND t.state = 'open';";
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
                            GROUP BY base_id", ["base_id" => $base_id]);
}

function weeknew($base, $week)
{
    // todo : remplacer la requete execute par une requete insert !
    execute("INSERT INTO todosheets(week,state ,base_id)
                   VALUES('$week','close','$base')", []);

    $query = "SELECT id FROM todosheets 
                WHERE week = :week AND base_id = :base";

    return selectOne($query, ['week' => $week, 'base' => $base]);
}

function readTodoThingsForDay($sid, $day, $dayOfWeek)
{
    $res = selectMany("SELECT description, type, value, u.initials AS 'initials', todos.id AS id
                             FROM todos 
                             INNER JOIN todothings t ON todos.todothing_id = t.id
                             LEFT JOIN users u ON todos.user_id = u.id
                             WHERE todosheet_id=:sid AND daything = :daything AND day_of_week = :dayofweek", ["sid" => $sid, "daything" => $day, "dayofweek" => $dayOfWeek]);
    return $res;
}

function readTodoForASheet($sheetID)
{
    $query = "SELECT todothing_id, daything, day_of_week
                FROM todos
                INNER JOIN todothings ON todos.todothing_id = todothings.id
                INNER JOIN todosheets ON todos.todosheet_id = todosheets.id
                WHERE todosheet_id = :id";

    return selectMany($query, ['id' => $sheetID]);
}

function addtoDo($todoID, $weekID, $dayOfWeek)
{
    $query = "INSERT INTO todos (todothing_id, todosheet_id, day_of_week) VALUE (:todoID, :sheetID, :day)";
    execute($query, ['todoID' => $todoID, 'sheetID' => $weekID, 'day' => $dayOfWeek]);
}

function updateTemplateName($id, $template_name)
{
    return execute(
        "UPDATE todosheets SET template_name=:template_name WHERE id =:id", ['template_name' => $template_name, 'id' => $id]);
}

function deleteTemplateName($id)
{
    return execute(
        "UPDATE todosheets SET template_name=NULL WHERE id =:id", ['id' => $id]);
}

function createTodoSheet($base_id, $lastWeek)
{
    return insert("INSERT INTO todosheets (base_id,state,week) VALUES (:base_id, 'blank', :lastWeek)", ["base_id" => $base_id, "lastWeek" => $lastWeek + 1]);
}

function unvalidateTodo($id, $type)
{
    if ($type == 1) {
        return execute("UPDATE todos SET user_id=NULL WHERE id=:id", ['id' => $id]);
    } else {
        return execute("UPDATE todos SET user_id=NULL, value=NULL WHERE id=:id", ['id' => $id]);
    }

}

function validateTodo($id, $value)
{
    $initials = $_SESSION['user']['initials'];
    $user = getUserByInitials($initials);

    if (!empty($value)) {
        return execute("UPDATE todos SET user_id=:userID, value=:value WHERE id=:id;", ['userID' => $user['id'], 'value' => $value, 'id' => $id]);
    } else {
        return execute("UPDATE todos SET user_id=:userID WHERE id=:id;", ['userID' => $user['id'], 'id' => $id]);
    }
}

function getTemplate_name($id)
{
    $query = "SELECT template_name 
             FROM todosheets
             WHERE id = :id";
    return selectOne($query, ['id' => $id]);
}

function getTodosheetMaxID($selectedBaseID)
{
    $query = "SELECT MAX(id) AS id
              FROM todosheets
              WHERE base_id =:id";
    return selectOne($query, ['id' => $selectedBaseID]);
}

function getTemplates_name($selectedBaseID)
{
    $query = "SELECT template_name, id 
             FROM todosheets
             WHERE base_id = :id AND template_name is NOT NULL";
    return selectMany($query, ['id' => $selectedBaseID]);
}

function readLastWeekTemplate($Template_name)
{
    return selectOne("SELECT id, week AS last_week
                            FROM todosheets
                            Where template_name =:Template_name", ["Template_name" => $Template_name]);
}
?>