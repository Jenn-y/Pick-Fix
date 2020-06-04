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

    <?php
    $query2 = oci_parse($db, "SELECT H.STATUS, H.DATETIME, H.REQUEST
                                     FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W
                                     WHERE R.WORK_OFFER = W.WID 
                                     AND H.REQUEST = R.RID
                                     AND STATUS = 0
                                     AND W.PROFESSIONAL = {$aid}");
    oci_execute($query2);
    ?>
    <main class="center">

        <?php if ($row['ROLE'] == 1){ ?>
            <h2>New Requests</h2>
            <div class="shadow">
                <?php if($row2 = oci_fetch_assoc($query2)) {
                    $query3 = oci_parse($db, "SELECT R.*, H.REQUEST, H.DATETIME, H.STATUS, S.CATEGORY, A.FNAME, A.LNAME 
                                     FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W, SERVICES S, ACCOUNTS A
                                     WHERE R.WORK_OFFER = W.WID 
                                     AND R.USER_ID = A.AID
                                     AND H.REQUEST = R.RID
                                     AND W.SERVICE = S.SID
                                     AND H.STATUS = 0
                                     AND W.PROFESSIONAL = {$aid}");
                    oci_execute($query3);
                    ?>
                <table class="requests">
                    <tr>
                        <th>Request</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                    <?php while($row3 = oci_fetch_assoc($query3)){
                       $request_id = $row3['REQUEST'];
                       $query4 = oci_parse($db, "SELECT R.*, H.REQUEST, H.DATETIME, H.STATUS, S.CATEGORY, A.FNAME, A.LNAME 
                                     FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W, SERVICES S, ACCOUNTS A
                                     WHERE R.WORK_OFFER = W.WID 
                                     AND R.USER_ID = A.AID
                                     AND H.REQUEST = R.RID
                                     AND W.SERVICE = S.SID
                                     AND H.REQUEST = {$request_id}
                                     AND H.STATUS IN (1, 2)
                                     AND W.PROFESSIONAL = {$aid}");
                    oci_execute($query4);
                    if (!oci_fetch_assoc($query4)){ ?>
                        <tr>
                            <td><?= $row3['CATEGORY'] ?><br>
                                <?= $row3['FNAME'] . ' ' . $row3['LNAME'] ?>
                            </td>
                            <td><?= $row3['DATETIME'] ?></td>
                            <td><a href="accepted_request.php?id=<?= $row3['RID'] ?>">Accept</a>
                            <a href="rejected_request.php?id=<?= $row3['RID'] ?>">Reject</a></td>
                        </tr>
                    <?php } ?>
                    <?php } ?>
                </table>
                <?php } ?>
            </div>
        <?php } ?>

        <?php
        $query2 = oci_parse($db, "SELECT H.STATUS, H.DATETIME, H.REQUEST
                                     FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W
                                     WHERE R.WORK_OFFER = W.WID 
                                     AND H.REQUEST = R.RID
                                     AND H.STATUS = 1
                                     AND W.PROFESSIONAL = {$aid}");
        oci_execute($query2);
        ?>
        <?php if ($row['ROLE'] == 1){ ?>
            <h2>Accepted Requests</h2>
            <div class="shadow">
                <?php if ($row2 = oci_fetch_assoc($query2)){ ?>
                <table class="requests">
                    <tr>
                        <th>Request</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                    <?php
                    $query3 = oci_parse($db, "SELECT R.*, H.DATETIME, H.STATUS, S.CATEGORY, A.FNAME, A.LNAME 
                                     FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W, SERVICES S, ACCOUNTS A
                                     WHERE R.WORK_OFFER = W.WID 
                                     AND R.USER_ID = A.AID
                                     AND H.REQUEST = R.RID
                                     AND W.SERVICE = S.SID
                                     AND H.STATUS = 1
                                     AND W.PROFESSIONAL = {$aid}");
                    oci_execute($query3);
                    while($row3 = oci_fetch_assoc($query3)): ?>
                        <tr>
                            <td><?= $row3['CATEGORY'] ?><br>
                                <?= $row3['FNAME'] . ' ' . $row3['LNAME'] ?>
                            </td>
                            <td><?= $row3['DATETIME'] ?></td>
                            <td><a href="#">Accepted</a></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
        <?php } else { ?>
            <p>You have no accepted requests.</p>
        <?php } ?>
            </div>
        <?php } ?>

        <?php
        $query2 = oci_parse($db, "SELECT H.STATUS, H.DATETIME, H.REQUEST
                                     FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W
                                     WHERE R.WORK_OFFER = W.WID 
                                     AND H.REQUEST = R.RID
                                     AND H.STATUS = 2
                                     AND W.PROFESSIONAL = {$aid}");
        oci_execute($query2);
        ?>
        <?php if ($row['ROLE'] == 1){ ?>
            <h2>Rejected Requests</h2>
            <div class="shadow">
                <?php if ($row2 = oci_fetch_assoc($query2)){ ?>
                    <table class="requests">
                        <tr>
                            <th>Request</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                        <?php
                        $query3 = oci_parse($db, "SELECT R.*, H.DATETIME, H.STATUS, S.CATEGORY, A.FNAME, A.LNAME 
                                     FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W, SERVICES S, ACCOUNTS A
                                     WHERE R.WORK_OFFER = W.WID 
                                     AND R.USER_ID = A.AID
                                     AND H.REQUEST = R.RID
                                     AND W.SERVICE = S.SID
                                     AND H.STATUS = 2
                                     AND W.PROFESSIONAL = {$aid}");
                        oci_execute($query3);
                        while($row3 = oci_fetch_assoc($query3)): ?>
                                <tr>
                                    <td><?= $row3['CATEGORY'] ?><br>
                                        <?= $row3['FNAME'] . ' ' . $row3['LNAME'] ?>
                                    </td>
                                    <td><?= $row3['DATETIME'] ?></td>
                                    <td><a href="#">Rejected</a></td>
                                </tr>
                        <?php endwhile; ?>
                    </table>
                <?php } else { ?>
                    <p>You have no rejected requests.</p>
                <?php } ?>
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
                                                     WHERE USER_ID={$_SESSION['user_id']}
                                                     ORDER BY STATUS");
                oci_execute($query);
                ?>
                <?php while($row = oci_fetch_assoc($query)): ?>
                    <tr>
                        <td><?= $row['CATEGORY'] ?></td>
                        <td><?= $row['DATETIME'] ?></td>
                        <?php if($row['STATUS'] == 0): ?>
                            <td><a href="#">Pending</a></td>
                        <?php elseif($row['STATUS'] == 1): ?>
                            <td><a href="#">Approved</a></td>
                        <?php elseif($row['STATUS'] == 2): ?>
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