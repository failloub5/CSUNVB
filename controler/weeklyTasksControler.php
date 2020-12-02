<!--
COMMENTAIRES
-->
<?php

/** Fonction qui permet l'affichage des semaines de tâches pour une base spécifique
 * @param $selectedBaseID : l'ID de la base dont les semaines sont à afficher
 */
function homeWeeklyTasks($selectedBaseID){
    $weeksNbrList = getClosedWeeks($selectedBaseID); // La liste des numéros de semaines qui sont fermées
    $activeWeek = getOpenedWeeks($selectedBaseID);  // Le numero de la semaine active

    $baseList = getbases();
    require_once VIEW . 'todo/homeWeeklyTasks.php';
}

/**
 * Fonction qui affiche les tâches d'une semaine spécifique
 * @param $baseID : l'ID de la base à laquelle appartient la semaine à afficher
 * @param $weekID : l'ID de la semaine à afficher
 */
function showWeeklyTasks($baseID, $weekID){
    $base = getbasebyid($baseID);
    $week = getTodosheetsByID($weekID);
    $dates = getDatesFromWeekNumber($week['week']);

    /** Test pour vérifier si une autre feuille est déjà ouverte */
    $alreadyOpen = true;
    if(empty(getOpenedWeeks($baseID))){
        $alreadyOpen = false;
    }

    // toDo : Affichage des tâches (Fusion des 2 vues d'affichage)
    for ($daynight=0; $daynight <= 1; $daynight++) {
        for ($dayofweek = 1; $dayofweek <= 7; $dayofweek++) {
            $todoThings[$daynight][$dayofweek] = readTodoThingsForDay($weekID,$daynight,$dayofweek);
        }
    }

    $days = [1 => "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];

    require_once VIEW . 'todo/detailsWeeklyTasks.php';
}

/**
 * Fonction qui retourne les dates des jours de la semaine, à partir d'un numéro de semaine
 * @param $weekNumber : Le numéro de la semaine dont on veut connaitre les dates
 * @return array : les dates de la semaine
 */
function getDatesFromWeekNumber($weekNumber){
    // ToDo : Valeurs en dur à enlever !
    $year = 2000 + intdiv($weekNumber,100);
    $week = $weekNumber%100;

    $dates = Array();
    $time = strtotime(sprintf("%4dW%02d", $year, $week));

    for($i = 0; $i < 7; $i++){
        $day = date(strtotime("+".$i." day", $time));
        $fullDate = strftime('%e %b %Y', $day);
        $dates[] = $fullDate;
    }

    return $dates;
}

/**
 * Fonction qui ajoute à la bbd dans todosheets les données relative à base_id et week
 * @param $base : id de la base
 */
function addWeek($base){
    //Lit la dernière semaine
    $week = readLastWeek($base);

    if(empty($week)){
        $week['last_week'] = date("yW"); // Affiche le numéro de la semaine actuelle dans le bon format
        // $week['id'] = 23; /** toDO : Semaine par défaut ? */
    }else {
        /*Sinon ajouter 1 nouvelle semaine à celle déjà existante*/
        $week['last_week'] = nextWeekNumber($week['last_week']);
    }

    $toDos = readTodoForASheet($week['id']);
    $newWeek = weeknew($base, $week['last_week']);

    foreach ($toDos as $todo) {

        addtoDo($todo['todothing_id'], $newWeek['id'],  $todo['day_of_week']);
    }

    $_SESSION['flashmessage'] = "La semaine ".$week['last_week']." a été créée.";
    homeWeeklyTasks($base);
}

/**
 * Fonction qui retourne le numéro de semaine de la semaine suivante
 * @param $weekNbr : le numéro de la semaine
 * @return false|string
 */
function nextWeekNumber($weekNbr){
    $year = 2000 + intdiv($weekNbr,100);
    $week = $weekNbr%100;

    $time = strtotime(sprintf("%4dW%02d", $year , $week));
    $nextWeek = date(strtotime("+ 1 week", $time));

    return  date("yW", $nextWeek);
}

/**
 * Function qui ouvre une semaine fermée et affiche sa vue détaillée
 * @param $baseID : l'ID de la base à laquelle appartient la semaine
 * @param $weekID : l'ID de la semaine a ouvrir
 */
function openAWeek($baseID, $weekID){
    openWeeklyTasks($weekID);
    $_SESSION['flashmessage'] = "La semaine a été ouverte.";
    showWeeklyTasks($baseID, $weekID);
}

/**
 * Function qui ferme une semaine ouverte et renvoie sur la liste des semaines
 * @param $baseID : l'ID de la base à laquelle appartient la semaine
 * @param $weekID : l'ID de la semaine a fermer
 */
function closeAWeek($baseID, $weekID){
    $week = getTodosheetsByID($weekID);

    closeWeeklyTasks($weekID);
    $_SESSION['flashmessage'] = "La semaine ".$week['week']." a été clôturée.";
    homeWeeklyTasks($baseID);
}

function modelWeek( $weekID, $model_name){
    updateTodoSheet($weekID,$model_name);
    $currentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    header('Location: '.$currentURL);
}


/** ================== Fonctions à vérifier =============== */
/** Crées par marwan.alhelo, David.Roulet & Gatien.Jayme */


function createSheetToDo($base_id)
{
    // récupérer la valeur de $item puis transférer les valeurs

    $lastWeek = readLastWeek($base_id);
    createTodoSheet($base_id, $lastWeek['last_week']);
    unset($_POST['site']);
    unset($_POST['base']);
    todoListHomePage($base_id);
}


function activateSheet($state)
{
    $activatestatus = activateTodoSheets($state);
}




