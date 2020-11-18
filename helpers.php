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

//display a var (with var_dump()) for debug, only if debug mode is enabled
function displaydebug($var)
{
    require ".const.php";   //get the $debug variable
    if ($debug == true) {   //if debug mode enabled
        if (substr($_SERVER['SERVER_SOFTWARE'], 0, 7) != "PHP 7.3") {  //if version is not 7.3 (var_dump() don't have the same design)
            echo "<pre><small>" . print_r($var, true) . "</small></pre>";   //print with line break and style of <pre>
        } else {
            var_dump($var); //else to a simple var_dump() of PHP 7.3
        }
    }
}

/**
 * inspired by source https://stackoverflow.com/questions/7447472/how-could-i-display-the-current-git-branch-name-at-the-top-of-the-page-of-my-de
 * @author Kevin Ridgway
 */
function gitBranchTag() {
    $stringfromfile = file('.git/HEAD', FILE_USE_INCLUDE_PATH);
    $firstLine = $stringfromfile[0]; //get the string from the array
    $explodedstring = explode("/", $firstLine, 3); //seperate out by the "/" in the string
    $branchname = $explodedstring[2]; //get the one that is always the branch name
    return "<div style='clear: both; width: 100%; font-size: 14px; font-family: Helvetica; color: #8d8d8d; background: transparent; text-align: right;'>version " . getVersion(). " sur la branche " . $branchname . "</div>"; //show it on the page
}

function getVersion() {
    return "2.0";
}

?>
