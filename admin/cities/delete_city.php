<?php
include('../../includes/db.php');

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    var_dump($id);
    $result = oci_parse($db, "UPDATE cities SET date_deleted = SYSDATE WHERE cid = {$id}");
    if($result) {
        oci_execute($result);
        oci_commit($db);
        header('Location: cities.php');
    }
}
