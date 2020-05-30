<?php
if($_POST) {
    $result = oci_parse($db, "INSERT INTO cities (cname) VALUES('{$_POST['name']}')");

    if($result) {
        oci_execute($result);
        oci_commit($db);

        echo '<script> location.replace("cities.php"); </script>';

    }
}
?>

<form method="post">
    <label for="name">City name: </label>
    <input type="text" name="name">

    <button type="submit">ADD</button>
</form>
