<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once("includes/db.php");


function checkRequiredField($value)
{
    return isset($value) && !empty($value);
}

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
        while ($row = oci_fetch_assoc($query2)) {
            $city = $row['CID'];
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
            <form method="post">
                <label for="new_service">Add a new service</label>
                <select name="new_service">
                    <option disabled selected value>Choose a service</option>
                    <?php while ($get_services = oci_fetch_assoc($query4)) : ?>
                        <option value="<?= $get_services['SID'] ?>"><?= $get_services['CATEGORY'] ?></option>
                    <?php endwhile; ?>
                </select>
                <button type="submit">ADD</button>
            </form>
            <form method="post">
                <label for="deleted_service">Delete a service</label>
                <select name="deleted_service">
                    <option disabled selected value>Choose a service</option>
                    <?php while ($get_services = oci_fetch_assoc($query5)) : ?>
                        <option value="<?= $get_services['SID'] ?>"><?= $get_services['CATEGORY'] ?></option>
                    <?php endwhile; ?>
                </select>
                <button type="submit">DELETE</button>
            </form>
            <form method="post">
                <table>
                    <tr>
                        <th>City</th>
                    </tr>
                    <?php while ($row = oci_fetch_assoc($query2)) { ?>
                        <tr>
                            <td><?= $row['CNAME'] ?>
                                <input type="hidden" name="city" value="<?php echo $row['CID'] ?>"></td>
                            <?php $query3 = oci_parse($db, "SELECT DISTINCT W.SERVICE, S.CATEGORY, C.CID, S.CAT_DESCRIPTION, W.SERVICE_LEVEL, S.SID, W.CHARGE_PER_HOUR
                                                     FROM WORK_OFFERS W, SERVICES S, CITIES C
                                                     WHERE W.SERVICE = S.SID
                                                     AND W.DATE_DELETED IS NULL
                                                     AND W.PROFESSIONAL = {$aid}
                                                     AND C.CID = {$row['CID']}
                                                     ORDER BY S.CATEGORY");
                            oci_execute($query3); ?>
                            <td>
                                <table>
                                    <tr>
                                        <th>Services</th>
                                        <th>Category Description</th>
                                        <th>Charge per Hour</th>
                                        <th>Service Level</th>
                                        <th>Save Edits</th>
                                    </tr>
                                    <?php while ($row_offer = oci_fetch_assoc($query3)) { ?>
                                        <tr>
                                            <td><?= $row_offer['CATEGORY'] ?> - <?= $row_offer['SID']; ?>
                                                <input type="hidden" name="service"
                                                       value="<?php echo $row_offer['SID']; ?>"></td>
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
                                            </td>
                                            <td>
                                                <button type="submit">Save</button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                <?php
                if (isset($_POST['charge_per_hour'])) {
                    if (checkRequiredField($_POST['charge_per_hour'])) {
                        $charge = $_POST['charge_per_hour'];
                        $service = $_POST['service'];
                        $city = $_POST['city'];
                        var_dump($_POST['service']);
                        //header("Location: update_service.php?city=$city&service=$service&charge_per_hour=$charge");
                    }
                }
                ?>
            </form>
        </div>
    </main>
    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>