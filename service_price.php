<?php

$service_id = $_GET['s_id'];
$city_id = $_GET['c_id'];
$professional = $_GET['p_id'];
$num_hrs = $_GET['num_of_hrs'];

include_once ('includes/db.php');

$query = oci_parse($db, "SELECT WID, CHARGE_PER_HOUR FROM WORK_OFFERS
                                            WHERE SERVICE={$service_id} AND CITY={$city_id} AND PROFESSIONAL={$professional}");
oci_execute($query);
$row = oci_fetch_assoc($query);

echo $row['CHARGE_PER_HOUR'] * $num_hrs;
