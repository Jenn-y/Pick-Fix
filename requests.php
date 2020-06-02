<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once("includes/db.php");

$aid = $_SESSION['user_id'];
$q = "SELECT * FROM accounts WHERE aid={$aid}";
$query = oci_parse($db, $q);
oci_execute($query);

$row = oci_fetch_assoc($query);

/*$query_requests = oci_parse($db, "SELECT r.* FROM requests, request_")*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link href="css/header.css" rel="stylesheet">
    <link href="css/profile.css" rel="stylesheet">
    <link rel="stylesheet" href="css/footer.css">

    <title>Requests</title>
</head>
<body id="requests">
<div id="page-container">
    <?php include('includes/header.php'); ?>

    <main class="center">
        <?php if ($row['ROLE'] == 1){ ?>
            <h2>New Requests</h2>
            <?php
            $query = oci_parse($db, "SELECT * FROM REQUESTS
                                                 WHERE USER_ID = {$_SESSION['user_id']}");
            oci_execute($query);

            //if(oci_fetch($query)):
                ?>
            <div class="shadow">
                <table class="requests">
                    <tr>
                        <th>Request</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </table>
            </div>
        <?php } ?>

        <h2 id="sentRequests">Sent Requests</h2>

        <div class="shadow">
            <?php
            $query = oci_parse($db, "SELECT * FROM REQUESTS
                                                 WHERE USER_ID = {$_SESSION['user_id']}");
            oci_execute($query);

            if(oci_fetch($query)):
            ?>
            <table class="sent-requests">
                <tr>
                    <th>Request</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                <?php
                $query = oci_parse($db, "SELECT CATEGORY, STATUS, DATETIME, DESCRIPTION FROM REQUESTS
                                                     JOIN WORK_OFFERS ON WORK_OFFER = WID
                                                     JOIN SERVICES ON SERVICE = SID
                                                     JOIN REQUESTS_HISTORY ON REQUEST = RID
                                                     WHERE USER_ID={$_SESSION['user_id']}");
                oci_execute($query);
                ?>
                <?php while($row = oci_fetch_assoc($query)): ?>
                    <tr>
                        <td><?= $row['CATEGORY'] ?></td>
                        <td><?= $row['DATETIME'] ?></td>
                        <?php if($row['STATUS'] = 0): ?>
                            <td><a href="#">Pending</a></td>
                        <?php elseif($row['STATUS'] = 1): ?>
                            <td><a href="#">Approved</a></td>
                        <?php elseif($row['STATUS'] = 2): ?>
                            <td><a href="#">Rejected</a></td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    <?php else: ?>
        <p>You have no new Requests.</p>
    <?php endif; ?>
    </main>

    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>