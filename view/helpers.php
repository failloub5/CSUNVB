<?php
/**
 * Auteur: Thomas Grossmann
 * Date: Mars 2020
 **/

function getFlashMessage()
{
    if (isset($_SESSION['flashmessage'])) {
        $message = $_SESSION['flashmessage'];
        unset($_SESSION['flashmessage']);
        return '<div class="alert alert-info">' . $message . '</div>';
    } else {
        return null;
    }
}

function setFlashMessage($message)
{
    $_SESSION['flashmessage'] = $message;
}

function getDrugSheetStateButton($state)
{
    switch ($state) {
        case "closed":
            return "reopen";
        case "open":
        case "reopened":
            return "close";
        default:
            return "open";
    }
}

function buttonTask($initials, $desription, $taskID, $type, $weekState)
{
    if ($weekState == 'open') {
        if (empty($initials)) {
            $messageQuittance = 'Vous êtes sur le point de quittancer la tâche suivante : <br> "'.$desription.'".' ;
            return "<button type='button' class='btn btn-secondary toggleTodoModal btn-block m-1' data-title='Quittancer une tâche' data-id='".$taskID."' data-status='close' data-type='".$type."' data-content='" . $messageQuittance . "'>" . $desription . "<div class='bg-white rounded mt-1'><br></div></button>";
        } else {
            $messageQuittance = 'Vous êtes sur le point de retirer la quittance de la tâche suivante : <br> "'.$desription.'".' ;
            return "<button type='button' class='btn btn-success toggleTodoModal btn-block m-1' data-title='Retirer une quittance' data-id='".$taskID."' data-status='open' data-type='".$type."' data-content='" . $messageQuittance . "'>" . $desription . "<div class='text-dark bg-white rounded mt-1'>" . $initials . "</div></button>";
        }
    } else {
        if (empty($initials)) {
            return "<button type='button' class='btn btn-warning btn-block m-1' disabled >" . $desription . "<div class='bg-white rounded mt-1'><br></div></button>";
        } else {
            return "<button type='button' class='btn btn-success btn-block m-1' disabled >" . $desription . "<div class='text-dark bg-white rounded mt-1'>" . $initials . "</div></button>";
        }
    }
}


/**
 * Retourne la date formatée pour l'affichage
 * @param $date au format standard YYYY-MM-DD HH:ii:ss
 * @param $format un de quelques formats prédéfinis que l'on utilise dans l'appli
 */
function displayDate($date, $format)
{
    switch ($format) {
        case 1:
            return strftime('%A %e %b %Y', strtotime($date)); // Complet avec le jour
        default:
            return strftime('%e %b %Y', strtotime($date)); // Complet sans le jour
    }
}

/**
 * Si l'utilisateur n'est pas administrateur, affiche une erreur et le redirige sur la page d'accueil
 */
function isAdmin()
{
    if ($_SESSION['user']['admin'] == 0) {
        setFlashMessage("Vous n'êtes pas autorisé à effectuer cette action !");
        return false;
    }
    return true;
}

function actionForStatus($status)
{
    switch ($status) {
        case "blank":
            return "Ouvrir";
        case "open":
            return "Fermer";
        case "close":
            return "Réouvrir";
        case "reopen":
            return "Fermer";
        default:
            return "action indéterminée";
    }
}

function affichageDebug($var)
{
    echo "<pre>", var_dump($var), "</pre>";
}

?>
