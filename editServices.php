<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once("includes/db.php");
include('includes/functions.php');

if (isset($_SESSION['user_id'])) {
    $aid = $_SESSION['user_id'];

    $query = oci_parse($db, "select * from accounts where aid = '{$aid}'");
    oci_execute($query);
    $row = oci_fetch_assoc($query);

    $query2 = oci_parse($db, "SELECT DISTINCT W.CITY, C.CNAME, C.CID
                                     FROM WORK_OFFERS W, CITIES C
                                     WHERE W.CITY = C.CID
                                     AND W.PROFESSIONAL = {$aid}
                                     ORDER BY C.CNAME");
    oci_execute($query2);

    $query4 = oci_parse($db, "SELECT S.SID, S.CATEGORY
                                     FROM SERVICES S
                                     WHERE S.DATE_DELETED IS NULL AND S.SID NOT IN
                                     (SELECT W.SERVICE FROM WORK_OFFERS W, SERVICES S WHERE W.SERVICE = S.SID AND W.PROFESSIONAL = {$aid} AND W.DATE_DELETED IS NULL)
                                     ORDER BY S.CATEGORY");
    oci_execute($query4);


    if (isset($_POST['new_service'])) {
        $query2 = oci_parse($db, "SELECT DISTINCT W.CITY
                                     FROM WORK_OFFERS W, CITIES C
                                     WHERE W.CITY = C.CID
                                     AND W.DATE_DELETED IS NULL
                                     AND W.PROFESSIONAL = {$aid}");
        oci_execute($query2);
        while($row = oci_fetch_assoc($query2)) {
            $city = $row['CITY'];
            $check_deleted = oci_parse($db, "SELECT W.*
                                                    FROM WORK_OFFERS W 
                                                    WHERE W.PROFESSIONAL = {$aid}
                                                    AND W.CITY = {$city}
                                                    AND W.SERVICE = {$_POST['new_service']}
                                                    AND W.DATE_DELETED IS NOT NULL");
            oci_execute($check_deleted);
            if (!oci_fetch_assoc($check_deleted)) {
                $sql = oci_parse($db, "INSERT INTO WORK_OFFERS (SERVICE, CITY, CHARGE_PER_HOUR, PROFESSIONAL, SERVICE_LEVEL)
                                          VALUES ({$_POST['new_service']}, {$city}, 4, {$aid}, 'Beginner')");
                oci_execute($sql);
                oci_commit($db);
            } else {
                $sql = oci_parse($db, "UPDATE WORK_OFFERS SET DATE_DELETED = NULL
                                           WHERE CITY = {$city}
                                           AND SERVICE = {$_POST['new_service']}
                                           AND PROFESSIONAL = {$aid}");
                oci_execute($sql);
                oci_commit($db);
            }
        }
    }
    if (isset($_POST['deleted_service'])) {
        while ($row = oci_fetch_assoc($query2)) {
            $city = $row['CID'];
            $sql = oci_parse($db, "UPDATE WORK_OFFERS SET DATE_DELETED = SYSDATE 
                                          WHERE SERVICE = {$_POST['deleted_service']}
                                          AND CITY = {$city}
                                          AND PROFESSIONAL = {$aid}");
            oci_execute($sql);
            oci_commit($db);
        }
    }
    $query2 = oci_parse($db, "SELECT DISTINCT W.CITY, C.CNAME, C.CID
                                     FROM WORK_OFFERS W, CITIES C
                                     WHERE W.CITY = C.CID
                                     AND W.PROFESSIONAL = {$aid}
                                     AND W.DATE_DELETED IS NULL
                                     ORDER BY C.CNAME");
    oci_execute($query2);
    $query5 = oci_parse($db, "SELECT DISTINCT W.SERVICE, S.CATEGORY, S.SID
                                     FROM WORK_OFFERS W, SERVICES S
                                     WHERE W.SERVICE = S.SID
                                     AND W.PROFESSIONAL = {$aid}
                                     AND W.DATE_DELETED IS NULL
                                     ORDER BY S.CATEGORY");
    oci_execute($query5);
}

?>

<!doctype html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link rel="stylesheet" href="css/editProfile.css">
    <link href="css/header.css" rel="stylesheet">
    <link rel="stylesheet" href="css/footer.css">
    <title>Edit Service Categories</title>
</head>
<body id="editServices">

<div id="page-container">
    <?php include('includes/header.php'); ?>

    <main>
        <div class="main center">
            <h2>Edit Service Categories</h2>
            <div id="service_functions">
                <form method="post">
                    <label for="new_service">Add a new service</label>
                    <select name="new_service">
                        <option disabled selected value>Choose a service</option>
                        <?php while ($get_services = oci_fetch_assoc($query4)) : ?>
                            <option value="<?= $get_services['SID'] ?>"><?= $get_services['CATEGORY'] ?></option>
                        <?php endwhile; ?>
                    </select>
                    <button type="submit" class="buttonStyle">ADD</button>
                </form>
                <form method="post">
                    <label for="deleted_service">Delete a service</label>
                    <select name="deleted_service">
                        <option disabled selected value>Choose a service</option>
                        <?php while ($get_services = oci_fetch_assoc($query5)) : ?>
                            <option value="<?= $get_services['SID'] ?>"><?= $get_services['CATEGORY'] ?></option>
                        <?php endwhile; ?>
                    </select>
                    <button type="submit" class="buttonStyle">DELETE</button>
                </form>
            </div>
            <?php while ($row = oci_fetch_assoc($query2)) {
            $city_id = $row['CID']; ?>
            <table>
                <tr>
                    <th colspan="5" id="city_header"><?= $row['CNAME'] ?></th>
                </tr>

                <?php $query3 = oci_parse($db, "SELECT W.*, S.CATEGORY, S.CAT_DESCRIPTION, C.CNAME
                                                                   FROM WORK_OFFERS W, SERVICES S, CITIES C
                                                                   WHERE W.SERVICE = S.SID
                                                                   AND W.CITY = C.CID 
                                                                   AND W.DATE_DELETED IS NULL
                                                                   AND W.PROFESSIONAL = {$aid}
                                                                   AND W.CITY = {$city_id}
                                                                   ORDER BY S.CATEGORY");
                oci_execute($query3); ?>

                <tr>
                    <th>Services</th>
                    <th>Category Description</th>
                    <th>Charge per Hour</th>
                    <th>Service Level</th>
                    <th>Save</th>
                </tr>

                <?php
                while ($row_offer = oci_fetch_assoc($query3)) { ?>
                    <form method="post">
                        <tr>
                            <td><?= $row_offer['CATEGORY']; ?>
                                <input type="hidden" name="service" value="<?= $row_offer['SERVICE']; ?>"></td>
                            <td><?= $row_offer['CAT_DESCRIPTION'] ?></td>
                            <td><input id="charge_per_hour" name="charge_per_hour" type="number"
                                       value="<?php
                                       if (isset($row_offer['CHARGE_PER_HOUR'])) {
                                           echo $row_offer['CHARGE_PER_HOUR'];
                                       }
                                       ?>">
                            </td>
                            <td><input id="service_level" name="service_level" type="text" value="<?php
                                if (isset($row_offer['SERVICE_LEVEL'])) {
                                    echo $row_offer['SERVICE_LEVEL'];
                                }
                                ?>">
                                <input type="hidden" name="wid" value="<?= $row_offer['WID']; ?>">
                            </td>
                            <td><input type="submit" name="submit" value="Save" class="buttonStyle">
                                <?php
                                if (isset($_POST['charge_per_hour']) && isset($_POST['service']) && isset($_POST['wid']) && isset($_POST['service_level'])) {
                                    if (checkRequiredField($_POST['charge_per_hour'])) {
                                        $charge = $_POST['charge_per_hour'];
                                        $service = $_POST['service'];
                                        $service_level = $_POST['service_level'];
                                        $wid = $_POST['wid'];
                                        $city = $row_offer['CITY'];

                                        $query = oci_parse($db, "UPDATE WORK_OFFERS
                                                                                        SET CHARGE_PER_HOUR = {$charge}, SERVICE_LEVEL = '{$service_level}'
                                                                                        WHERE SERVICE={$service} 
                                                                                        AND CITY={$city} 
                                                                                        AND PROFESSIONAL={$aid}
                                                                                        AND WID = {$wid}");
                                        oci_execute($query);
                                        oci_commit($db);

                                        echo '<script> location.replace("editServices.php"); </script>';
                                    }
                                }
                                ?>
                            </td>
                    </form>
                    </tr>
                <?php }
                } ?>
            </table>
            <a href="editProfile.php" class="buttonStyle" id="return_link">Back to Edit</a>
        </div>
    </main>
    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>