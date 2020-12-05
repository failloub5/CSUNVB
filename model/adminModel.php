<?php
/**
 * Title: adminModel.php
 * Project : CSU-NVB
 **/

/**
 * MODIFICATIONS BY :
 * USER: marwan.alhelo
 * DATE: 13.02.2020
 * Time: 11:29
 *
 *  *USER: paola.costa
 * DATE : 20.11.20
 * Time : 17:08
 */


/**
 * @param $id : id de la base à déterminer
 * @return text : nom de la base selon $id
 */
function getbasebyid($id)       //Récupère une base en fonction de son Id
{
    return selectOne("SELECT * FROM bases where id =:id", ['id' => $id]);
}

function getbases()            //Récupère toutes les bases
{
    return selectMany("SELECT * FROM bases", []);
}

function getUserByInitials($initials)       //Récupère un utilisateur en fonction de ses initiales
{
    return selectOne("SELECT * FROM users where initials =:initials", ['initials' => $initials]);
}

function getUsers()     //Récupère tous les utilisateurs
{
    return selectMany("SELECT * FROM users", []);
}

function getUser($id)     //Récupère l'utilisateur qui a cet $id
{
    return selectOne("SELECT * FROM users where id=:id", ['id' => $id]);
}

function SaveUser($user)       //Met à jour un utilisateur
{
    unset($user['password']);
    unset($user['firstconnect']);
    return execute("UPDATE users SET firstname= :firstname, lastname= :lastname, initials = :initials, admin = :admin where id = :id", $user);
}

function SaveUserPassword($hash, $id)       //Met à jour le mdp d'un utilisateur
{
    return execute("UPDATE users SET password= :password, firstconnect= :firstconnect where id = :id", ['password' => $hash, 'firstconnect' => 0, 'id' => $id]);
}

function addNewDrug($nameDrug)
{
    return intval(insert("INSERT INTO drugs (name) values (:nameDrugs) ", ['nameDrugs' => $nameDrug]));
}

function addNewUser($prenomUser, $nomUser, $initialesUser, $hash, $admin, $firstconnect)
{
    return intval(insert("INSERT INTO users (firstname, lastname, initials, password, admin, firstconnect) VALUES (:firstname, :lastname, :initials, :password, :admin, :firstconnect)", ['firstname' => $prenomUser, 'lastname' => $nomUser, 'initials' => $initialesUser, 'password' => $hash, 'admin' => $admin, 'firstconnect' => $firstconnect]));       //à optimiser/simplifier avec un tableau
}

function addNewBase($nameBase)
{
    return intval(insert("INSERT INTO bases (name) values (:nameBase) ", ['nameBase' => $nameBase]));
}

function changePwdState($changeUser)
{
    $newpassw = substr(md5(rand()), 0, 6);
    $hash = password_hash($newpassw, PASSWORD_DEFAULT);
    execute("UPDATE users SET firstconnect= :firstconnect, password = :hash WHERE id= :id", ['firstconnect' => 1, 'id' => $changeUser, 'hash' => $hash]);
    return $newpassw;
}

function addNewNova($nameNova)
{
    return intval(insert("INSERT INTO novas (number) values (:nameNovas) ", ['nameNovas' => $nameNova]));
}

?>
