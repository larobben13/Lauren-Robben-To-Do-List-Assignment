<?php 
require('database.php');

$itemnum = filter_input(INPUT_POST, 'itemnum', FILTER_VALIDATE_INT);
$todoitems = filter_input(INPUT_POST, 'todoitems', FILTER_SANITIZE_STRING);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);


If($itemnum){
    $query = 'UPDATE todoitems 
                    SET Title = :todoitems, Description=:description
                        WHERE ITEMNUM =:itemnum';
    $statement = $db->prepare($query);
    $statement->bindValue(':itemnum', $itemnum);
    $statement->bindValue(':todoitems', $todoitems);
    $statement->bindValue(':description', $description);
    $success = $statement->execute();
    $statement->closeCursor();

}

$updated = true;

include('index.php');

?>