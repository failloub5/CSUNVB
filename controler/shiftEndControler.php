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
    $result = addNewShiftSheet("en préparation", $baseID);
    if ($result == false) {
        $_SESSION['flashmessage'] = "Une erreur est survenue. Impossible d'ajouter la Nova.";
    } else {
        $_SESSION['flashmessage'] = "La Nova a bien été créé !";
    }
    adminGuardSheet();
}
function adminGuardSheet()
{
    $guardsheets = getGuardsheets();
    require_once VIEW . 'viewsShiftEnd/ListShiftEnd.php';
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

function listShiftEnd($base_id)
{
    $site = getbasebyid($base_id)["name"];
    $TitlesLines = getGuardLines();
    $Titles = getSectionsTitles();
    $admin = getUserAdmin($_SESSION["username"]["admin"]);
    $Bases = getbases();
    $list = Guardsheet();
    $guardsheets = getGuardsheetForBase($base_id);
    require_once VIEW . 'viewsShiftEnd/shiftEndHome.php';
}

