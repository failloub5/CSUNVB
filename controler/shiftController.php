<?php
/**
 * Auteur: Thomas Grossmann / Mounir Fiaux
 * Date: Mars 2020
 */

/**
 * @param $baseID
 */
function newShiftSheet()
{
    if (isAdmin()) {
        $result = addNewShiftSheet($_SESSION['base']['id']);
        if ($result == false) {
            setFlashMessage("Une erreur est survenue. Impossible d'ajouter la feuille de garde.");
        } else {
            setFlashMessage("La feuille de garde a bien été créée !");
        }
        listshiftforbase($_SESSION["base"]['id']);
    } else {
        home();
    }
}

// Attention: cette fonction se base sur un diagramme d'état simplifié:
// blank -> open -> close -> reopen -> close
// Elle ne fonctionnera pas le jour où on pourra passer d'un état à un autre parmi plusieurs
function altershiftsheetStatus($sheet_id)
{
    $sheet = getshiftsheetByID($sheet_id);
    switch ($sheet["status"]) {
        case 'open' :
        case 'reopen' :
            closeShiftPage($sheet["id"]);
            break;
        case 'blank' :
            if (($_SESSION['user']['admin'] == true)) {
                if (getNbshiftsheet('open', $sheet["base_id"]) == 0) {
                    openShiftPage($sheet["id"]);
                } else {
                    $_SESSION["flashmessage"] = "Une autre feuille est déjà ouverte";
                }
            }
            break;
        case 'close' :
            if (($_SESSION['user']['admin'] == true)) reopenShiftPage($sheet["id"]);
            break;
        default :
            break;
    }
    listshiftforbase($_SESSION["base"]['id']);
}

// default: the base where the user logged
function listshift()
{
    listshiftforbase($_SESSION['base']['id']);
}

// List shifts for a specific base
function listshiftforbase($baseID)
{
    $Bases = getbases();
    $shiftsheets = getshiftsheetForBase($baseID);
    require_once VIEW . 'shift/list.php';
}

function showshift($shiftid)
{
    $sections = getshiftsections($shiftid);
    $shiftsheet = getshiftsheetByID($shiftid);
    $enableshiftsheetUpdate = ($shiftsheet['status'] == "open" || ($shiftsheet['status'] == "blank" && $_SESSION['user']['admin'] == true));
    $enableshiftsheetFilling = ($shiftsheet['status'] == "open" || $shiftsheet['status'] == "reopen");
    $novas = getNovas();
    $users = getUsers();
    require_once VIEW . 'shift/show.php';
}

//function checkShift

