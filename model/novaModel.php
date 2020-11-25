<?php
function getNovas()
{
    try {
        $dbh = getPDO();
        $query = 'SELECT * FROM novas';
        $statement = $dbh->prepare($query);//prepare query
        $statement->execute();//execute query
        $queryResult = $statement->fetchAll(PDO::FETCH_ASSOC);//prepare result for client
        $dbh = null;
        return $queryResult;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        return null;
    }
}

function addNewNova($nameNova)
{
    return intval (insert("INSERT INTO novas (number) values (:nameNovas) ",['nameNovas'=>$nameNova] ));
}

function updateNameNova($updateNameNova, $idNova)
{
    return execute("UPDATE novas SET number= :number WHERE id= :id", ['number' => $updateNameNova, 'id' => $idNova]);
}
