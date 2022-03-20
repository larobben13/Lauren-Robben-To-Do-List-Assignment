<?php 
require('database.php');

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$todoitems = filter_input(INPUT_POST, 'todoitems', FILTER_SANATIZE_STRING);
$title = filter_input(INPUT_POST, 'title', FILTER_SANATIZE_STRING);
$description = filter_input(INPUT_POST, 'description', FILTER_SANATIZE_STRING);

If($id){
    $query = 'UPDATE todoitems
                SET Name = :todoitems, Title= :title, Description= :description
                    WHERE ID = id';
    $statement = $db->prepare($query);
    $statement->bindvalue(':id', $id);
    $statement->bindvalue(':todoitems', $todoitems);
    $statement->bindvalue(':title', $title);
    $statement->bindvalue(':description', $description);
    $success = $statement->execute();
    $statement->closeCursor();


}

$updated = true;

include('index.php');

?>