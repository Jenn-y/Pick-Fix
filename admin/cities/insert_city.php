<?php
if ($_POST) {

    $query = oci_parse($db, "SELECT cid, cname FROM cities 
WHERE date_deleted IS NOT NULL AND UPPER ('{$_POST['name']}') = UPPER (cname)");
    oci_execute($query);
    if ($row = oci_fetch_assoc($query)) {
        $query = oci_parse($db, "UPDATE cities SET date_deleted = null WHERE cid in (SELECT cid FROM cities 
WHERE date_deleted IS NOT NULL AND UPPER ('{$_POST['name']}') = UPPER (cname))");
        oci_execute($query);
        oci_commit($db);
    } else {
        $result = oci_parse($db, "INSERT INTO cities (cname) VALUES('{$_POST['name']}')");

        oci_execute($result);
        oci_commit($db);
    }

    echo '<script> location.replace("cities.php"); </script>';


}
?>

<form method="post">
    <label for="name">City name: </label>
    <input type="text" name="name">

    <button type="submit">ADD</button>
</form>
