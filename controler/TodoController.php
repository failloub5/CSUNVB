<!--
COMMENTAIRES
-->
<?php

/** Fonction qui permet l'affichage des semaines de tâches pour la base par défaut (où on est loggé)
 */
function listtodo(){
    listtodoforbase($_SESSION['base']['id']);
}

/** Fonction qui permet l'affichage des semaines de tâches pour une base spécifique
 * @param $selectedBaseID : l'ID de la base dont les semaines sont à afficher
 */
function listtodoforbase($selectedBaseID){
    $weeksNbrList = getClosedWeeks($selectedBaseID); // La liste des numéros de semaines qui sont fermées
    $activeWeek = getOpenedWeeks($selectedBaseID);  // Le numero de la semaine active
    $baseList = getbases();
    require_once VIEW . 'todo/list.php';
}

/**
 * Fonction qui affiche les tâches d'une semaine spécifique
 * @param $todo_id : l'ID de la feuille de tâche à afficher
 */
function showtodo($todo_id){
    $week = getTodosheetByID($todo_id);
    $base = getbasebyid($week['base_id']);
    $dates = getDaysForWeekNumber($week['week']);

    /** Test pour vérifier si une autre feuille est déjà ouverte */
    $alreadyOpen = true;
    if(empty(getOpenedWeeks($base['id']))){
        $alreadyOpen = false;
    }

    for ($daynight=0; $daynight <= 1; $daynight++) {
        for ($dayofweek = 1; $dayofweek <= 7; $dayofweek++) {
            $todoThings[$daynight][$dayofweek] = readTodoThingsForDay($todo_id,$daynight,$dayofweek);
            foreach ($todoThings[$daynight][$dayofweek] as $key => $todoThing){
                if($todoThing['type'] == "2" && !is_null($todoThing['value'])){
                    $todoThings[$daynight][$dayofweek][$key]['description'] = str_replace("....", "".$todoThing['value']."", "".$todoThing['description']."");
                }
            }

        }
    }

    $days = [1 => "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];

    require_once VIEW . 'todo/show.php';
}


/**
 * Fonction qui ajoute à la bbd dans todosheets les données relative à base_id et week
 * @param $base : id de la base
 */
function addWeek(){
    $base = $_SESSION['base']['id']; // On ne peut ajouter une feuille qu'à la base où on se trouve
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

    setFlashMessage("La semaine ".$week['last_week']." a été créée.");
    listtodoforbase($base);
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
function reopenweek($todo_id){
    openWeeklyTasks($todo_id);
    setFlashMessage("La semaine a été ouverte.");
    showtodo($todo_id);
}

/**
 * Function qui ferme une semaine ouverte et renvoie sur la liste des semaines
 * @param $baseID : l'ID de la base à laquelle appartient la semaine
 * @param $weekID : l'ID de la semaine a fermer
 */
function closeweek($todo_id){
    $week = getTodosheetByID($todo_id);

    closeWeeklyTasks($todo_id);
    setFlashMessage("La semaine ".$week['week']." a été clôturée.");
    listtodoforbase($week['base_id']);
}

function modelWeek($weekID, $template_name){
    updateTodoSheet($weekID,$template_name);
    $currentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    header('Location: '.$currentURL);
}

function loadAModel($weekID, $template_name){
    $toDos = readTodoForASheet($week['id']);  // TODO (noté par XCL) : corriger ce code qui ne fait rien
}

function switchTodoStatus(){
    $status = $_POST['modal-todoStatus'];
    $todoID = $_POST['modal-todoID'];

    if($status == 'open'){
        unvalidateTodo($todoID);
    } else {
        validateTodo($todoID);
    }
}
