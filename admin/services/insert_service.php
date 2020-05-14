<?php
if($_POST) {
    $result = mysqli_query($db, "INSERT INTO services (category, status) 
                VALUES('{$_POST['category']}', 1)");

    if($result) {
        header('Location: services.php');
    }
}
?>

<form method="post">
    <label for="category">Service category name: </label>
    <input type="text" name="category">

    <button type="submit">ADD</button>
</form>
