<?php
/**
 * Auteur: Thomas Grossmann / Mounir Fiaux
 * Date: Mars 2020
 */

/**
 * @param $baseID
 */
function newShiftSheet($baseID)
{
    $result = addNewShiftSheet($baseID);
    if ($result == false) {
        $_SESSION['flashmessage'] = "Une erreur est survenue. Impossible d'ajouter la feuille de garde.";
    } else {
        $_SESSION['flashmessage'] = "La feuille de garde a bien été créé !";
    }
    listShiftEnd($_SESSION["selectedBase"]);
}


// blank -> open -> close -> reopen -> close
function alterGuardSheetStatus(){
    switch ($_POST["status"]) {
        case 'open' :
        case 'reopen' :
            closeShiftPage($_POST["id"]);
            break;
        case 'blank' :
            if (($_SESSION['username']['admin'] == true)) {
                if( getNbGuardSheet('open',$_SESSION["selectedBase"]) == 0 ){
                    openShiftPage($_POST["id"]);
                }else{
                    $_SESSION["flashmessage"] = "Une autre feuille est déjà ouverte";
                }
            }
            break;
        case 'close' :
            if (($_SESSION['username']['admin'] == true)) reopenShiftPage($_POST["id"]);
            break;
        default :
            break;
    }
    listShiftEnd($_SESSION["selectedBase"]);
}

function listShiftEnd($baseID)
{
    $Bases = getbases();
    $guardsheets = getGuardsheetForBase($baseID);
    var_dump(getNbGuardSheet("open",$baseID));
    require_once VIEW . 'viewsShiftEnd/shiftEndHome.php';

}

function showShiftEnd($shiftid)
{
    $sections = getGuardSections($shiftid);
    $guardSheet = getGuardsheetByID($shiftid);
    $enableGuardSheetUpdate = ($guardSheet['status'] == "open" || ($guardSheet['status'] == "blank" && $_SESSION['username']['admin'] == true));
    $enableGuardSheetFilling = ($guardSheet['status'] == "open");
    require_once VIEW . 'viewsShiftEnd/showShiftEnd.php';
}

