<?php
$newtodoitems = filter_input(INPUT_POST, "newtodoitems");
$title = filter_input(INPUT_POST, "title");
$description = filter_input(INPUT_POST, "description");

$todoitems = filter_input(INPUT_GET, "todoitems");

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDO ToDo List</title>
    <link rel="stylesheet" href="css/main.css">
</head>


<body>
<main>
    <header>
        <h1>To Do List</h1>
    </header>
    <?php
    if(!$todoitems && !$newtodoitems) { ?>
    <section>
        <h2>Select Data / Read Data</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']?>"method="GET">
                <label for='todoitems'>To Do Items:</label>
                <input type="text" id="todoitems" name="todoitems" required>
                <button>Submit</button>
            </form>
    </section>
    <section>
        <h2>Insert Data / Create Data</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']?>"method="POST">
                <label for='newtodoitems'>New To Do Items:</label>
                <input type="text" id="newtodoitems" name="newtodoitems" required>
                <label for='title'>Title:</label>
                <input type="text" id="title" name="title" required>
                <label for='description'>Description:</label>
                <input type="text" id="description" name="description" required>
                <button>Submit</button>

            </form>
    </section>
    <?php } else { ?>
        <?php require("database.php") ?>
        <?php
        if($newtodoitems) {
            $query = 'INSERT INTO todoitems
                        (Name, Title, Description)
                        VALUES
                            (:newtodoitems, :title, :newdescription)';
            $statement = $db->prepare($query);
            $statement->bindValue(':newtodoitems', $newtodoitems);
            $statement->bindValue(':title', $title);
            $statement->bindValue(':newdescription', $description);
            $statement->execute();
            $statement->closeCursor();
        }
        ?>
        <?php 
        if($todoitems || $newtodoitems) {
            $query = 'SELECT * FROM todoitems
                        WHERE Name = :todoitems
                                ORDER BY Description DESC';
            $statement = $db->prepare($query);
            if ($todoitems) {
                $statement->bindValue(':todoitems', $todoitems);
            } else {
                $statement->bindValue(":todoitems", $newtodoitems);
            }
            $statement->execute();
            $results = $statement->fetchAll();
            $statement->closeCursor();
        }
        ?>
        <?php 
        if(!empty($results)) { ?>
        <section>
            <h2>Update or Delete Data</h2>
            <?php foreach ($results as $result){
                $id = $result["ID"];
                $todoitems = $result["Name"];
                $title = $result["Title"];
                $description = $result["Description"];
            ?>

                <form class="delete" action="delete_record.php" method="POST">
               <input type="hidden" name="id" value="<?php echo $id ?>">
               <button class="red">Delete</button>
            </form>
           <?php }?>
           
        </section>

        <?php } else {?>
            <p>Sorry, No to do list items exist yet</p>
        <?php }?>
    <?php } ?>
</main>
</body>
</html>