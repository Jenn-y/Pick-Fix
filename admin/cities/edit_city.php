<?php
if(isset($_GET['id']) && $_POST) {
    $id = $_GET['id'];
    $result = oci_parse($db, "UPDATE cities SET cname = '{$_POST['name']}' WHERE cid = $id");

    if($result) {
        oci_execute($result);
        oci_commit($db);

        header('Location: cities.php');
    }
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = oci_parse($db, "SELECT cname FROM cities WHERE cid = $id");
    oci_execute($query);
    $row = oci_fetch_assoc($query);
}


?>

<form method="post">
    <label for="name">City name: </label>
    <input type="text" name="name" value="<?php
    if (isset($row['CNAME'])){
        echo $row['CNAME'];
    }
     ?>">

    <button type="submit">SAVE</button>
</form>
