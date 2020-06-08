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

        <?php if ($row['ROLE'] == 1) { ?>
            <h2>New Requests</h2>
            <div class="shadow">
                <?php if ($row2 = oci_fetch_assoc($query2)) {
                    $query3 = oci_parse($db, "SELECT R.*, H.REQUEST, H.DATETIME, H.STATUS, S.CATEGORY, C.CNAME, A.FNAME, A.LNAME 
                                     FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W, SERVICES S, ACCOUNTS A, CITIES C
                                     WHERE R.WORK_OFFER = W.WID 
                                     AND W.CITY = C.CID
                                     AND R.USER_ID = A.AID
                                     AND H.REQUEST = R.RID
                                     AND W.SERVICE = S.SID
                                     AND H.STATUS = 0
                                     AND W.PROFESSIONAL = {$aid}
                                     ORDER BY H.DATETIME DESC");
                    oci_execute($query3);
                    $num_of_new = 1;
                    ?>
                    <table class="requests">
                        <tr>
                            <th>#</th>
                            <th>FROM</th>
                            <th>REQUESTED<br>SERVICE</th>
                            <th>IN</th>
                            <th>NUMBER OF<br>HOURS</th>
                            <th>ESTIMATED<br>PRICE</th>
                            <th>DATE<br>RECEIVED</th>
                            <th>STATUS</th>
                        </tr>
                        <?php while ($row3 = oci_fetch_assoc($query3)) {
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
                            if (!oci_fetch_assoc($query4)) { ?>
                                <tr>
                                    <td><?php echo $num_of_new; $num_of_new++; ?></td>
                                    <td><?= $row3['FNAME'] . ' ' . $row3['LNAME'] ?></td>
                                    <td><?= $row3['CATEGORY'] ?></td>
                                    <td><?= $row3['CNAME'] ?></td>
                                    <td><?= $row3['NUM_OF_HRS'] ?></td>
                                    <td><?= $row3['NUM_OF_HRS'] * $row3['CHARGE_PER_HOUR'] ?></td>
                                    <td><?= $row3['DATETIME'] ?></td>
                                    <td><a href="accepted_request.php?id=<?= $row3['RID'] ?>">Accept</a>
                                        <a href="rejected_request.php?id=<?= $row3['RID'] ?>">Reject</a></td>
                                </tr>
                                <tr>
                                    <td colspan="8">PROBLEM DESCRIPTION:<br><?= $row3['DESCRIPTION'] ?></td>
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
        <?php if ($row['ROLE'] == 1) { ?>
            <h2>Accepted Requests</h2>
            <div class="shadow">
                <?php if ($row2 = oci_fetch_assoc($query2)) { ?>
                    <table class="requests">
                        <tr>
                            <th>#</th>
                            <th>FROM</th>
                            <th>REQUESTED<br>SERVICE</th>
                            <th>IN</th>
                            <th>NUMBER OF<br>HOURS</th>
                            <th>ESTIMATED<br>PRICE</th>
                            <th>DATE<br>RECEIVED</th>
                            <th>STATUS</th>
                        </tr>
                        <?php
                        $query3 = oci_parse($db, "SELECT R.*, H.DATETIME, H.STATUS, S.CATEGORY, C.CNAME, A.FNAME, A.LNAME, A.AREA_CODE, A.PHONE_NUMBER 
                                     FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W, SERVICES S, ACCOUNTS A, CITIES C
                                     WHERE R.WORK_OFFER = W.WID 
                                     AND R.USER_ID = A.AID
                                     AND W.CITY = C.CID
                                     AND H.REQUEST = R.RID
                                     AND W.SERVICE = S.SID
                                     AND H.STATUS = 1
                                     AND W.PROFESSIONAL = {$aid}
                                     ORDER BY H.DATETIME DESC");
                        oci_execute($query3);
                        $num_of_accepted = 1;
                        while ($row3 = oci_fetch_assoc($query3)): ?>
                            <tr>
                                <td><?php echo $num_of_accepted; $num_of_accepted++; ?></td>
                                <td><?= $row3['FNAME'] . ' ' . $row3['LNAME'] ?></td>
                                <td><?= $row3['CATEGORY'] ?></td>
                                <td><?= $row3['CNAME'] ?></td>
                                <td><?= $row3['NUM_OF_HRS'] ?></td>
                                <td><?= $row3['NUM_OF_HRS'] * $row3['CHARGE_PER_HOUR'] ?></td>
                                <td><?= $row3['DATETIME'] ?></td>
                                <td><a href="#">Accepted</a></td>
                            </tr>
                            </tr>
                            <tr>
                                <td colspan="1">CONTACT:<br><?= '+' . $row3['AREA_CODE'] . ' ' . $row['PHONE_NUMBER'] ?></td>
                                <td colspan="7">PROBLEM DESCRIPTION:<br><?= $row3['DESCRIPTION'] ?></td>
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
        <?php if ($row['ROLE'] == 1) { ?>
            <h2>Rejected Requests</h2>
            <div class="shadow">
                <?php if ($row2 = oci_fetch_assoc($query2)) { ?>
                    <table class="requests">
                        <tr>
                            <th>#</th>
                            <th>FROM</th>
                            <th>REQUESTED<br>SERVICE</th>
                            <th>IN</th>
                            <th>NUMBER OF<br>HOURS</th>
                            <th>ESTIMATED<br>PRICE</th>
                            <th>DATE<br>RECEIVED</th>
                            <th>STATUS</th>
                        </tr>
                        <?php
                        $query3 = oci_parse($db, "SELECT R.*, H.DATETIME, H.STATUS, S.CATEGORY, C.CNAME, A.FNAME, A.LNAME 
                                     FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W, SERVICES S, ACCOUNTS A, CITIES C
                                     WHERE R.WORK_OFFER = W.WID 
                                     AND R.USER_ID = A.AID
                                     AND W.CITY = C.CID
                                     AND H.REQUEST = R.RID
                                     AND W.SERVICE = S.SID
                                     AND H.STATUS = 2
                                     AND W.PROFESSIONAL = {$aid}
                                     ORDER BY H.DATETIME DESC");
                        oci_execute($query3);
                        $num_of_rejected = 1;
                        while ($row3 = oci_fetch_assoc($query3)): ?>
                            <tr>
                                <td><?php echo $num_of_rejected; $num_of_rejected++; ?></td>
                                <td><?= $row3['FNAME'] . ' ' . $row3['LNAME'] ?></td>
                                <td><?= $row3['CATEGORY'] ?></td>
                                <td><?= $row3['CNAME'] ?></td>
                                <td><?= $row3['NUM_OF_HRS'] ?></td>
                                <td><?= $row3['NUM_OF_HRS'] * $row3['CHARGE_PER_HOUR'] ?></td>
                                <td><?= $row3['DATETIME'] ?></td>
                                <td><a href="#">Rejected</a></td>
                            </tr>
                            <tr>
                                <td colspan="8">PROBLEM DESCRIPTION:<br><?= $row3['DESCRIPTION'] ?></td>
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
            $num_of_sent = 1;
            if (oci_fetch($query)):
            ?>
            <table class="sent-requests">
                <tr>
                    <th>#</th>
                    <th>SERVICE</th>
                    <th>CITY</th>
                    <th>PROFESSIONAL</th>
                    <th>ESTIMATED PRICE</th>
                    <th>DATE</th>
                    <th>STATUS</th>
                </tr>
                <?php
                $query = oci_parse($db, "SELECT CATEGORY, STATUS, DATETIME, R.DESCRIPTION, SERVICE, FNAME, LNAME, AREA_CODE, PHONE_NUMBER, CNAME, R.NUM_OF_HRS, R.CHARGE_PER_HOUR 
                                                     FROM REQUESTS R
                                                     JOIN WORK_OFFERS ON WORK_OFFER = WID
                                                     JOIN SERVICES ON SERVICE = SID
                                                     JOIN CITIES ON CITY = CID
                                                     JOIN REQUESTS_HISTORY ON REQUEST = RID
                                                     JOIN ACCOUNTS ON PROFESSIONAL = AID
                                                     WHERE USER_ID={$_SESSION['user_id']}
                                                     ORDER BY STATUS");
                oci_execute($query);
                ?>
                <?php while ($row = oci_fetch_assoc($query)) {
                    if ($row['STATUS'] == 0) {
                        $queryN = oci_parse($db, "SELECT CATEGORY, STATUS, DATETIME, DESCRIPTION FROM REQUESTS
                                                     JOIN WORK_OFFERS ON WORK_OFFER = WID
                                                     JOIN SERVICES ON SERVICE = SID
                                                     JOIN REQUESTS_HISTORY ON REQUEST = RID
                                                     WHERE USER_ID={$_SESSION['user_id']}
                                                     AND SERVICE = {$row['SERVICE']}
                                                     AND STATUS IN (1, 2)
                                                     ORDER BY STATUS");
                        oci_execute($queryN);
                        $checkStatus = oci_fetch_assoc($queryN);
                        if (!$checkStatus) { ?>
                    <tr>
                        <td><?php echo $num_of_sent; $num_of_sent++; ?></td>
                        <td><?= $row['CATEGORY'] ?></td>
                        <td><?= $row['CNAME'] ?></td>
                        <td><?= $row['FNAME'] . ' ' . $row['LNAME'] ?></td>
                        <td> <?= $row['CHARGE_PER_HOUR'] * $row['NUM_OF_HRS'] . 'BAM' ?></td>
                        <td><?= $row['DATETIME'] ?></td>
                            <td><a href="#">Pending</a></td>
                    </tr>
                            <tr>
                                <td colspan="7">Problem Description: <br> <?= $row['DESCRIPTION'] ?></td>
                            </tr>
                        <?php }  } else if ($row['STATUS'] == 1): ?>
                        <tr>
                            <td><?php echo $num_of_sent; $num_of_sent++; ?></td>
                            <td><?= $row['CATEGORY'] ?></td>
                            <td><?= $row['CNAME'] ?></td>
                            <td><?= $row['FNAME'] . ' ' . $row['LNAME'] ?></td>
                            <td> <?= $row['CHARGE_PER_HOUR'] * $row['NUM_OF_HRS'] . 'BAM' ?></td>
                        <td><?= $row['DATETIME'] ?></td>
                            <td><a href="#">Approved</a></td>
                        </tr>
                        <tr>
                            <td colspan="7">Problem Description: <br> <?= $row['DESCRIPTION'] ?></td>
                        </tr>
                        <?php elseif ($row['STATUS'] == 2): ?>
                        <tr>
                            <td><?php echo $num_of_sent; $num_of_sent++; ?></td>
                            <td><?= $row['CATEGORY'] ?></td>
                            <td><?= $row['CNAME'] ?></td>
                            <td><?= $row['FNAME'] . ' ' . $row['LNAME'] ?></td>
                            <td> <?= $row['CHARGE_PER_HOUR'] * $row['NUM_OF_HRS'] . 'BAM' ?></td>
                            <td><?= $row['DATETIME'] ?></td>
                            <td><a href="#">Rejected</a></td>
                        </tr>
                        <tr>
                            <td colspan="7">Problem Description: <br> <?= $row['DESCRIPTION'] ?></td>
                        </tr>
                        <?php endif; ?>

                <?php } ?>
            </table>
        </div>
    <?php else: ?>
        <p>You have no sent requests.</p>
    <?php endif; ?>
    </main>

    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>