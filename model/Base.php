<?php

function getbasebyid($id)       //Récupère une base en fonction de son Id
{
    return selectOne("SELECT * FROM bases where id =:id", ['id' => $id]);
}

function getbases()
{
    return selectMany("SELECT * FROM bases", []);
}

function addNewBase($nameBase)
{
    return intval (insert("INSERT INTO bases (name) values (:nameBase) ",['nameBase'=>$nameBase] ));
}

function renameBase($id, $name)
{
    return execute("UPDATE bases SET name=:name WHERE id=:id",['name' => $name, 'id' => $id]);
}
