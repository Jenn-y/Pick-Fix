<?php
include('../../includes/db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    var_dump($id);
    $result = oci_parse($db, "UPDATE services SET date_deleted = null WHERE sid = {$id}");
    if ($result) {
        oci_execute($result);
        oci_commit($db);
        header('Location: services.php');
    }
}