<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once("includes/db.php");

if (isset($_GET['id']) && isset($_GET['amount'])) {
    $id = $_GET['id'];
    $amount = $_GET['amount'];

    $result = oci_parse($db, "INSERT INTO REQUESTS_HISTORY(DATETIME, STATUS, REQUEST, OFFER_AMOUNT)
                                     VALUES (SYSDATE, 2, {$id}, {$amount})");

    oci_execute($result);
    oci_commit($db);

    header('Location: requests');

}

