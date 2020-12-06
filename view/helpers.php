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
