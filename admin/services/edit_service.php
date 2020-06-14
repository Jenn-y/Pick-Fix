<?php
if (isset($_GET['id']) && $_POST) {
    $id = $_GET['id'];
    $result = oci_parse($db, "UPDATE services SET category = '{$_POST['category']}', cat_description = '{$_POST['cat_description']}' WHERE sid = $id");

    if ($result) {
        oci_execute($result);
        oci_commit($db);

        header('Location: services.php');
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = oci_parse($db, "SELECT category, cat_description FROM services WHERE sid = $id");
    oci_execute($query);
    $row = oci_fetch_assoc($query);
}


?>

<form method="post">
    <div>
        <label for="category">Service category name: </label>
        <input type="text" name="category" value="<?php
        if (isset($row['CATEGORY'])) {
            echo $row['CATEGORY'];
        }
        ?>"></div>
    <div>
        <label for="cat_description">Category description: </label>
        <input type="text" name="cat_description" value="<?php
        if (isset($row['CAT_DESCRIPTION'])) {
            echo $row['CAT_DESCRIPTION'];
        }
        ?>">
    </div>
    <div>
        <button type="submit">SAVE</button>
    </div>
</form>
