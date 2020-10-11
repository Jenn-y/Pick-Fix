<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("includes/functions.php");
check_if_logged_in();
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/footer.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!--    <script type="text/javascript">-->
<!--       -->
<!--        if (screen.width <= 800) {-->
<!--            document.location = "requests-m";-->
<!--        }-->
<!--      -->
<!--    </script>-->
    <title>Requests</title>

    <style>
        h1 {
            font-size: 2em;
            font-family: Roboto, sans-serif;
            margin-bottom: 0 !important;
            font-weight: bold !important;
        }
        a:hover {
            text-decoration: none;
            color: black;
        }
    </style>
</head>
<body id="requests">
<div id="page-container">
    <?php include('includes/header.php'); ?>
        <main class="center">
            <!--            NEW REQUESTS -->
            <?php
            $query2 = oci_parse($db, "SELECT H.STATUS, H.DATETIME, H.REQUEST
                                     FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W
                                     WHERE R.WORK_OFFER = W.WID 
                                     AND H.REQUEST = R.RID
                                     AND STATUS = 0
                                     AND W.PROFESSIONAL = {$aid}");
            oci_execute($query2);
            ?>
            <?php if ($row['ROLE'] == 1) {
                $num_of_new = 1; ?>
                <h2>New requests</h2>
                <div class="shadow">
                    <?php if ($row2 = oci_fetch_assoc($query2)) {
                        $query3 = oci_parse($db, "SELECT R.*, H.REQUEST, H.DATETIME, H.STATUS, S.CATEGORY, C.CNAME, A.FNAME, A.LNAME, offer_amount 
                                     FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W, SERVICES S, ACCOUNTS A, CITIES C
                                     WHERE R.WORK_OFFER = W.WID 
                                     AND W.CITY = C.CID
                                     AND R.USER_ID = A.AID
                                     AND H.REQUEST = R.RID
                                     AND W.SERVICE = S.SID
                                     AND H.STATUS = 0 and offer_amount = -1
                                     AND W.PROFESSIONAL = {$aid}
                                     ORDER BY H.DATETIME DESC");
                        oci_execute($query3);
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
                                        <td style="border-right: 1px solid darkblue;"><?php echo $num_of_new;
                                            $num_of_new++; ?></td>
                                        <td><?= $row3['FNAME'] . ' ' . $row3['LNAME'] ?></td>
                                        <td><?= $row3['CATEGORY'] ?></td>
                                        <td><?= $row3['CNAME'] ?></td>
                                        <td><?= $row3['NUM_OF_HRS'] ?></td>
                                        <td><?= $row3['NUM_OF_HRS'] * $row3['CHARGE_PER_HOUR'] ?></td>
                                        <td><?= $row3['DATETIME'] ?></td>
                                        <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#new-requests<?=$num_of_new?>">Review</button></td>
                                        <div class="modal fade" id="new-requests<?=$num_of_new?>">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Incoming request</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <h5>Problem description</h5>
                                                        <p><?= $row3['DESCRIPTION'] ?></p>
                                                        <hr>
                                                        <h5>Send an offer</h5>
                                                        <form action="send_offer.php?rid=<?= $row3['RID'] ?>" method="post">
                                                            <input type="number" id="offer_amount" name="offer_amount" placeholder="Your offer in BAM..." style="margin-bottom: 0 !important;">
                                                            <button type="submit" class="btn btn-success">Send offer</button>
                                                        </form>
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <a href="rejected_request.php?id=<?=$row3['RID']?>&amount=<?=$row3['NUM_OF_HRS'] * $row3['CHARGE_PER_HOUR']?>"><button type="button" class="btn btn-danger">Reject</button></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </table>
                    <?php }
                    if ($num_of_new == 1) { ?>
                        <p style="padding: 1rem; ">You have no new requests.</p>
                    <?php } ?>
                </div>
            <?php } ?>

            <!--            OFFERS SENT -->
            <?php
            $query2 = oci_parse($db, "SELECT H.STATUS, H.DATETIME, H.REQUEST
                                     FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W
                                     WHERE R.WORK_OFFER = W.WID 
                                     AND H.REQUEST = R.RID
                                     AND STATUS = 0
                                     AND W.PROFESSIONAL = {$aid}");
            oci_execute($query2);
            ?>
            <?php if ($row['ROLE'] == 1) {
                $num_of_sent = 1; ?>
                <h2>Sent offers</h2>
                <div class="shadow">
                    <?php if ($row2 = oci_fetch_assoc($query2)) {
                        $query3 = oci_parse($db, "SELECT R.*, H.REQUEST, H.DATETIME, H.STATUS, S.CATEGORY, C.CNAME, A.FNAME, A.LNAME, offer_amount 
                                     FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W, SERVICES S, ACCOUNTS A, CITIES C
                                     WHERE R.WORK_OFFER = W.WID 
                                     AND W.CITY = C.CID
                                     AND R.USER_ID = A.AID
                                     AND H.REQUEST = R.RID
                                     AND W.SERVICE = S.SID
                                     AND W.PROFESSIONAL = {$aid}
                                     ORDER BY H.DATETIME DESC");
                        oci_execute($query3);
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
                                if($row3['STATUS'] == 0 && $row3['OFFER_AMOUNT'] != -1) {
                                    $request_id = $row3['REQUEST'];
                                    $query4 = oci_parse($db, "SELECT R.*, H.REQUEST, H.DATETIME, H.STATUS, S.CATEGORY, A.FNAME, A.LNAME, A.PHONE_NUMBER, offer_amount 
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
                                            <td style="border-right: 1px solid darkblue;"><?php echo $num_of_sent;
                                                $num_of_sent++; ?></td>
                                            <td><?= $row3['FNAME'] . ' ' . $row3['LNAME'] ?></td>
                                            <td><?= $row3['CATEGORY'] ?></td>
                                            <td><?= $row3['CNAME'] ?></td>
                                            <td><?= $row3['NUM_OF_HRS'] ?></td>
                                            <td><?= $row3['NUM_OF_HRS'] * $row3['CHARGE_PER_HOUR'] ?></td>
                                            <td><?= $row3['DATETIME'] ?></td>
                                            <td><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#sent-offers<?=$num_of_sent?>">Pending</button></td>
                                            <div class="modal fade" id="sent-offers<?=$num_of_sent?>">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Offer: Pending</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <p>Your offer of <?=$row3['OFFER_AMOUNT']?> has been sent. </p>
                                                            <h2>Problem description</h2>
                                                            <p><?= $row3['DESCRIPTION'] ?></p>
                                                        </div>

                                                        <!-- Modal footer -->
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </tr>
                                    <?php } ?>
                                <?php } else if($row3['STATUS'] == 1) {?>
                                    <tr>
                                        <td style="border-right: 1px solid darkblue;"><?php echo $num_of_sent;
                                            $num_of_sent++; ?></td>
                                        <td><?= $row3['FNAME'] . ' ' . $row3['LNAME'] ?></td>
                                        <td><?= $row3['CATEGORY'] ?></td>
                                        <td><?= $row3['CNAME'] ?></td>
                                        <td><?= $row3['NUM_OF_HRS'] ?></td>
                                        <td><?= $row3['NUM_OF_HRS'] * $row3['CHARGE_PER_HOUR'] ?></td>
                                        <td><?= $row3['DATETIME'] ?></td>
                                        <td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#accepted-offer<?=$num_of_sent?>">Offer Accepted</button></td>
                                        <div class="modal fade" id="accepted-offer<?=$num_of_sent?>">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Offer: Accepted</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <p>Your offer of <b><?=$row3['OFFER_AMOUNT']?></b>BAM to solve their problem has been <span class="text-success font-italic font-weight-bold">accepted</span>.</p>
                                                        <p>Problem description:</p>
                                                        <p><?= $row3['DESCRIPTION'] ?></p>
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                <?php } else if($row3['STATUS'] == 2) { ?>
                                    <tr>
                                        <td style="border-right: 1px solid darkblue;"><?php echo $num_of_sent;
                                            $num_of_sent++; ?></td>
                                        <td><?= $row3['FNAME'] . ' ' . $row3['LNAME'] ?></td>
                                        <td><?= $row3['CATEGORY'] ?></td>
                                        <td><?= $row3['CNAME'] ?></td>
                                        <td><?= $row3['NUM_OF_HRS'] ?></td>
                                        <td><?= $row3['NUM_OF_HRS'] * $row3['CHARGE_PER_HOUR'] ?></td>
                                        <td><?= $row3['DATETIME'] ?></td>
                                        <td><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejected-offer<?=$num_of_sent?>">Offer Rejected</button></td>
                                        <div class="modal fade" id="rejected-offer<?=$num_of_sent?>">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Offer: rejected</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <p>Your offer of <b><?=$row3['OFFER_AMOUNT']?></b>BAM to solve their problem has been <span class="text-danger font-italic font-weight-bold">rejected</span>.</p>
                                                        <p>Problem description:</p>
                                                        <p><?= $row3['DESCRIPTION'] ?></p>
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </table>
                    <?php }
                    if ($num_of_sent == 1) { ?>
                        <p style="padding: 1rem; ">You have no new requests.</p>
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
                        <th>Feedback</th>
                    </tr>
                    <?php
                    $query = oci_parse($db, "SELECT OFFER_AMOUNT, CATEGORY, STATUS, DATETIME, R.DESCRIPTION, SERVICE, FNAME, LNAME, AREA_CODE, PHONE_NUMBER, CNAME, R.NUM_OF_HRS, R.CHARGE_PER_HOUR, R.RID 
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
                        if ($row['STATUS'] == 0 && $row['OFFER_AMOUNT'] == -1) {
                            $queryN = oci_parse($db, "SELECT CATEGORY, STATUS, DATETIME, DESCRIPTION FROM REQUESTS
                                                     JOIN WORK_OFFERS ON WORK_OFFER = WID
                                                     JOIN SERVICES ON SERVICE = SID
                                                     JOIN REQUESTS_HISTORY ON REQUEST = RID
                                                     WHERE REQUEST = {$row['RID']}
                                                     AND SERVICE = {$row['SERVICE']}
                                                     AND STATUS IN (1, 2)
                                                     ORDER BY STATUS");
                            oci_execute($queryN);
                            $checkStatus = oci_fetch_assoc($queryN);
                            if (!$checkStatus) { ?>
                                <tr>
                                    <td style="border-right: 1px solid darkblue;"><?php echo $num_of_sent;
                                        $num_of_sent++; ?></td>
                                    <td><?= $row['CATEGORY'] ?></td>
                                    <td><?= $row['CNAME'] ?></td>
                                    <td><?= $row['FNAME'] . ' ' . $row['LNAME'] ?></td>
                                    <td> <?= $row['CHARGE_PER_HOUR'] * $row['NUM_OF_HRS'] . 'BAM' ?></td>
                                    <td><?= $row['DATETIME'] ?></td>
                                    <td><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#sent-requests<?=$num_of_sent?>">Pending</button></td>
                                    <div class="modal fade" id="sent-requests<?=$num_of_sent?>">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h4 class="modal-title">Problem description</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>

                                                <div class="modal-body">
                                                    <p>Professional will review your request as soon as possible and send you an offer</p>
                                                    <p>Your problem description:</p>
                                                    <p><?= $row['DESCRIPTION'] ?></p>
                                                </div>

                                                <!-- Modal footer -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <td></td>
                            <?php }
                        }
                        else if($row['STATUS'] == 0 && $row['OFFER_AMOUNT'] != -1) {
                            $queryN = oci_parse($db, "SELECT CATEGORY, STATUS, DATETIME, DESCRIPTION FROM REQUESTS
                                                     JOIN WORK_OFFERS ON WORK_OFFER = WID
                                                     JOIN SERVICES ON SERVICE = SID
                                                     JOIN REQUESTS_HISTORY ON REQUEST = RID
                                                     WHERE REQUEST = {$row['RID']}
                                                     AND SERVICE = {$row['SERVICE']}
                                                     AND STATUS IN (1, 2)
                                                     ORDER BY STATUS");
                            oci_execute($queryN);
                            $checkStatus = oci_fetch_assoc($queryN);
                            if (!$checkStatus) { ?>
                                <tr>
                                    <td style="border-right: 1px solid darkblue;"><?php echo $num_of_sent;
                                        $num_of_sent++; ?></td>
                                    <td><?= $row['CATEGORY'] ?></td>
                                    <td><?= $row['CNAME'] ?></td>
                                    <td><?= $row['FNAME'] . ' ' . $row['LNAME'] ?></td>
                                    <td> <?= $row['CHARGE_PER_HOUR'] * $row['NUM_OF_HRS'] . 'BAM' ?></td>
                                    <td><?= $row['DATETIME'] ?></td>
                                    <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sent-requests<?=$num_of_sent?>">New offer!</button></td>
                                    <div class="modal fade" id="sent-requests<?=$num_of_sent?>">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h4 class="modal-title">New offer!</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>

                                                <div class="modal-body">
                                                    <p>The seller has offered <b><?= $row['OFFER_AMOUNT'] ?></b>BAM to solve your problem.</p>
                                                    <hr>
                                                    <h6>Your problem description:</h6>
                                                    <p><?= $row['DESCRIPTION'] ?></p>
                                                </div>

                                                <!-- Modal footer -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <a href="rejected_request.php?id=<?=$row['RID']?>&amount=<?=$row['OFFER_AMOUNT']?>" class="btn btn-danger">Reject</a>
                                                    <a href="accepted_request.php?id=<?=$row['RID']?>&amount=<?=$row['OFFER_AMOUNT']?>" class="btn btn-success">Accept</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <td></td>
                            <?php }
                        }
                        else if ($row['STATUS'] == 1): ?>
                            <tr>
                                <td style="border-right: 1px solid darkblue;"><?php echo $num_of_sent;
                                    $num_of_sent++; ?></td>
                                <td><?= $row['CATEGORY'] ?></td>
                                <td><?= $row['CNAME'] ?></td>
                                <td><?= $row['FNAME'] . ' ' . $row['LNAME'] ?></td>
                                <td> <?= $row['CHARGE_PER_HOUR'] * $row['NUM_OF_HRS'] . 'BAM' ?></td>
                                <td><?= $row['DATETIME'] ?></td>
                                <td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#job-success<?=$num_of_sent?>">Accepted</button></td>
                                <div class="modal fade" id="job-success<?=$num_of_sent?>">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h4 class="modal-title">Deal completed</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <h5>Congratulations!</h5>
                                                <p>You have accepted the seller's offer of <b><?=$row['OFFER_AMOUNT']?></b>BAM to complete the job.</p>
                                                <hr>
                                                <h5>Problem description:</h5>
                                                <p><?= $row['DESCRIPTION'] ?></p>
                                            </div>

                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                $checkRated = oci_parse($db, "SELECT R.JOB_RATING
                                                                                 FROM REQUESTS R
                                                                                 WHERE R.RID = {$row['RID']}
                                                                                 AND JOB_RATING IS NOT NULL");
                                oci_execute($checkRated);
                                $rated = oci_fetch_assoc($checkRated);
                                if (!$rated): ?>
                                <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#professional-rating<?=$num_of_sent?>">Rate</button></td>
                                <div class="modal fade" id="professional-rating<?=$num_of_sent?>">
                                    <div class="modal-dialog modal-md modal-dialog-centered">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h4 class="modal-title">Feedback!</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <form action="rate_professional.php?rid=<?=$row['RID']?>" method="post">
                                                <div class="modal-body">
                                                    <div>
                                                        <label for="rating">Rate the professional</label>
                                                        <select name="rating" id="rating" class="ml-3" required>
                                                            <option disabled selected>1-5</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                        </select>
                                                    </div>
                                                    <textarea name="comment" placeholder="Leave a review (optional)" style="margin-bottom: 0"></textarea>
                                                </div>

                                                <!-- Modal footer -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success">Rate</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php else: ?>
                                <td><i>Completed</i></td>
                                <?php endif; ?>
                            </tr>
                        <?php elseif ($row['STATUS'] == 2): ?>
                            <tr>
                                <td style="border-right: 1px solid darkblue;"><?php echo $num_of_sent;
                                    $num_of_sent++; ?></td>
                                <td><?= $row['CATEGORY'] ?></td>
                                <td><?= $row['CNAME'] ?></td>
                                <td><?= $row['FNAME'] . ' ' . $row['LNAME'] ?></td>
                                <td> <?= $row['CHARGE_PER_HOUR'] * $row['NUM_OF_HRS'] . 'BAM' ?></td>
                                <td><?= $row['DATETIME'] ?></td>
                                <td><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#job-fail<?=$num_of_sent?>">Rejected</button></td>
                                <div class="modal fade" id="job-fail<?=$num_of_sent?>">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h4 class="modal-title">Job canceled</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <p>The job has been canceled.</p>
                                                <h6>Your problem description:</h6>
                                                <p><?= $row['DESCRIPTION'] ?></p>
                                            </div>

                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <td></td>
                            </tr>
                        <?php endif; ?>
                    <?php } ?>
                </table>
            </div>
        <?php else: ?>
            <p style="padding: 1rem;">You have no sent requests.</p>
        <?php endif; ?>
    </main>
    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>