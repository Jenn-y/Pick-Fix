<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("includes/functions.php");
check_if_logged_in();
include_once("includes/db.php");

$aid = -1;

if (isset($_GET['id'])) {
    $aid = $_GET['id'];
} else {
    $aid = $_SESSION['user_id'];
}

$account = oci_parse($db, "SELECT * FROM accounts WHERE aid={$aid}");
oci_execute($account);
$row = oci_fetch_assoc($account);

if ($_POST && isset($_GET['id'])) {

    if (checkRequiredField($_POST['city']) && checkRequiredField($_POST['service']) && checkRequiredField($_POST['phone']) && checkRequiredField($_POST['problem-description'])) {
        // checkRequiredField($_POST['fname']) && checkRequiredField($_POST['lname']) &&
        // && checkRequiredField($_POST['num_of_hrs'])


        // Fetching the WID from table WORK_OFFERS
        $query = oci_parse($db, "SELECT WID, CHARGE_PER_HOUR FROM WORK_OFFERS
                                            WHERE SERVICE={$_POST['service']} AND CITY={$_POST['city']} AND PROFESSIONAL={$_GET['id']}");
        oci_execute($query);
        $wid = oci_fetch_assoc($query);

        // Inserting a row into REQUESTS
        // original
//        $query_request = oci_parse($db, "INSERT INTO requests(user_id, work_offer, description, charge_per_hour, num_of_hrs)
//              VALUES ({$_SESSION['user_id']}, {$wid['WID']}, '{$_POST['problem-description']}', {$wid['CHARGE_PER_HOUR']},
//              {$_POST['num_of_hrs']}) returning rid INTO :id");

        $num_of_hrs = 0;
        if(checkRequiredField($_POST['num_of_hrs'])) {
            $num_of_hrs = $_POST['num_of_hrs'];
        }

        // new
        $query_request = oci_parse($db, "INSERT INTO requests(user_id, work_offer, description, charge_per_hour, num_of_hrs)
              VALUES ({$_SESSION['user_id']}, {$wid['WID']}, '{$_POST['problem-description']}', {$wid['CHARGE_PER_HOUR']}, {$num_of_hrs}) returning rid INTO :id");

        oci_execute($query_request);
        oci_commit($db);

        oci_bind_by_name($query_request, ':id', $newId);
        oci_execute($query_request);
        oci_commit($db);

        $query_history = oci_parse($db, "INSERT INTO REQUESTS_HISTORY(datetime, status, request, offer_amount) VALUES (SYSDATE, 0, {$newId}, -1)");
        oci_execute($query_history);
        oci_commit($db);

        header('Location: requests');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link href="css/header.css" rel="stylesheet">
    <link href="css/profile.css" rel="stylesheet">
    <link rel="stylesheet" href="css/footer.css">

    <title>Profile</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function reqListener() {
            document.getElementById("estimate_price").innerHTML = this.responseText + 'BAM';
        }

        function testJavascriptRequest() {
            var oReq = new XMLHttpRequest();
            oReq.addEventListener("load", reqListener);
            var city = $('#city').val();
            var service = $('#service').val();
            var numOfHrs = $('#num_of_hrs').val();
            oReq.open("GET", "service_price.php?p_id=<?= $_GET['id']?>&c_id=" + city + '&s_id=' + service + '&num_of_hrs=' + numOfHrs);
            oReq.send();
        }
    </script>

    <style>
        a {
            font-family: Roboto, sans-serif;
        }
        h1 {
            font-size: 2em;
            font-family: Roboto, sans-serif;
            margin-bottom: 0 !important;
            font-weight: bold !important;
        }
        a:hover {
            text-decoration: none;
            color: #343a40;
        }

        .cat {
            margin-left: 3rem;
        }

        .cat li {
            padding-bottom: 0.5rem;
        }

        h2 {
            font-size: 24px !important;
        }

        h3 {
            font-size: 1.5em !important;
        }
        h4 {
            font-size: 16px !important;
        }
        input, textarea, select {
            margin-bottom: 2rem;
        }
        @media (max-width: 767px) {
            .puma {
                text-align: center;
            }
            .cat {
                margin: 0;
                list-style: none;
            }
        }
    </style>

</head>
<body>

<div id="page-container">
    <?php include('includes/header.php'); ?>

    <main class="center">
        <div class="shadow">
            <div class="user flex-container">
                <img src="<?= fetch_profile_image($row['AID'], $row['IMG_TYPE']); ?>" alt="default-user-image">
                <div>
                    <h2 id="username" style="color: #f4f4f4;"><?= $row['FNAME'] . ' ' . $row['LNAME'] ?></h2>
                    <p style="color: #f4f4f4;"><?= $row['PRIMARY_CITY'] ?>, Bosnia and Herzegovina</p>
                    <?php if ($row['ROLE'] == 1) {
                        $sql = oci_parse($db, "SELECT R.JOB_RATING
                                                       FROM REQUESTS R, WORK_OFFERS W
                                                       WHERE R.WORK_OFFER = W.WID
                                                       AND W.PROFESSIONAL = {$aid}
                                                       AND R.JOB_RATING IS NOT NULL");
                        oci_execute($sql);

                        $sum_rates = 0;
                        $num_of_rates = 0;

                        while ($request_row = oci_fetch_assoc($sql)) {
                            $sum_rates = $sum_rates + $request_row['JOB_RATING'];
                            $num_of_rates++;
                        }
                        $rating = 1;
                        if ($num_of_rates != 0) {
                            $rating = $sum_rates / $num_of_rates;
                        }
                        $empty_stars = 5;
                        ?>
                        <br><span style="color: #f4f4f4;">Service Rating: <span style="color: #f4f4f4;"><?php echo number_format((float)$rating, 2, '.', ''); ?><br></span></span>
                        <?php while ($rating >= 1) :
                            $rating--;
                            $empty_stars--; ?>
                            <span><i class="fa fa-star" style="color: #FFDF00;"></i></span>
                        <?php endwhile;
                        if ($rating > 0) :
                            $empty_stars--; ?>
                            <span><i class="fa fa-star-half-empty" style="color: #FFDF00"></i></span>
                        <?php endif;
                        while ($empty_stars >= 1) :
                            $empty_stars--; ?>
                            <span><i class="fa fa-star-o" style="color: #f4f4f4;"></i></span>
                        <?php endwhile; ?>
                        <span style="color:#C0C0C0;">(<?php echo $num_of_rates; ?> reviews)</span>
                    <?php } ?>
                </div>
            </div>
            <div class="container p-5 puma">
                <div class="row pb-4">
                    <div class="col-md-12">
                        <h3>About: </h3>
                        <?php if ($row['SHORT_BIOGRAPHY'] == null): ?>
                            <p><i>- No profile description provided -</i></p>
                        <?php else: ?>
                            <p><?= $row['SHORT_BIOGRAPHY'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($row['ROLE'] == 1): ?>
                <hr>
                <div class="row pb-4">
                    <div class="col-md-3">
                        <h3>Areas Served:</h3>
                        <ul class="cat">
                            <?php
                            $query = oci_parse($db, "SELECT DISTINCT CITY, CNAME FROM WORK_OFFERS
                                                            JOIN CITIES
                                                            ON CID = CITY
                                                            where professional = {$aid}
                                                            ORDER BY CNAME");
                            oci_execute($query);

                            while ($row3 = oci_fetch_assoc($query)): ?>
                                <li><?= $row3['CNAME'] ?></li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                    <div class="col-md-9">
                        <h3>Categories:</h3>
                        <ul class="cat">
                            <?php
                            $query = oci_parse($db, "SELECT DISTINCT SERVICE, CATEGORY FROM WORK_OFFERS
                                                            JOIN SERVICES
                                                            ON SERVICE = SID
                                                            where professional = {$aid}
                                                            ORDER BY CATEGORY");
                            oci_execute($query);

                            while ($row2 = oci_fetch_assoc($query)): ?>
                                <li><?= $row2['CATEGORY'] ?></li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
                <hr>
                <div class="row pb-4">
                    <div class="col-md-6">
                        <h3>Contact:</h3>
                        <p><?= '+' . $row['AREA_CODE'] . ' ' . $row['PHONE_NUMBER'] ?></p>
                    </div>
                    <div class="col-md-6 d-flex align-items-center justify-content-center justify-content-md-start">
                        <?php if (isset($_GET['id'])): ?>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Send a request!</button>
                        <div class="modal fade" id="myModal">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h4 class="modal-title">Describe your problem</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <?php if (isset($_GET['id'])): ?>
                                    <form action="" method="post">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 d-flex flex-column">
                                                    <?php
                                                    $query = oci_parse($db, "SELECT DISTINCT CITY, CNAME FROM WORK_OFFERS
                                                            JOIN CITIES
                                                            ON CID = CITY
                                                            where professional = {$aid}
                                                            ORDER BY CITY");
                                                    oci_execute($query); ?>

                                                    <select name="city" id="city" required>
                                                        <option disabled selected value>City</option>
                                                        <?php while ($row = oci_fetch_assoc($query)): ?>
                                                            <option value="<?= $row['CITY'] ?>"><?= $row['CNAME'] ?></option>
                                                        <?php endwhile; ?>
                                                    </select>

                                                    <?php
                                                    $query = oci_parse($db, "SELECT DISTINCT SERVICE, CATEGORY FROM WORK_OFFERS
                                                            JOIN SERVICES
                                                            ON SERVICE = SID
                                                            where professional = {$aid}
                                                            ORDER BY SERVICE");
                                                    oci_execute($query); ?>
                                                    <select name="service" id="service" required>
                                                        <option disabled selected value>Service</option>
                                                        <?php while ($row = oci_fetch_assoc($query)): ?>
                                                            <option value="<?= $row['SERVICE'] ?>"><?= $row['CATEGORY'] ?></option>
                                                        <?php endwhile; ?>
                                                    </select>

                                                    <input type="tel" name="phone" id="phone" placeholder="Phone number" required>
                                                    <input type="number" name="num_of_hrs" id="num_of_hrs" placeholder="Number of hours (optional)">
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea class="form-group" name="problem-description" id="problem-description" placeholder="Describe your problem here..." style="min-width: 100%;"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">Send</button>
                                        </div>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($_SESSION['role'] == 1 || isset($_GET['id'])) { ?>
            <div class="comments_section shadow">
                <h2>Reviews</h2>
                <?php
                $qry = oci_parse($db, "SELECT R.PRO_RECOMMENDATION, R.JOB_RATING, A.FNAME, A.LNAME, A.AID, S.CATEGORY
                                                       FROM REQUESTS R, WORK_OFFERS W, ACCOUNTS A, SERVICES S
                                                       WHERE R.WORK_OFFER = W.WID
                                                       AND W.PROFESSIONAL = {$aid}
                                                       AND W.SERVICE = S.SID
                                                       AND R.PRO_RECOMMENDATION IS NOT NULL
                                                       AND R.USER_ID = A.AID
                                                       ORDER BY R.JOB_RATING DESC");
                oci_execute($qry);
                $no_comment = true;
                while ($comment = oci_fetch_assoc($qry)) {
                    $no_comment = false; ?>
                    <div class="comment">
                        <?php if ($_SESSION['user_id'] == $comment['AID']) { ?>
                            <h3>You</h3>
                        <?php } else { ?>
                            <h2><?php echo $comment['FNAME'] . ' ' . $comment['LNAME']; ?></h2>
                        <?php } ?>
                        <h4>Service: <?php echo $comment['CATEGORY']; ?></h4>
                        <div>
                            <span>Rating: </span>
                            <?php $stars = $comment['JOB_RATING'];
                            $empty = 5 - $stars;
                            while ($stars >= 1) {
                                $stars--; ?>
                                <span><i class="fa fa-star" style="color: #FFDF00;"></i></span>
                            <?php }
                            while ($empty >= 1) {
                                $empty--; ?>
                                <span><i class="fa fa-star-o"></i></span>
                            <?php } ?>
                        </div>
                        <p><?php echo $comment['PRO_RECOMMENDATION']; ?></p>
                    </div>
                <?php } ?>
                <?php if ($no_comment) { ?>
                    <div class="no_comment comment">
                        <p>No comments available</p>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </main>

    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>