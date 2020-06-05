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
            <table>
                <tr>
                    <th>City</th>
                    <th>Services</th>
                    <th>Category Description</th>
                    <th>Charge per Hour</th>
                    <th>Service Level</th>
                    <th>Save Edits</th>
                </tr>
                <?php while ($row = oci_fetch_assoc($query2)) { ?>
                <tr>
                    <td><?= $row['CNAME'] ?></td>
                    <?php $query3 = oci_parse($db, "SELECT DISTINCT W.SERVICE, S.CATEGORY, S.CAT_DESCRIPTION
                                                     FROM WORK_OFFERS W, SERVICES S, CITIES C
                                                     WHERE W.SERVICE = S.SID
                                                     AND W.PROFESSIONAL = {$aid}
                                                     AND C.CID = {$row['CID']}
                                                     ORDER BY S.CATEGORY");
                    oci_execute($query3)?>
                    <td>
                        <table>
                    <?php while ($services = oci_fetch_assoc($query3)) : ?>
                    <tr><td><?= $services['CATEGORY'] ?></td></tr>
                    <?php endwhile; ?>
                        </table>
                    </td>
                    <td>
                        <?php $query3 = oci_parse($db, "SELECT DISTINCT W.SERVICE, S.CATEGORY, S.CAT_DESCRIPTION
                                                     FROM WORK_OFFERS W, SERVICES S, CITIES C
                                                     WHERE W.SERVICE = S.SID
                                                     AND W.PROFESSIONAL = {$aid}
                                                     AND C.CID = {$row['CID']}
                                                     ORDER BY S.CATEGORY");
                        oci_execute($query3)?>
                        <table>
                            <?php while ($descriptions = oci_fetch_assoc($query3)) : ?>
                            <tr>
                                <td><?= $descriptions['CAT_DESCRIPTION'] ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </table>
                    </td>
                    <td>
                        <?php $query3 = oci_parse($db, "SELECT DISTINCT W.SERVICE, S.CATEGORY, W.CHARGE_PER_HOUR
                                                     FROM WORK_OFFERS W, SERVICES S, CITIES C
                                                     WHERE W.SERVICE = S.SID
                                                     AND W.PROFESSIONAL = {$aid}
                                                     AND C.CID = {$row['CID']}
                                                     ORDER BY S.CATEGORY");
                        oci_execute($query3)?>
                        <table>
                            <?php while ($charge_per_hour = oci_fetch_assoc($query3)) : ?>
                                <tr>
                                    <td><?= $charge_per_hour['CHARGE_PER_HOUR'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </table>
                    </td>
                    <td>
                        <?php $query3 = oci_parse($db, "SELECT DISTINCT W.SERVICE, S.CATEGORY, W.SERVICE_LEVEL
                                                     FROM WORK_OFFERS W, SERVICES S, CITIES C
                                                     WHERE W.SERVICE = S.SID
                                                     AND W.PROFESSIONAL = {$aid}
                                                     AND C.CID = {$row['CID']}
                                                     ORDER BY S.CATEGORY");
                        oci_execute($query3)?>
                        <table>
                            <?php while ($level = oci_fetch_assoc($query3)) : ?>
                                <tr>
                                    <td><?= $level['SERVICE_LEVEL'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </table>
                    </td>
                    <td>
                        <?php $query3 = oci_parse($db, "SELECT DISTINCT W.SERVICE, S.CATEGORY
                                                     FROM WORK_OFFERS W, SERVICES S, CITIES C
                                                     WHERE W.SERVICE = S.SID
                                                     AND W.PROFESSIONAL = {$aid}
                                                     AND C.CID = {$row['CID']}
                                                     ORDER BY S.CATEGORY");
                        oci_execute($query3)?>
                        <table>
                            <?php while ($editRow = oci_fetch_assoc($query3)) : ?>
                                <tr>
                                    <td><button type="submit">SAVE</button></td>
                                </tr>
                            <?php endwhile; ?>
                        </table>
                    </td>
                </tr>
                <?php } ?>
            </table>

        </div>
    </main>
    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>