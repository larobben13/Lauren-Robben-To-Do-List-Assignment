<?php
require('database.php');

$itemnum = filter_input(INPUT_POST, 'itemnum', FILTER_VALIDATE_INT);

if($itemnum){
    $query = 'DELETE FROM todoitems
                    WHERE ITEMNUM = :itemnum';
    $statement = $db->prepare($query);
    $statement->bindValue(':itemnum', $itemnum);
    $success = $statement->execute();
    $statement->closeCursor();
}


$deleted = true;
include('index.php');
?>