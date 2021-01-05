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
            $messageQuittance = 'Vous êtes sur le point de quittancer la tâche suivante : <br> "' . $desription . '".';
            return "<button type='button' class='btn btn-secondary toggleTodoModal btn-block m-1' data-title='Quittancer une tâche' data-id='" . $taskID . "' data-status='close' data-type='" . $type . "' data-content='" . $messageQuittance . "'>" . $desription . "<div class='bg-white rounded mt-1'><br></div></button>";
        } else {
            $messageQuittance = 'Vous êtes sur le point de retirer la quittance de la tâche suivante : <br> "' . $desription . '".';
            return "<button type='button' class='btn btn-success toggleTodoModal btn-block m-1' data-title='Retirer une quittance' data-id='" . $taskID . "' data-status='open' data-type='" . $type . "' data-content='" . $messageQuittance . "'>" . $desription . "<div class='text-dark bg-white rounded mt-1'>" . $initials . "</div></button>";
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

// todo (VB) : supprimer dès que les vues 'list' sont homogènes
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

function affichageDebug($var) // todo : supprimer pour la release
{
    echo "<pre>", var_dump($var), "</pre>";
}

/**
 * @param $slug
 * @return string
 */
function showState($slug)
{
    switch ($slug) {
        case "blank":
            return "en préparation";
        case "open":
            return "active(s)";
        case "reopen":
            return "en correction";
        case "close":
            return "fermée(s)";
        case "archive":
            return "archivée(s)";
        default:
            return "[Non défini]";
    }
}

function showSheetsTodoByStatus($slug, $sheets)
{
    switch ($slug) {
        case "blank":
            $html = "<div class='slugBlank'>";
            break;
        case "open":
            $html = "<div class='slugOpen'>";
            break;
        case "reopen":
            $html = "<div class='slugReopen'>";
            break;
        case "close":
            $html = "<div class='slugClose'>";
            break;
        case "archive":
            $html = "<div class='slugArchive'>";
            break;
        default:
            $html = "<div>";
            break;
    }

    $html = $html."<h3>Semaine(s) " . showState($slug) . "</h3>
                    <button class='btn dropdownButton'><i class='fas fa-caret-square-down' data-list='".$slug."' style='font-size: 1.75rem'></i></button>
                    </div>";

    if (!empty($sheets)) {
        $html = $html . "<div class='".$slug."Sheets'><table class='table table-bordered'>
                        <thead class='thead-dark'><th>Semaine n°</th><th class='actions'>Actions</th></thead>
                        <tbody>";

        $actionDetail = "";

        foreach ($sheets as $sheet) {

            $html = $html . "<tr> <td>Semaine " . $sheet['week'];

            if ($_SESSION['user']['admin'] == 1 && (isset($week['template_name']))) {
                $html = $html . "<i class='fas fa-file-alt' title='" . $week['template_name'] . "'></i>";
            }

            $html = $html . "<td><div class='d-flex justify-content-around'>
                                <form>
                                    <input type='hidden' name='action' value='showtodo'>
                                    <input type='hidden' name='id' value='" . $sheet['id'] . "'>
                                    <button type='submit' class='btn btn-primary'>Détails</button>
                                </form>
                            " . slugsButtonTodo($slug, $sheet['id']) . "</div></td>";
        }

        $html = $html . "</tr> </tbody> </table></div>";

    } else {
        $html = $html."<div class='".$slug."Sheets'><p>Aucune feuille de tâche n'est actuellement " . showState($slug) . ".</p></div>";
    }

    return $html;
}

/**
 * @param $slug
 * @param $sheetID
 * @return string
 */
function slugsButtonTodo($slug, $sheetID)
{
    $buttons = "";
    switch ($slug) {
        case "blank":
            if(ican('opensheet')){
                $buttons = $buttons . "<form  method='POST' action=''>
                    <input type='hidden' name='id' value='" . $sheetID . "'>
                    <button type='submit' class='btn btn-primary'>Activer</button>
                    </form>";
            }
        case "archive":
            if(ican('deletesheet')) {
                $buttons = $buttons . "<form  method='POST' action=''>
                    <input type='hidden' name='id' value='" . $sheetID . "'>
                    <button type='submit' class='btn btn-primary'>Supprimer</button>
                    </form>";
            }
            break;
        case "open":
            if(ican('closesheet')) {
                $buttons = $buttons . "<form  method='POST' action=''>
                    <input type='hidden' name='id' value='" . $sheetID . "'>
                    <button type='submit' class='btn btn-primary'>Fermer</button>
                    </form>";
            }
            break;
        case "reopen":
            if(ican('closesheet')) {
                $buttons = $buttons . "<form  method='POST' action=''>
                    <input type='hidden' name='id' value='" . $sheetID . "'>
                    <button type='submit' class='btn btn-primary'>Refermer</button>
                    </form>";
            }
            break;
        case "close":
            if(ican('opensheet')) {
                $buttons = $buttons . "<form  method='POST' action=''>
                    <input type='hidden' name='id' value='" . $sheetID . "'>
                    <button type='submit' class='btn btn-primary'>Corriger</button>
                    </form>";
            }
            if(ican('archivesheet')) {
                $buttons = $buttons . "<form  method='POST' action=''>
                    <input type='hidden' name='id' value='" . $sheetID . "'>
                    <button type='submit' class='btn btn-primary'>Archiver</button>
                    </form>";
            }
            break;
        default:
            break;
    }
    return $buttons;

}