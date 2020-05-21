<?php
if($_POST) {
    $result = oci_parse($db, "INSERT INTO services (category) VALUES('{$_POST['category']}')");

    if ($result) {
        oci_execute($result);
        oci_commit($db);
    }
}
?>

<form action="services.php" method="post">
    <label for="category">Service category name: </label>
    <input type="text" name="category">

    <button type="submit">ADD</button>
</form>
