<?php
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
                   VALUES('$week','closen','$base')", []);
}
?>