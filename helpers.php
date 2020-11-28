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

/**
 * inspired by source https://stackoverflow.com/questions/7447472/how-could-i-display-the-current-git-branch-name-at-the-top-of-the-page-of-my-de
 * @author Kevin Ridgway
 */
function gitBranchTag()
{
    $stringfromfile = file('.git/HEAD', FILE_USE_INCLUDE_PATH);
    if ($stringfromfile) {
        $firstLine = $stringfromfile[0]; //get the string from the array
        $explodedstring = explode("/", $firstLine, 3); //seperate out by the "/" in the string
        $branchname = "la branche " . $explodedstring[2]; //get the one that is always the branch name
    } else {
        $branchname = "production";
    }
    return "<div style='clear: both; width: 100%; font-size: 14px; font-family: Helvetica; color: #8d8d8d; background: transparent; text-align: right;'>version " . getVersion() . " sur " . $branchname . "</div>"; //show it on the page
}

function getVersion()
{
    return "2.0";
}

?>
