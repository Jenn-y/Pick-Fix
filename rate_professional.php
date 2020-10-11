<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once("includes/db.php");

if (isset($_GET['rid']) && isset($_POST['rating'])) {
    $rid = $_GET['rid'];

    if (isset($_POST['comment'])) {
        $sql = oci_parse($db, "UPDATE REQUESTS
                                    SET JOB_RATING = {$_POST['rating']},
                                    PRO_RECOMMENDATION = '{$_POST['comment']}'
                                    WHERE RID = {$rid}");
        oci_execute($sql);
        oci_commit($db);
        echo '<script> location.replace("requests"); </script>';
    } else {
        $sql = oci_parse($db, "UPDATE REQUESTS
                                    SET JOB_RATING = {$_POST['rating']}
                                    WHERE RID = {$rid}");
        oci_execute($sql);
        oci_commit($db);
        echo '<script> location.replace("requests"); </script>';
    }

    header('Location: requests');
}

