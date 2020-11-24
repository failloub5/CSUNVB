<?php

/** Display Admin page */
function adminHome()
{
    require VIEW . 'admin/adminHome.php';
}

/** Users Administration */
function adminCrew()
{
    $users = getUsers();
    require_once VIEW . 'admin/adminCrew.php';
}

function newUser()      //Pointe sur la page d'ajout d'un user
{
    require_once VIEW . 'admin/newUser.php';
}

function saveNewUser()         //Crée un utilisateur
{

    $prenomUser = $_POST['prenomUser'];
    $nomUser = $_POST['nomUser'];
    $initialesUser = $_POST['initialesUser'];
    $startPassword = $_POST['startPassword'];

    $hash = password_hash($startPassword, PASSWORD_DEFAULT);
    $result = addNewUser($prenomUser, $nomUser, $initialesUser, $hash, 0, 1);
    if ($result == 0) {
        $_SESSION['flashmessage'] = "Une erreur est survenue. Impossible d'ajouter l'utilisateur.";
    } else {
        $_SESSION['flashmessage'] = "L'utilisateur a bien été créé !";
    }
    adminCrew();
}

function changeUserAdmin()       //Change un user en admin (et inversément)
{
    $changeUser = $_GET['idUser'];
    $user = getUser($changeUser);
    if ($user['admin']) {
        $user['admin'] = 0;
        $_SESSION['flashmessage'] = $user['initials'] . " est désormais un utilisateur.";
    } else {
        $user['admin'] = 1;
        $_SESSION['flashmessage'] = $user['initials'] . " est désormais un administrateur.";
    }
    SaveUser($user);
    adminCrew();
}

function resetUserPassword()
{
    $newpassword = changePwdState($_GET['idUser']);
    $_SESSION['flashmessage'] = "Le nouveau mot de passe est: $newpassword";
    adminCrew();
}

/** Drug Administration */

function adminDrugs()
{
    $drugs = getDrugs();
    require_once VIEW . 'admin/adminDrugs.php';
}

function newDrug(){
    if(isset($_POST['nameDrug'])){
        addNewDrug($_POST['nameDrug']);
        adminDrugs();
    }
    else {
        require_once VIEW . 'admin/newDrug.php';
    }
}

function updateDrug(){
    $idDrug = $_GET['idDrug'];
    if(isset($_POST['updateNameDrug'])){
        updateNameDrug($_POST['updateNameDrug'], $idDrug);
        adminDrugs();
    }
    else {
        require_once VIEW . 'admin/updateDrug.php';
    }
}

/** Bases Administration */

function adminBases()
{
    $bases = getbases();
    require_once VIEW . 'admin/adminBases.php';
}

function newBase(){
    if(isset($_POST['nameBase'])){
        addNewBase($_POST['nameBase']);
        adminBases();
    }
    else {
        require_once VIEW . 'admin/newBase.php';
    }
}

function updateBase()
{
    $idBase = $_GET['idBase'];
    if(isset($_POST['updateNameBase'])){
        updateNameBase($_POST['updateNameBase'], $idBase);
        adminBases();
    }
    else {
        require_once VIEW . 'admin/updateBase.php';
    }
}

/** Nova Administration */

function adminNovas()
{
    $novas = getNovas();
    require_once VIEW . 'admin/adminNovas.php';
}

function newNova(){
    if(isset($_POST['nameNova'])){
        addNewNova($_POST['nameNova']);
        adminNovas();
    }
    else {
        require_once VIEW . 'admin/newNova.php';
    }
}

function updateNova()
{
    $idNova = $_GET['idNova'];
    if(isset($_POST['updateNameNova'])){
        updateNameNova($_POST['updateNameNova'], $idNova);
        adminNovas();
    }
    else {
        require_once VIEW . 'admin/updateNova.php';
    }
}