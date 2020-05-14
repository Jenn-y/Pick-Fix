<?php
if($_POST) {
    $result = mysqli_query($db, "INSERT INTO cities (name, status) VALUES('{$_POST['name']}', '{$_POST['status']}')");

    if($result) {
        header('Location: cities.php');
    }
}
?>

<form method="post">
    <label for="name">City name: </label>
    <input type="text" name="name">
    <label for="status">Status: </label>
    <!--<input type="number" name="status">-->
    <input type="radio" id="status" name="status" value="1"> Active
    <input type="radio" id="status" name="status" value="0"> Inactive
    <button type="submit"> submit</button>
</form>
