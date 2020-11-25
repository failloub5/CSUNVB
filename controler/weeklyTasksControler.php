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
 * @param $weekID : l'ID de la semaine à afficher
 * @param $weekNbr : Le numéro de la semaine à afficher
 */
function showWeeklyTasks($baseID, $weekID, $weekNbr){
    $base = getbasebyid($baseID);
    $dates = getDatesFromWeekNumber($weekNbr);
    // Récupération des dates par rapport à la semaine
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
    setlocale(LC_TIME, 'fr'); // Besoin de vérifier que cette ligne ne posera aucun problème !
    $time = strtotime(sprintf("%4dW%02d", $year, $week));

    for($i = 0; $i < 7; $i++){
        $day = date(strtotime("+".$i." day", $time));
        $fullDate = strftime('%A %e %b %Y', $day);
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
        /*S'il pas de dernière semaine dans une base, il faut en créer 1*/
        $today = date("y.m.d");
        $good_format=strtotime ($today);
        /*echo date('W',$good_format);*/
        $week['last_week'] = date('W',$good_format);
    }else {
        /*Sinon ajouter 1 nouvelle semaine à celle déjà existante*/
        $week['last_week'] +=  1;
    }
    
    weeknew($base, $week['last_week']);
}

function openAWeek($baseID, $weekID, $weekNbr){
    $alreadyOpen = getOpenedWeeks($baseID);
    if(empty($alreadyOpen)){
        openWeeklyTasks($weekID);
        $_SESSION['flashmessage'] = "La semaine a été ouverte.";
    } else {
        // toDo : changement de couleur possible pour les messages d'erreur ?
        $_SESSION['flashmessage'] = "Une autre semaine est déjà ouverte.";
    }
    showWeeklyTasks($baseID, $weekID, $weekNbr);
}

function closeAWeek($baseID, $weekID, $weekNbr){
    closeWeeklyTasks($weekID);
    $_SESSION['flashmessage'] = "La semaine a été cloturée.";
    showWeeklyTasks($baseID, $weekID, $weekNbr);
}