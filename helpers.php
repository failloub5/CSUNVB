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
    return "2.1";
}

function getDrugStateButton($state) {
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

function buttonTask($initials, $desription, $weekState)
{
    if($weekState == 'open'){
        if(empty($initials)) {
            return "<button type='submit' class='btn btn-secondary btn-block m-1' data-toggle='modal' data-target='#popUpValidation'>".$desription."<div class='bg-white rounded mt-1'><br></div></button>";
        } else {
            return "<button type='submit' class='btn btn-success btn-block m-1'>".$desription."<div class='text-success bg-white rounded mt-1'>".$initials."</div></button>";
        }
    } else {
        if(empty($initials)) {
            return "<button type='submit' class='btn btn-warning btn-block m-1' disabled >".$desription."<div class='bg-white rounded mt-1'><br></div></button>";
        } else {
            return "<button type='submit' class='btn btn-success btn-block m-1' disabled >".$desription."<div class='text-success bg-white rounded mt-1'>".$initials."</div></button>";
        }
    }
}


function popUpValidation($title, $message){

    return "<div class='modal fade' id='popUpValidation' data-backdrop='static' data-keyboard='false' tabindex='-1' role='dialog'>
                <div class='modal-dialog modal-dialog-centered' role='document'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h4 class='modal-title'>".$title."</h4>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>x</span>
                            </button>
                        </div>
                        <div class='modal-body'>
                            <p>".$message."</p>
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-primary'>Ok</button>
                            <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
                        </div>
                    </div>
                </div>
            </div>";
}
?>
