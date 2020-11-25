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
    adminGuardSheet();
}


function adminGuardSheet()
{
    $guardsheets = getGuardSheets();
    require_once VIEW . 'viewsShiftEnd/shiftEndHome.php';
}

function reOpenShift()
{
    reopenShiftPage($_POST["reOpen"]);
    require_once VIEW . 'main/home.php';
}
function closeShift()
{
    closeShiftPage($_POST["close"]);
    require_once VIEW . 'main/home.php';
}

function listShiftEnd($baseID)
{
    $site = getbasebyid($baseID)["name"];
    $TitlesLines = getGuardLines();
    $Titles = getSectionsTitles();
    $admin = getUserAdmin($_SESSION["username"]["admin"]);
    $Bases = getbases();
    $list = Guardsheet();
    $guardsheets = getGuardsheetForBase($baseID);
    require_once VIEW . 'viewsShiftEnd/shiftEndHome.php';
}

function showShiftEnd($shiftid)
{
    $listSections = getGuardSectionsWithLines();
    $guardsheet = getGuardsheetDetails($shiftid);
    require_once VIEW . 'viewsShiftEnd/showShiftEnd.php';
}
