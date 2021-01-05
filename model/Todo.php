<?php
/**Function for the daily task section**/


/**
 * Function that gets all data from a weekly sheet based on it's ID
 * @param $id : ID of the desired sheet
 * @return array|mixed|null
 */
function getTodosheetByID($id)
{
    /*return selectOne("SELECT * FROM todosheets where id =:id", ['id' => $id]);*/
    return selectOne("SELECT *
                             FROM todosheets
                             LEFT JOIN status ON todosheets.status_id = status.id
                             WHERE todosheets.id =:id", ['id' => $id]);

}



/**
 * Function that gets all data from a weekly sheet based on week number and base id *
 * @param $base_id : ID of the desired base
 * @param $weeknb : Number of the desired week. format: yynn where yy is 2 digit year and nn is week number
 * @return array|mixed|null
 */
function getTodosheetByBaseAndWeek($base_id, $weeknb)
{
    return selectOne("SELECT * FROM todosheets where base_id =:id and week = :weeknb", ['id' => $base_id, 'week' => $weeknb]);
}

/**
 * Function that gets all weekly sheets based on base ID and slug name
 * @param $baseID : ID of the desired base
 * @param $slug : Name of desired slug. Values: blank, open, close, reopen
 * @return array|mixed|null
 */
function getWeeksBySlugs($baseID, $slug)
{
    $query = "SELECT t.week, t.id, t.template_name
            FROM todosheets t
            JOIN bases b ON t.base_id = b.id
            JOIN status ON t.status_id = status.id
            WHERE b.id = :baseID AND status.slug =:slug
            ORDER BY t.week DESC;";
    return selectMany($query, ['baseID' => $baseID, 'slug' => $slug]);
}


//function getStateFromTodo($id){
//    return execute("SELECT status.slug FROM status LEFT JOIN todosheets ON todosheets.status_id = status.id WHERE todosheets.id =:sheetID", ["sheetID"=>$id]);
//}



/**
 * Function that sets status of specified weekly sheet to close
 * @param $id : ID of the week to close
 */
function closeWeeklyTasks($id)
{
    execute("UPDATE todosheets set status_id=3 WHERE id=:id", ['id' => $id]);
}

/**
 * Function that sets status of specified weekly sheet to open
 * @param $id : ID of the week to open
 */
function openWeeklyTasks($id)
{
    execute("UPDATE todosheets set status_id=2 WHERE id=:id", ['id' => $id]);
}

/**
 * Function that gets the latest week for a defined base
 * @param $base_id
 * @return array|mixed|null
 */
function readLastWeek($base_id)
{
    return selectOne("SELECT MAX(week) as 'last_week', MAX(id) AS 'id'
                            FROM todosheets
                            Where base_id =:base_id
                            GROUP BY base_id", ["base_id" => $base_id]);
}


/**
 * @param $base
 * @param $week
 * @return array|mixed|null
 */
function weeknew($base, $week)
{
    // todo : check if working
    return insert("INSERT INTO todosheets(week ,status_id ,base_id)
                   VALUES('$week','3','$base')", []);
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
    return insert("INSERT INTO todosheets (base_id,status_id,week) VALUES (:base_id, 1, :lastWeek)", ["base_id" => $base_id, "lastWeek" => $lastWeek + 1]);
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

function getTemplates_name()
{
    $query = "SELECT template_name, id 
             FROM todosheets
             WHERE template_name is NOT NULL";
    return selectMany($query, []);
}

function readLastWeekTemplate($Template_name)
{
    return selectOne("SELECT id, week AS last_week
                            FROM todosheets
                            Where template_name =:Template_name", ["Template_name" => $Template_name]);
}


function closeStatus(){
    return selectOne("SELECT slug
                      FROM status
                      Where slug = 'close'", []);

}