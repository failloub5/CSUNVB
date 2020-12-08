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
    $_SESSION['flashmessage']=$message;
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

/**
 * Retourne la date formatée pour l'affichage
 * @param $date au format standard YYYY-MM-DD HH:ii:ss
 * @param $format un de quelques formats prédéfinis que l'on utilise dans l'appli
 */
function displayDate ($date, $format)
{
    switch ($format) {
        case 1: return strftime('%A %e %b %Y', strtotime($date)); // Complet avec le jour
        default: return strftime('%e %b %Y', strtotime($date)); // Complet sans le jour
    }
}

/**
 * Si l'utilisateur n'est pas administrateur, affiche une erreur et le redirige sur la page d'accueil
 */
function isAdmin(){
    if($_SESSION['user']['admin'] == false){
        setFlashMessage("Vous n'êtes pas autorisé à effectuer cette action !");
        return true;
    }
    return false;
}
function actionForStatus($status){
    switch ($status){
        case "blank":
            return "Ouvrir";
        case "open":
            return "Fermer";
        case "close":
            return "Réouvrir";
        case "reopen":
            return "Fermer";
        default:
            return "action indéterminée";
    }
}

?>
