<?php
if($_POST) {
    $result = mysqli_query($db, "INSERT INTO cities (name, status) VALUES('{$_POST['name']}', 1)");

    if($result) {
        header('Location: cities.php');
    }
}
?>

<form method="post">
    <label for="name">City name: </label>
    <input type="text" name="name">

    <button type="submit">ADD</button>
</form>
