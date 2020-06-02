<?php
if($_POST) {
    $query = oci_parse($db, "SELECT sid, category FROM services 
WHERE date_deleted IS NOT NULL AND UPPER ('{$_POST['category']}') = UPPER (category)");
    oci_execute($query);
    if ($row = oci_fetch_assoc($query)) {
        $query = oci_parse($db, "UPDATE services SET date_deleted = null, cat_description = '{$_POST['cat_description']}' WHERE sid in (SELECT sid FROM services 
WHERE date_deleted IS NOT NULL AND UPPER ('{$_POST['category']}') = UPPER (category))");
        oci_execute($query);
        oci_commit($db);
    }
    else {
        $result = oci_parse($db, "INSERT INTO services (category, cat_description) VALUES('{$_POST['category']}', '{$_POST['cat_description']}')");

        oci_execute($result);
        oci_commit($db);
    }

    echo '<script> location.replace("services.php"); </script>';
}
?>

<form action="services.php" method="post">
    <label for="category">Service category name: </label>
    <input type="text" name="category"><br>
    <label for="cat_description">Category description </label>
    <input type="text" name="cat_description"><br>

    <button type="submit">ADD</button>
</form>
