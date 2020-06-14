<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once("includes/db.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $result = oci_parse($db, "INSERT INTO REQUESTS_HISTORY(DATETIME, STATUS, REQUEST)
                                     VALUES (SYSDATE, 2, {$id})");

    oci_execute($result);
    oci_commit($db);

    header('Location: requests.php');

}

