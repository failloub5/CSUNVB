<?php

/** Display Home page */
function home()
{
    require VIEW . 'main/home.php';
}

function disconnect()
{
    $_SESSION['user'] =  null;
    $_SESSION['action'] = 'login';
    login();
}

function login()
{
    if(isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['base']))
    {
        trylogin();
    }else{
        displayLoginPage();
    }
}

function displayLoginPage(){
    require VIEW . 'main/login.php';
}


function tryLogin()
{
    $initials = $_POST['username'];
    $password = $_POST['password'];
    $baseLogin = $_POST['base'];

    $user = getUserByInitials($initials);
    if (password_verify($password, $user['password'])) {
        unset($user['password']); // don't store password in the session
        $_SESSION['user'] =  $user;
        $_SESSION['base'] = getbasebyid($baseLogin);        //Met la base dans la session
        if ($user['firstconnect'] == true) {
            firstLogin();
        } else {
            $_SESSION['flashmessage'] = 'Bienvenue ' . $user['firstname'] . ' ' . $user['lastname'] . ' !';
            home();
        }
    } else {
        $_SESSION['flashmessage'] = 'Identifiants incorrects ...';
        displayLoginPage();
    }
}

function firstLogin(){
    if(isset($_POST['passwordchange'])&&isset($_POST['confirmpassword']))
    {
        changeFirstPassword();
    }else{
        firstLoginPage();
    }
}



function changeFirstPassword()         //Oblige le nouvel user à changer son mdp à sa première connection
{
    $passwordchange = $_POST['passwordchange'];
    $confirmpassword = $_POST['confirmpassword'];
    //TODO Condtion à refaire ( michael )
    if ($passwordchange != $_SESSION['user']['password']) {
        if ($confirmpassword != $passwordchange) {
            $_SESSION['flashmessage'] = "Erreur lors de la confirmation du mot de passe";
            firstLoginPage();
        } else {
            $_SESSION['flashmessage'] = "Mot de passe modifié";
            $id = $_SESSION['user']['id'];
            $hash = password_hash($confirmpassword, PASSWORD_DEFAULT);
            SaveUserPassword($hash, $id);
            disconnect();
        }
    } else {
        $_SESSION['flashmessage'] = "Le nouveau mot de passe doit être différent de l'ancien !";
        firstLoginPage();
    }
}


function firstLoginPage(){
    require VIEW . 'main/firstLogin.php';
}


function unknownPage(){
    require VIEW . 'main/unknownPage.php';
}

