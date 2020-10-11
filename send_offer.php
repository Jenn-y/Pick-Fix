<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once("includes/db.php");

if (isset($_GET['rid']) && isset($_POST['offer_amount'])) {
    $rid = $_GET['rid'];
    $offer_amount = $_POST['offer_amount'];

    $result = oci_parse($db, "update requests_history set offer_amount = {$offer_amount} where request = {$rid}");

    oci_execute($result);
    oci_commit($db);

    header('Location: requests');

}
