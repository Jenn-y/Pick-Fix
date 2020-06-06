<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once ('includes/db.php');

if (isset($_SESSION['user_id'])) {
    $aid = $_SESSION['user_id'];

    if (isset($_GET['service']) && isset($_GET['city']) && isset($_GET['charge_per_hour'])) {
        $service = $_GET['service'];
        $city = $_GET['city'];
        $charge_per_hour = $_GET['charge_per_hour'];
        var_dump($charge_per_hour);
        //$service_level = $_GET['service_level'];

        $query = oci_parse($db, "UPDATE WORK_OFFERS
                                    SET CHARGE_PER_HOUR = {$charge_per_hour}
                                    WHERE SERVICE={$service} AND CITY={$city} AND PROFESSIONAL={$aid}");
        oci_execute($query);
        oci_commit($db);

        //header('Location: editServices.php');

    }

}