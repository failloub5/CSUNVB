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
    $result = addNewShiftSheet($_SESSION['base']['id']);
    if ($result == false) {
        $_SESSION['flashmessage'] = "Une erreur est survenue. Impossible d'ajouter la feuille de garde.";
    } else {
        $_SESSION['flashmessage'] = "La feuille de garde a bien été créé !";
    }
    listshiftforbase($_SESSION["base"]['id']);
}


// blank -> open -> close -> reopen -> close
function altershiftsheetStatus(){
    switch ($_POST["status"]) {
        case 'open' :
        case 'reopen' :
            closeShiftPage($_POST["id"]);
            break;
        case 'blank' :
            if (($_SESSION['user']['admin'] == true)) {
                if( getNbshiftsheet('open',$_SESSION["selectedBase"]) == 0 ){
                    openShiftPage($_POST["id"]);
                }else{
                    $_SESSION["flashmessage"] = "Une autre feuille est déjà ouverte";
                }
            }
            break;
        case 'close' :
            if (($_SESSION['user']['admin'] == true)) reopenShiftPage($_POST["id"]);
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
    $enableshiftsheetFilling = ($shiftsheet['status'] == "open");
    require_once VIEW . 'shift/show.php';
}

