<?php
include('../../includes/db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = oci_parse($db, "UPDATE services SET date_deleted = SYSDATE WHERE sid = {$id}");
    if ($result) {
        oci_execute($result);
        oci_commit($db);

        header('Location: services.php');
    }
}
