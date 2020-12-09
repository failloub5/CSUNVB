<?php
function getNovas()
{
    $nova= selectMany('SELECT * FROM novas',[]);
    return $nova;
}

function addNewNova($nameNova)
{
    return intval (insert("INSERT INTO novas (number) values (:nameNovas) ",['nameNovas'=>$nameNova] ));
}

function updateNameNova($updateNameNova, $idNova)
{
    return execute("UPDATE novas SET number= :number WHERE id= :id", ['number' => $updateNameNova, 'id' => $idNova]);
}
