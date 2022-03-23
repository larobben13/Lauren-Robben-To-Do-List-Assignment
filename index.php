<?php 
$newtodoitems = filter_input(INPUT_POST, "newtodoitems", FILTER_SANITIZE_STRING);
$description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_STRING);

$todoitems = filter_input(INPUT_GET, "todoitems", FILTER_SANITIZE_STRING);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lauren To Do List </title>
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
<main>
    <header>
        <h1>To-Do List</h1>
    </header>
    <?php 
    if(isset($deleted)) {
        echo "Record Deleted. <br>";
    }else if(isset($updated)){
        echo "Record Updated. <br>";
    }
    ?>
    <?php 
        if(!$todoitems && !$newtodoitems) { ?>
    <section>
        <h2>My To Do List</h2>
        <form action = "<?php echo $_SERVER['PHP_SELF']?>" method="GET">
            <label for= 'todoitems'>ToDo Items:</label>
            <input type="text" itemnum="todoitems" name="todoitems" required>
            <button>Submit</button>
            </form>
    </section>
    <section>
        <h2>Add To Do Items</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                <label for='newtodoitems'>New To Do Item:</label>
                <input type="text" itemnum="newtodoitems" name="newtodoitems" required>
                <label for='description'>Description:</label>
                <input type="text" itemnum="description" name="description" required>
                <button>Submit</button>
            </form>
    </section>
    <?php } else {?>
    <?php require("database.php")?>
        <?php 
       if ($newtodoitems){
            $query =  'INSERT INTO todoitems
                        (Title, Description)
                        VALUES
                            (:newtodoitems, :newdescription)';
            $statement = $db->prepare($query);
            $statement->bindValue(':newtodoitems', $newtodoitems);
            $statement->bindValue(':newdescription',$description);
            $statement->execute();
            $statement->closeCursor();
        }
        ?>
         <?php 
            if($todoitems || $newtodoitems) {
                $query = 'SELECT * FROM todoitems
                            WHERE Title = :todoitems
                                ORDER BY itemnum DESC';
                $statement = $db->prepare($query);
                if($todoitems) {
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
        if(!empty($results)) {?>
            <section>
                <h2>Update or Delete Data</h2>
                <?php foreach ($results as $result){
                    $itemnum = $result["ItemNum"];
                    $todoitems = $result["Title"];
                    $description = $result["Description"];
                ?>
                <form class="update" action="update_record.php" method="POST">
                <input type="hidden" name="itemnum" value="<?php echo $itemnum ?>">

                <label for="todoitems-<?php echo $itemnum ?>">Title:</label>
                <input type="text" itemnum="todoitems-<?php echo $todoitems?>" name="todoitems" value="-<?php echo $todoitems?>" required>
                <label for="description-<?php echo $itemnum ?>">Description:</label>
                <input type="text" itemnum="description-<?php echo $description?>" name="description" value="-<?php echo $description?>" required>
                <button>Update</button>
                </form>
                <form class="delete" action="delete_record.php" method="POST">
                    <input type="hidden" name="itemnum" value="<?php echo $itemnum?>">
                    <button class="red">Delete</button>
                </form>
                <?php }?>
        </section>
       
       <?php } else {?>
        <p>Sorry, No to do list items exist yet</p>
        <?php }?>
        <a href="<?php echo $_SERVER['PHP_SELF']?>">Go to request form</a>
    <?php } ?>
</main>
</body>
</html>