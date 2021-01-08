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

// todo (VB) : supprimer dès que les vues 'list' sont homogènes
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
            return "Activer";
        case "open":
            return "Fermer";
        case "close":
            return "Corriger";
        case "reopen":
            return "Refermer";
        default:
            return "Action indéterminée";
    }
}


function showState($slug, $plural = 0)
{
    switch ($slug) {
        case "blank":
            $result = "en préparation";
            break;
        case "open":
            $result = "active";
            if ($plural) {
                $result = $result . "(s)";
            }
            break;
        case "reopen":
            $result = "en correction";
            break;
        case "close":
            $result = "fermée";
            if ($plural) {
                $result = $result . "(s)";
            }
            break;
        case "archive":
            $result = "archivée";
            if ($plural) {
                $result = $result . "(s)";
            }
            break;
        default:
            $result = "[Non défini]";
            break;
    }

    return $result;
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

    $html = $html . "<h3>Semaine(s) " . showState($slug, 1) . "</h3>
                    <button class='btn dropdownButton'><i class='fas fa-caret-square-down' data-list='" . $slug . "' ></i></button>
                    </div>";

    if (!empty($sheets)) {
        $html = $html . "<div class='" . $slug . "Sheets'><table class='table table-bordered'>
                        <thead class='thead-dark'><th>Semaine n°</th><th class='actions'>Actions</th></thead>
                        <tbody>";

        foreach ($sheets as $sheet) {

            $html = $html . "<tr> <td>Semaine " . $sheet['week'];

            if (ican('createsheet') && (isset($sheet['template_name']))) {
                $html = $html . "<i class='fas fa-file-alt template' title='" . $sheet['template_name'] . "'></i>";
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
        $html = $html . "<div class='" . $slug . "Sheets'><p>Aucune feuille de tâche n'est actuellement " . showState($slug) . ".</p></div>";
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
            // Test pour vérifier si une autre feuille est déjà ouverte
            $sheet = getTodosheetByID($sheetID);
            $alreadyOpen = (empty(getWeeksBySlugs($sheet['base_id'], 'open'))) ? false : true;

            if (ican('opensheet')) {
                if(!$alreadyOpen){
                    $buttons = $buttons . "<form  method='POST' action='?action=switchSheetState'>
                    <input type='hidden' name='id' value='" . $sheetID . "'>
                    <input type='hidden' name='newSlug' value='open'>
                    <button type='submit' class='btn btn-primary'>Activer</button>
                    </form>";
                } else {
                    $buttons = $buttons ."<form><button type='submit' class='btn btn-primary' disabled>Activer</button></form>";
                }

            }
        case "archive":
            if (ican('deletesheet')) { // TODO : ajouter une verification de la part de l'utilisateur (VB)
                $buttons = $buttons . "<form  method='POST' action='?action=deleteSheet'>
                    <input type='hidden' name='id' value='" . $sheetID . "'>
                    <button type='submit' class='btn btn-primary'>Supprimer</button>
                    </form>";
            }
            break;
        case "open":
            if (ican('closesheet')) {
                $buttons = $buttons . "<form  method='POST' action='?action=switchSheetState'>
                    <input type='hidden' name='id' value='" . $sheetID . "'>
                    <input type='hidden' name='newSlug' value='close'>
                    <button type='submit' class='btn btn-primary'>Fermer</button>
                    </form>";
            }
            break;
        case "reopen":
            if (ican('closesheet')) {
                $buttons = $buttons . "<form  method='POST' action='?action=switchSheetState'>
                    <input type='hidden' name='id' value='" . $sheetID . "'>
                    <input type='hidden' name='newSlug' value='close'>
                    <button type='submit' class='btn btn-primary'>Refermer</button>
                    </form>";
            }
            break;
        case "close":
            if (ican('opensheet')) {
                $buttons = $buttons . "<form  method='POST' action='?action=switchSheetState'>
                    <input type='hidden' name='id' value='" . $sheetID . "'>
                    <input type='hidden' name='newSlug' value='reopen'>
                    <button type='submit' class='btn btn-primary'>Corriger</button>
                    </form>";
            }
            if (ican('archivesheet')) {
                $buttons = $buttons . "<form  method='POST' action='?action=switchSheetState'>
                    <input type='hidden' name='id' value='" . $sheetID . "'>
                    <input type='hidden' name='newSlug' value='archive'>
                    <button type='submit' class='btn btn-primary'>Archiver</button>
                    </form>";
            }
            break;
        default:
            break;
    }
    return $buttons;
}

function listSheet($page, $sheets)
{
    switch ($page) {
        case "shift":
            $function = "listShiftSheet";
            break;
        default:
            break;
    }
    $html = "<div> <!-- Sections d'affichage des différentes feuilles -->";
    $html .= "<div> <!-- Feuilles ouvertes -->
        <div class='slugBlank'>
        " . $function("open", $sheets["open"]) . "
    </div><br>";
    $html .= "<div> <!-- Feuilles en préparation -->
        <div class='slugOpen'>
        " . $function("blank", $sheets["blank"]) . "
    </div><br>";
    $html .= "<div> <!-- Feuilles en correction -->
        <div class='slugReopen'>
        " . $function("reopen", $sheets["reopen"]) . "
    </div><br>";
    $html .= "<div> <!-- Feuilles fermées -->
        <div class='slugClose'>
        " . $function("close", $sheets["close"]) . "
    </div>";
    return $html;
}

function listShiftSheet($slug, $shiftList)
{
    $html = "<h3>Semaine(s) " . showState($slug, 1) . "</h3>
                    <button class='btn dropdownButton'><i class='fas fa-caret-square-down' data-list='" . $slug . "' ></i></button>
                    </div>";
    if (count($shiftList) > 0) {
        $head = '<table class="table table-bordered  table-striped '.$slug.'Sheets" style="text-align: center">
        <thead class="thead-dark">
        <th>Date</th>
        <th>Véhicule</th>
        <th>Responsable</th>
        <th>Équipage</th>
        <th class="actions">Action</th>
        </thead>';
        $body = "";
        foreach ($shiftList as $shift) {
            $body .= "<tr>
                <td>".date('d.m.Y', strtotime($shift['date']))."</td>
                <td>Jour : ".$shift["slug"] . $shift['novaDay'] . "<br>Nuit : " . $shift['novaNight'] . "</td>
                <td>Jour : " . $shift['bossDay'] . "<br>Nuit : " . $shift['bossNight'] . "</td>
                <td>Jour : " . $shift['teammateDay'] . "<br>Nuit : " . $shift['teammateNight'] . "</td>";
            $body .="<td><div class='d-flex justify-content-around'>
                                <form>
                                    <input type='hidden' name='action' value='showShift'>
                                    <input type='hidden' name='id' value='" . $shift['id'] . "'>
                                    <button type='submit' class='btn btn-primary'>Détails</button>
                                </form>
            " . slugButtons("shift", $shift, $slug) . "</div></td>";
            $body .= "</td></tr>";
        }
        $foot = "</table>";
        $table = $head . $body . $foot;
        $html .= $table;
    } else {
        $html .= "<div class='" . $slug . "Sheets'><p>Aucune feuille de tâche n'est actuellement " . showState($slug) . ".</p></div>";
    }
    return $html;
}



function slugButtons($page, $sheet, $slug)
{
    $buttons = "";
    switch ($slug) {
        case "blank":
            if (ican('opensheet')) {
                // Test pour vérifier si une autre feuille est déjà ouverte
                if(!checkOpen($page,$sheet["base_id"])){
                    $buttons .= "<form  method='POST' action='?action=".$page."SheetSwitchState'>
                    <input type='hidden' name='id' value='" . $sheet["id"] . "'>
                    <input type='hidden' name='newSlug' value='open'>
                    <button type='submit' class='btn btn-primary'>Activer</button>
                    </form>";
                } else {
                    $buttons .= "<form><button type='submit' class='btn btn-primary' disabled>Activer</button></form>";
                }
            }
        case "archive":
            if (ican('deletesheet')) { // TODO : ajouter une verification de la part de l'utilisateur (VB)
                $buttons .= "<form  method='POST' action='?action=".$page."DeleteSheet'>
                    <input type='hidden' name='id' value='" . $sheet["id"] . "'>
                    <button type='submit' class='btn btn-primary'>Supprimer</button>
                    </form>";
            }
            break;
        case "open":
            if (ican('closesheet')) {
                $buttons .= "<form  method='POST' action='?action=".$page."SheetSwitchState'>
                    <input type='hidden' name='id' value='" .  $sheet["id"]  . "'>
                    <input type='hidden' name='newSlug' value='close'>
                    <button type='submit' class='btn btn-primary'>Fermer</button>
                    </form>";
            }
            break;
        case "reopen":
            if (ican('closesheet')) {
                $buttons .= "<form  method='POST' action='?action=".$page."SheetSwitchState'>
                    <input type='hidden' name='id' value='" . $sheet["id"] . "'>
                    <input type='hidden' name='newSlug' value='close'>
                    <button type='submit' class='btn btn-primary'>Refermer</button>
                    </form>";
            }
            break;
        case "close":
            if (ican('opensheet')) {
                $buttons .= "<form  method='POST' action='?action=".$page."SheetSwitchState'>
                    <input type='hidden' name='id' value='" . $sheet["id"] . "'>
                    <input type='hidden' name='newSlug' value='reopen'>
                    <button type='submit' class='btn btn-primary'>Corriger</button>
                    </form>";
            }
            if (ican('archivesheet')) {
                $buttons .= "<form  method='POST' action='?action=".$page."SheetSwitchState'>
                    <input type='hidden' name='id' value='" . $sheet["id"] . "'>
                    <input type='hidden' name='newSlug' value='archive'>
                    <button type='submit' class='btn btn-primary'>Archiver</button>
                    </form>";
            }
            break;
        default:
            break;
    }
    return $buttons;
}
