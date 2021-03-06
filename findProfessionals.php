<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("includes/functions.php");
check_if_logged_in();
include_once("includes/db.php");

if (isset($_SESSION['user_id'])) {
    $aid = $_SESSION['user_id'];
    $q = "SELECT * FROM accounts WHERE aid='{$aid}'";
    $query = oci_parse($db, $q);
    oci_execute($query);

    $row = oci_fetch_assoc($query);


    $query2 = oci_parse($db, 'SELECT * FROM services WHERE date_deleted IS NULL ORDER BY category');
    oci_execute($query2);
    $query5 = oci_parse($db, 'SELECT * FROM services WHERE date_deleted IS NULL ORDER BY category');
    oci_execute($query5);
    $query3 = oci_parse($db, 'SELECT * FROM cities WHERE date_deleted IS NULL ORDER BY cname');
    oci_execute($query3);

    $query6_1 = oci_parse($db, "SELECT * FROM accounts WHERE role = 1 AND aid != '{$aid}'");
    oci_execute($query6_1);
    $query6_2 = oci_parse($db, "SELECT * FROM accounts WHERE role = 1 AND aid != '{$aid}'");
    oci_execute($query6_2);

    if (isset($_POST['city']) && isset($_POST['service'])) {
        $list_specific_1 = oci_parse($db, "SELECT p.aid, p.lname, p.fname, w.*, s.category, c.cname, P.IMG_TYPE
                                                FROM work_offers w, accounts p, services s, cities c
                                                WHERE w.service = {$_POST['service']} 
                                                AND w.city = {$_POST['city']} 
                                                AND w.professional = p.aid
                                                AND w.service = s.sid
                                                AND w.city = c.cid
                                                AND w.professional != '{$aid}'");
        oci_execute($list_specific_1);
        $list_specific_2 = oci_parse($db, "SELECT p.aid, p.lname, p.fname, w.*, s.category, c.cname, P.IMG_TYPE
                                                FROM work_offers w, accounts p, services s, cities c
                                                WHERE w.service = {$_POST['service']} 
                                                AND w.city = {$_POST['city']} 
                                                AND w.professional = p.aid
                                                AND w.service = s.sid
                                                AND w.city = c.cid
                                                AND w.professional != '{$aid}'");
        oci_execute($list_specific_2);
    }
    if (!isset($_POST['city']) && isset($_POST['service'])) {
        $list_by_service_1 = oci_parse($db, "SELECT DISTINCT W.PROFESSIONAL, P.FNAME, P.LNAME, P.AID, s.category, P.IMG_TYPE
                                                  FROM work_offers w, accounts p, services s 
                                                  WHERE w.service = {$_POST['service']} 
                                                  AND w.professional = p.aid 
                                                  AND w.professional != '{$aid}' 
                                                  and w.service = s.sid");
        oci_execute($list_by_service_1);
        $list_by_service_2 = oci_parse($db, "SELECT DISTINCT W.PROFESSIONAL, P.FNAME, P.LNAME, P.AID, s.category, P.IMG_TYPE
                                                  FROM work_offers w, accounts p, services s 
                                                  WHERE w.service = {$_POST['service']} 
                                                  AND w.professional = p.aid 
                                                  AND w.professional != '{$aid}' 
                                                  and w.service = s.sid");
        oci_execute($list_by_service_2);
    }
    if (isset($_POST['city']) && !isset($_POST['service'])) {
        $list_by_city_1 = oci_parse($db, "SELECT DISTINCT W.PROFESSIONAL, P.FNAME, P.LNAME, P.AID, C.CNAME, P.IMG_TYPE
                                                FROM work_offers w, accounts p, cities c
                                                WHERE w.city = {$_POST['city']}
                                                and w.city = c.cid
                                                AND w.professional = p.aid
                                                AND w.professional != '{$aid}'");
        oci_execute($list_by_city_1);
        $list_by_city_2 = oci_parse($db, "SELECT DISTINCT W.PROFESSIONAL, P.FNAME, P.LNAME, P.AID, C.CNAME, P.IMG_TYPE
                                                FROM work_offers w, accounts p, cities c
                                                WHERE w.city = {$_POST['city']}
                                                and w.city = c.cid
                                                AND w.professional = p.aid
                                                AND w.professional != '{$aid}'");
        oci_execute($list_by_city_2);
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/footer.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <title>Find Professionals | Pick & Fix</title>

    <style>
        .gold-img {
            border-radius: 10px;
        }
        .gold {
            border: 3px solid #FFD700;
            border-radius: 10px;
            position: relative;
            max-width: calc(25% - 1rem - 6px);
        }
        .trust {
            position: absolute;
            font-size: 14px;
            left: 0;
            padding: 0.25rem;
            background: #343a40;
            color: #FFD700;
            border-radius: 5px;
        }

        @media (max-width: 600px): {
            .gold {
                max-width: calc(50% - 0.5rem - 6px);
            }
        }
    </style>
</head>
<body id="findProfessionals">
<div class="page-container">
    <?php include('includes/header.php'); ?>

    <div class="welcome backImage">
        <div class="color-overlay"></div>
        <form method="post">
            <div class="flex-container">

                <select name="service">
                    <option disabled selected value>Select a service</option>
                    <?php while ($row2 = oci_fetch_assoc($query2)): ?>
                        <option value="<?= $row2['SID']; ?>"><?= $row2['CATEGORY']; ?></option>
                    <?php endwhile; ?>
                </select>

                <select name="city">
                    <option disabled selected value>&#128205;</option>
                    <?php while ($row3 = oci_fetch_assoc($query3)): ?>
                        <option value="<?= $row3['CID']; ?>"><?= $row3['CNAME']; ?></option>
                    <?php endwhile; ?>
                </select>
                <button id="get-professionals">Get Started</button>

            </div>
        </form>
    </div>

    <main>
        <section class="flex-container popular-services">
            <h2>Choose a professional for your service <i class="fa fa-angle-double-down" aria-hidden="true"></i></h2>
            <div class="allServices">
                <h1>All services</h1>

                <?php while ($row4 = oci_fetch_assoc($query5)): ?>
                    <div class="dropdown">
                        <a class="drop_link"><?= $row4['CATEGORY']; ?> <i class="fa fa-angle-down"
                                                                          aria-hidden="true"></i></a>
                        <div class="dropdown_content" style="display: none;">
                            <p><?= $row4['CAT_DESCRIPTION']; ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="displayProfessionals">
                <?php if (isset($_POST['city']) && isset($_POST['service'])) {
                    $num = 0; ?>
                    <?php while ($row5 = oci_fetch_assoc($list_specific_1)):
                        $completed_jobs = oci_parse($db, "SELECT COUNT (*) AS JOBS
                        FROM REQUESTS R, WORK_OFFERS W, REQUESTS_HISTORY H, CITIES C
                        WHERE R.WORK_OFFER = W.WID
                        AND H.REQUEST = R.RID
                        AND H.STATUS = 1
                        AND W.SERVICE = {$_POST['service']}
                        AND W.CITY = {$_POST['city']}
                        AND W.PROFESSIONAL = {$row5['AID']}");
                        oci_execute($completed_jobs);
                        $jobs = oci_fetch_assoc($completed_jobs);
                        $exp = oci_parse($db, "select MAX(F.PAYMENT_EXPIRATION)
                                          from ACCOUNTS A, FEE_PAYMENTS F
                                          where F.PROFESSIONAL = A.AID
                                          and A.AID = {$row5['AID']}");
                        oci_execute($exp);
                        $expiration_date = oci_fetch_assoc($exp);

                        $membership_q = oci_parse($db, "select payment_plan from fee_payments
                                                  join (select max(fid) latest_fee_payment
                                                        from ACCOUNTS A, FEE_PAYMENTS F
                                                        where F.PROFESSIONAL = A.AID
                                                        and A.AID = {$row5['AID']})
                                                        on latest_fee_payment = fid");
                        oci_execute($membership_q);
                        $membership = oci_fetch_assoc($membership_q);

                        if (strtotime(date("Y/m/d")) <= strtotime($expiration_date["MAX(F.PAYMENT_EXPIRATION)"]) && $membership['PAYMENT_PLAN'] != 4) {
                            $num++; ?>
                            <a class="gold" href="profile.php?id=<?= $row5['AID'] ?>">

                                <img class="gold-img" src="<?= fetch_profile_image($row5['AID'], $row5['IMG_TYPE']); ?>"
                                     alt="professional-profile">
                                <span class="trust">Pick&Fix Trust <i class="fa fa-check-square-o" aria-hidden="true"></i></span>
                                <h3><?php echo $row5['FNAME'] . ' ' . $row5['LNAME']; ?></h3>
                                <h4>Service: <?php echo $row5['CATEGORY']; ?></h4>
                                <h4>City: <?php echo $row5['CNAME']; ?></h4>
                                <h4>Charge per hour: <?php echo $row5['CHARGE_PER_HOUR']; ?> BAM</h4>
                                <h4>Jobs completed: <?php echo $jobs['JOBS']; ?></h4>
                                <?php $sql = oci_parse($db, "SELECT R.JOB_RATING
                                                       FROM REQUESTS R, WORK_OFFERS W
                                                       WHERE R.WORK_OFFER = W.WID
                                                       AND W.SERVICE = {$_POST['service']}
                                                       AND W.CITY = {$_POST['city']}
                                                       AND W.PROFESSIONAL = {$row5['AID']}
                                                       AND R.JOB_RATING IS NOT NULL");
                                oci_execute($sql);

                                $sum_rates = 0;
                                $num_of_rates = 0;

                                while ($request_row = oci_fetch_assoc($sql)) {
                                    $sum_rates = $sum_rates + $request_row['JOB_RATING'];
                                    $num_of_rates++;
                                }
                                $rating = 1.0;
                                if ($num_of_rates != 0) {
                                    $rating = $sum_rates / $num_of_rates;
                                }
                                $empty_stars = 5; ?>
                                <div class="rating_pro">
                                    <span>Rating: </span>
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
                                        <span><i class="fa fa-star-o"></i></span>
                                    <?php endwhile; ?>
                                </div>
                            </a>
                        <?php } ?>
                    <?php endwhile; ?>
                    <?php while ($row5 = oci_fetch_assoc($list_specific_2)):
                        $completed_jobs = oci_parse($db, "SELECT COUNT (*) AS JOBS
                        FROM REQUESTS R, WORK_OFFERS W, REQUESTS_HISTORY H, CITIES C
                        WHERE R.WORK_OFFER = W.WID
                        AND H.REQUEST = R.RID
                        AND H.STATUS = 1
                        AND W.SERVICE = {$_POST['service']}
                        AND W.CITY = {$_POST['city']}
                        AND W.PROFESSIONAL = {$row5['AID']}");
                        oci_execute($completed_jobs);
                        $jobs = oci_fetch_assoc($completed_jobs);
                        $exp = oci_parse($db, "select MAX(F.PAYMENT_EXPIRATION)
                                          from ACCOUNTS A, FEE_PAYMENTS F
                                          where F.PROFESSIONAL = A.AID
                                          and A.AID = {$row5['AID']}");
                        oci_execute($exp);
                        $expiration_date = oci_fetch_assoc($exp);

                        $membership_q = oci_parse($db, "select payment_plan from fee_payments
                                                  join (select max(fid) latest_fee_payment
                                                        from ACCOUNTS A, FEE_PAYMENTS F
                                                        where F.PROFESSIONAL = A.AID
                                                        and A.AID = {$row5['AID']})
                                                        on latest_fee_payment = fid");
                        oci_execute($membership_q);
                        $membership = oci_fetch_assoc($membership_q);

                        if (strtotime(date("Y/m/d")) > strtotime($expiration_date["MAX(F.PAYMENT_EXPIRATION)"]) || $membership['PAYMENT_PLAN'] == 4) {
                            $num++; ?>
                            <a href="profile.php?id=<?= $row5['AID'] ?>">

                                <img src="<?= fetch_profile_image($row5['AID'], $row5['IMG_TYPE']); ?>"
                                     alt="professional-profile">
                                <h3><?php echo $row5['FNAME'] . ' ' . $row5['LNAME']; ?></h3>
                                <h4>Service: <?php echo $row5['CATEGORY']; ?></h4>
                                <h4>City: <?php echo $row5['CNAME']; ?></h4>
                                <h4>Charge per hour: <?php echo $row5['CHARGE_PER_HOUR']; ?> BAM</h4>
                                <h4>Jobs completed: <?php echo $jobs['JOBS']; ?></h4>
                                <?php $sql = oci_parse($db, "SELECT R.JOB_RATING
                                                       FROM REQUESTS R, WORK_OFFERS W
                                                       WHERE R.WORK_OFFER = W.WID
                                                       AND W.SERVICE = {$_POST['service']}
                                                       AND W.CITY = {$_POST['city']}
                                                       AND W.PROFESSIONAL = {$row5['AID']}
                                                       AND R.JOB_RATING IS NOT NULL");
                                oci_execute($sql);

                                $sum_rates = 0;
                                $num_of_rates = 0;

                                while ($request_row = oci_fetch_assoc($sql)) {
                                    $sum_rates = $sum_rates + $request_row['JOB_RATING'];
                                    $num_of_rates++;
                                }
                                $rating = 1.0;
                                if ($num_of_rates != 0) {
                                    $rating = $sum_rates / $num_of_rates;
                                }
                                $empty_stars = 5; ?>
                                <div class="rating_pro">
                                    <span>Rating: </span>
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
                                        <span><i class="fa fa-star-o"></i></span>
                                    <?php endwhile; ?>
                                </div>
                            </a>
                        <?php } ?>
                    <?php endwhile; ?>
                    <?php if ($num == 0) { ?>
                        <h4 class="display_message">No professional available</h4>
                    <?php } else if ($num == 1) { ?>
                        <h4 class="display_available"><?php echo $num; ?> professional available</h4>
                    <?php } else { ?>
                        <h4 class="display_available"><?php echo $num; ?> professionals available</h4>
                    <?php } ?>
                <?php } else if (!isset($_POST['city']) && isset($_POST['service'])) {
                    $num = 0; ?>
                    <?php while ($row6 = oci_fetch_assoc($list_by_service_1)):
                        $completed_jobs = oci_parse($db, "SELECT COUNT (*) AS JOBS
                                                       FROM REQUESTS R, WORK_OFFERS W, REQUESTS_HISTORY H
                                                       WHERE R.WORK_OFFER = W.WID
                                                       AND H.REQUEST = R.RID
                                                       AND H.STATUS = 1
                                                       AND W.SERVICE = {$_POST['service']}
                                                       AND W.PROFESSIONAL = {$row6['AID']}");
                        oci_execute($completed_jobs);
                        $jobs = oci_fetch_assoc($completed_jobs);
                        $exp = oci_parse($db, "select MAX(F.PAYMENT_EXPIRATION)
                                          from ACCOUNTS A, FEE_PAYMENTS F
                                          where F.PROFESSIONAL = A.AID
                                          and A.AID = {$row6['AID']}");
                        oci_execute($exp);
                        $expiration_date = oci_fetch_assoc($exp);

                        $membership_q = oci_parse($db, "select payment_plan from fee_payments
                                                  join (select max(fid) latest_fee_payment
                                                        from ACCOUNTS A, FEE_PAYMENTS F
                                                        where F.PROFESSIONAL = A.AID
                                                        and A.AID = {$row6['AID']})
                                                        on latest_fee_payment = fid");
                        oci_execute($membership_q);
                        $membership = oci_fetch_assoc($membership_q);

                        if (strtotime(date("Y/m/d")) <= strtotime($expiration_date["MAX(F.PAYMENT_EXPIRATION)"]) && $membership['PAYMENT_PLAN'] != 4) {
                            $num++; ?>
                            <a class="gold" href="profile.php?id=<?= $row6['AID'] ?>">
                                <img class="gold-img" src="<?= fetch_profile_image($row6['AID'], $row6['IMG_TYPE']); ?>"
                                     alt="professional-profile">
                                <span class="trust">Pick&Fix Trust <i class="fa fa-check-square-o" aria-hidden="true"></i></span>
                                <h3><?php echo $row6['FNAME'] . ' ' . $row6['LNAME']; ?></h3>
                                <h4>Service: <?php echo $row6['CATEGORY']; ?></h4>
                                <h4>Jobs completed: <?php echo $jobs['JOBS']; ?></h4>
                                <?php $sql = oci_parse($db, "SELECT R.JOB_RATING
                                                       FROM REQUESTS R, WORK_OFFERS W
                                                       WHERE R.WORK_OFFER = W.WID
                                                       AND W.SERVICE = {$_POST['service']}
                                                       AND W.PROFESSIONAL = {$row6['AID']}
                                                       AND R.JOB_RATING IS NOT NULL");
                                oci_execute($sql);

                                $sum_rates = 0;
                                $num_of_rates = 0;

                                while ($request_row = oci_fetch_assoc($sql)) {
                                    $sum_rates = $sum_rates + $request_row['JOB_RATING'];
                                    $num_of_rates++;
                                }
                                $rating = 1.0;
                                if ($num_of_rates != 0) {
                                    $rating = $sum_rates / $num_of_rates;
                                }
                                $empty_stars = 5; ?>
                                <div class="rating_pro">
                                    <span>Rating: </span>
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
                                        <span><i class="fa fa-star-o"></i></span>
                                    <?php endwhile; ?>
                                </div>
                            </a>
                        <?php } ?>
                    <?php endwhile; ?>
                    <?php while ($row6 = oci_fetch_assoc($list_by_service_2)):
                        $completed_jobs = oci_parse($db, "SELECT COUNT (*) AS JOBS
                                                       FROM REQUESTS R, WORK_OFFERS W, REQUESTS_HISTORY H
                                                       WHERE R.WORK_OFFER = W.WID
                                                       AND H.REQUEST = R.RID
                                                       AND H.STATUS = 1
                                                       AND W.SERVICE = {$_POST['service']}
                                                       AND W.PROFESSIONAL = {$row6['AID']}");
                        oci_execute($completed_jobs);
                        $jobs = oci_fetch_assoc($completed_jobs);
                        $exp = oci_parse($db, "select MAX(F.PAYMENT_EXPIRATION)
                                          from ACCOUNTS A, FEE_PAYMENTS F
                                          where F.PROFESSIONAL = A.AID
                                          and A.AID = {$row6['AID']}");
                        oci_execute($exp);
                        $expiration_date = oci_fetch_assoc($exp);

                        $membership_q = oci_parse($db, "select payment_plan from fee_payments
                                                  join (select max(fid) latest_fee_payment
                                                        from ACCOUNTS A, FEE_PAYMENTS F
                                                        where F.PROFESSIONAL = A.AID
                                                        and A.AID = {$row6['AID']})
                                                        on latest_fee_payment = fid");
                        oci_execute($membership_q);
                        $membership = oci_fetch_assoc($membership_q);

                        if (strtotime(date("Y/m/d")) > strtotime($expiration_date["MAX(F.PAYMENT_EXPIRATION)"]) || $membership['PAYMENT_PLAN'] == 4) {
                            $num++; ?>
                            <a href="profile.php?id=<?= $row6['AID'] ?>">
                                <img src="<?= fetch_profile_image($row6['AID'], $row6['IMG_TYPE']); ?>"
                                     alt="professional-profile">
                                <h3><?php echo $row6['FNAME'] . ' ' . $row6['LNAME']; ?></h3>
                                <h4>Service: <?php echo $row6['CATEGORY']; ?></h4>
                                <h4>Jobs completed: <?php echo $jobs['JOBS']; ?></h4>
                                <?php $sql = oci_parse($db, "SELECT R.JOB_RATING
                                                       FROM REQUESTS R, WORK_OFFERS W
                                                       WHERE R.WORK_OFFER = W.WID
                                                       AND W.SERVICE = {$_POST['service']}
                                                       AND W.PROFESSIONAL = {$row6['AID']}
                                                       AND R.JOB_RATING IS NOT NULL");
                                oci_execute($sql);

                                $sum_rates = 0;
                                $num_of_rates = 0;

                                while ($request_row = oci_fetch_assoc($sql)) {
                                    $sum_rates = $sum_rates + $request_row['JOB_RATING'];
                                    $num_of_rates++;
                                }
                                $rating = 1.0;
                                if ($num_of_rates != 0) {
                                    $rating = $sum_rates / $num_of_rates;
                                }
                                $empty_stars = 5; ?>
                                <div class="rating_pro">
                                    <span>Rating: </span>
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
                                        <span><i class="fa fa-star-o"></i></span>
                                    <?php endwhile; ?>
                                </div>
                            </a>
                        <?php } ?>
                    <?php endwhile; ?>
                    <?php if ($num == 0) { ?>
                        <h4 class="display_message">No professional available</h4>
                    <?php } else if ($num == 1) { ?>
                        <h4 class="display_available"><?php echo $num; ?> professional available</h4>
                    <?php } else { ?>
                        <h4 class="display_available"><?php echo $num; ?> professionals available</h4>
                    <?php } ?>
                <?php } else if (isset($_POST['city']) && !isset($_POST['service'])) {
                    $num = 0; ?>
                    <?php while ($row7 = oci_fetch_assoc($list_by_city_1)):
                        $completed_jobs = oci_parse($db, "SELECT COUNT (*) AS JOBS
                        FROM REQUESTS R, WORK_OFFERS W, REQUESTS_HISTORY H
                        WHERE R.WORK_OFFER = W.WID
                        AND H.REQUEST = R.RID
                        AND H.STATUS = 1
                        AND W.CITY = {$_POST['city']}
                        AND W.PROFESSIONAL = {$row7['AID']}");
                        oci_execute($completed_jobs);
                        $jobs = oci_fetch_assoc($completed_jobs);
                        $exp = oci_parse($db, "select MAX(F.PAYMENT_EXPIRATION)
                                          from ACCOUNTS A, FEE_PAYMENTS F
                                          where F.PROFESSIONAL = A.AID
                                          and A.AID = {$row7['AID']}");
                        oci_execute($exp);
                        $expiration_date = oci_fetch_assoc($exp);

                        $membership_q = oci_parse($db, "select payment_plan from fee_payments
                                                  join (select max(fid) latest_fee_payment
                                                        from ACCOUNTS A, FEE_PAYMENTS F
                                                        where F.PROFESSIONAL = A.AID
                                                        and A.AID = {$row7['AID']})
                                                        on latest_fee_payment = fid");
                        oci_execute($membership_q);
                        $membership = oci_fetch_assoc($membership_q);

                        if (strtotime(date("Y/m/d")) <= strtotime($expiration_date["MAX(F.PAYMENT_EXPIRATION)"]) && $membership['PAYMENT_PLAN'] != 4) {
                            $num++; ?>
                            <a class="gold" href="profile.php?id=<?= $row7['AID'] ?>">
                                <img class="gold-img" src="<?= fetch_profile_image($row7['AID'], $row7['IMG_TYPE']); ?>"
                                     alt="professional-profile">
                                <span class="trust">Pick&Fix Trust <i class="fa fa-check-square-o" aria-hidden="true"></i></span>
                                <h3><?php echo $row7['FNAME'] . ' ' . $row7['LNAME']; ?></h3>
                                <h4>City: <?php echo $row7['CNAME']; ?></h4>
                                <h4>Jobs completed: <?php echo $jobs['JOBS']; ?></h4>
                                <?php $sql = oci_parse($db, "SELECT R.JOB_RATING
                                                       FROM REQUESTS R, WORK_OFFERS W
                                                       WHERE R.WORK_OFFER = W.WID
                                                       AND W.CITY = {$_POST['city']}
                                                       AND W.PROFESSIONAL = {$row7['AID']}
                                                       AND R.JOB_RATING IS NOT NULL");
                                oci_execute($sql);

                                $sum_rates = 0;
                                $num_of_rates = 0;

                                while ($request_row = oci_fetch_assoc($sql)) {
                                    $sum_rates = $sum_rates + $request_row['JOB_RATING'];
                                    $num_of_rates++;
                                }
                                $rating = 1.0;
                                if ($num_of_rates != 0) {
                                    $rating = $sum_rates / $num_of_rates;
                                }
                                $empty_stars = 5; ?>
                                <div class="rating_pro">
                                    <span>Rating: </span>
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
                                        <span><i class="fa fa-star-o"></i></span>
                                    <?php endwhile; ?>
                                </div>
                            </a>
                        <?php } ?>
                    <?php endwhile; ?>
                    <?php while ($row7 = oci_fetch_assoc($list_by_city_2)):
                        $completed_jobs = oci_parse($db, "SELECT COUNT (*) AS JOBS
                        FROM REQUESTS R, WORK_OFFERS W, REQUESTS_HISTORY H
                        WHERE R.WORK_OFFER = W.WID
                        AND H.REQUEST = R.RID
                        AND H.STATUS = 1
                        AND W.CITY = {$_POST['city']}
                        AND W.PROFESSIONAL = {$row7['AID']}");
                        oci_execute($completed_jobs);
                        $jobs = oci_fetch_assoc($completed_jobs);
                        $exp = oci_parse($db, "select MAX(F.PAYMENT_EXPIRATION)
                                          from ACCOUNTS A, FEE_PAYMENTS F
                                          where F.PROFESSIONAL = A.AID
                                          and A.AID = {$row7['AID']}");
                        oci_execute($exp);
                        $expiration_date = oci_fetch_assoc($exp);

                        $membership_q = oci_parse($db, "select payment_plan from fee_payments
                                                  join (select max(fid) latest_fee_payment
                                                        from ACCOUNTS A, FEE_PAYMENTS F
                                                        where F.PROFESSIONAL = A.AID
                                                        and A.AID = {$row7['AID']})
                                                        on latest_fee_payment = fid");
                        oci_execute($membership_q);
                        $membership = oci_fetch_assoc($membership_q);

                        if (strtotime(date("Y/m/d")) > strtotime($expiration_date["MAX(F.PAYMENT_EXPIRATION)"]) || $membership['PAYMENT_PLAN'] == 4) {
                            $num++; ?>
                            <a href="profile.php?id=<?= $row7['AID'] ?>">
                                <img src="<?= fetch_profile_image($row7['AID'], $row7['IMG_TYPE']); ?>"
                                     alt="professional-profile">
                                <h3><?php echo $row7['FNAME'] . ' ' . $row7['LNAME']; ?></h3>
                                <h4>City: <?php echo $row7['CNAME']; ?></h4>
                                <h4>Jobs completed: <?php echo $jobs['JOBS']; ?></h4>
                                <?php $sql = oci_parse($db, "SELECT R.JOB_RATING
                                                       FROM REQUESTS R, WORK_OFFERS W
                                                       WHERE R.WORK_OFFER = W.WID
                                                       AND W.CITY = {$_POST['city']}
                                                       AND W.PROFESSIONAL = {$row7['AID']}
                                                       AND R.JOB_RATING IS NOT NULL");
                                oci_execute($sql);

                                $sum_rates = 0;
                                $num_of_rates = 0;

                                while ($request_row = oci_fetch_assoc($sql)) {
                                    $sum_rates = $sum_rates + $request_row['JOB_RATING'];
                                    $num_of_rates++;
                                }
                                $rating = 1.0;
                                if ($num_of_rates != 0) {
                                    $rating = $sum_rates / $num_of_rates;
                                }
                                $empty_stars = 5; ?>
                                <div class="rating_pro">
                                    <span>Rating: </span>
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
                                        <span><i class="fa fa-star-o"></i></span>
                                    <?php endwhile; ?>
                                </div>
                            </a>
                        <?php } ?>
                    <?php endwhile; ?>
                    <?php if ($num == 0) { ?>
                        <h4 class="display_message">No professional available</h4>
                    <?php } else if ($num == 1) { ?>
                        <h4 class="display_available"><?php echo $num; ?> professional available</h4>
                    <?php } else { ?>
                        <h4 class="display_available"><?php echo $num; ?> professionals available</h4>
                    <?php } ?>
                <?php } else {
                    $num = 0; ?>
                    <?php while ($row5 = oci_fetch_assoc($query6_1)):
                        $completed_jobs = oci_parse($db, "SELECT COUNT (*) AS JOBS
                                                       FROM REQUESTS R, WORK_OFFERS W, REQUESTS_HISTORY H
                                                       WHERE R.WORK_OFFER = W.WID
                                                       AND H.REQUEST = R.RID
                                                       AND H.STATUS = 1
                                                       AND W.PROFESSIONAL = {$row5['AID']}");
                        oci_execute($completed_jobs);
                        $jobs = oci_fetch_assoc($completed_jobs);
                        $exp = oci_parse($db, "select MAX(F.PAYMENT_EXPIRATION)
                                          from ACCOUNTS A, FEE_PAYMENTS F
                                          where F.PROFESSIONAL = A.AID
                                          and A.AID = {$row5['AID']}");
                        oci_execute($exp);
                        $expiration_date = oci_fetch_assoc($exp);

                        $membership_q = oci_parse($db, "select payment_plan from fee_payments
                                                  join (select max(fid) latest_fee_payment
                                                        from ACCOUNTS A, FEE_PAYMENTS F
                                                        where F.PROFESSIONAL = A.AID
                                                        and A.AID = {$row5['AID']})
                                                        on latest_fee_payment = fid");
                        oci_execute($membership_q);
                        $membership = oci_fetch_assoc($membership_q);

                        if (strtotime(date("Y/m/d")) <= strtotime($expiration_date["MAX(F.PAYMENT_EXPIRATION)"]) && $membership['PAYMENT_PLAN'] != 4) {
                            $num++; ?>
                            <a class="gold" href="profile.php?id=<?= $row5['AID'] ?>">
                                <img class="gold-img" src="<?= fetch_profile_image($row5['AID'], $row5['IMG_TYPE']); ?>"
                                     alt="professional-profile">
                                <span class="trust">Pick&Fix Trust <i class="fa fa-check-square-o" aria-hidden="true"></i></span>
                                <h3><?php echo $row5['FNAME'] . ' ' . $row5['LNAME']; ?></h3>
                                <h4>Jobs completed: <?php echo $jobs['JOBS']; ?></h4>
                                <?php $sql = oci_parse($db, "SELECT R.JOB_RATING
                                                       FROM REQUESTS R, WORK_OFFERS W
                                                       WHERE R.WORK_OFFER = W.WID
                                                       AND W.PROFESSIONAL = {$row5['AID']}
                                                       AND R.JOB_RATING IS NOT NULL");
                                oci_execute($sql);

                                $sum_rates = 0;
                                $num_of_rates = 0;

                                while ($request_row = oci_fetch_assoc($sql)) {
                                    $sum_rates = $sum_rates + $request_row['JOB_RATING'];
                                    $num_of_rates++;
                                }
                                $rating = 1.0;
                                if ($num_of_rates != 0) {
                                    $rating = $sum_rates / $num_of_rates;
                                }
                                $empty_stars = 5; ?>
                                <div class="rating_pro">
                                    <span>Rating: </span>
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
                                        <span><i class="fa fa-star-o"></i></span>
                                    <?php endwhile; ?>
                                </div>
                            </a>
                        <?php } ?>
                    <?php endwhile; ?>
                    <?php while ($row5 = oci_fetch_assoc($query6_2)):
                        $completed_jobs = oci_parse($db, "SELECT COUNT (*) AS JOBS
                                                       FROM REQUESTS R, WORK_OFFERS W, REQUESTS_HISTORY H
                                                       WHERE R.WORK_OFFER = W.WID
                                                       AND H.REQUEST = R.RID
                                                       AND H.STATUS = 1
                                                       AND W.PROFESSIONAL = {$row5['AID']}");
                        oci_execute($completed_jobs);
                        $jobs = oci_fetch_assoc($completed_jobs);
                        $exp = oci_parse($db, "select MAX(F.PAYMENT_EXPIRATION)
                                          from ACCOUNTS A, FEE_PAYMENTS F
                                          where F.PROFESSIONAL = A.AID
                                          and A.AID = {$row5['AID']}");
                        oci_execute($exp);
                        $expiration_date = oci_fetch_assoc($exp);

                        $membership_q = oci_parse($db, "select payment_plan from fee_payments
                                                  join (select max(fid) latest_fee_payment
                                                        from ACCOUNTS A, FEE_PAYMENTS F
                                                        where F.PROFESSIONAL = A.AID
                                                        and A.AID = {$row5['AID']})
                                                        on latest_fee_payment = fid");
                        oci_execute($membership_q);
                        $membership = oci_fetch_assoc($membership_q);

                        if (strtotime(date("Y/m/d")) > strtotime($expiration_date["MAX(F.PAYMENT_EXPIRATION)"]) || $membership['PAYMENT_PLAN'] == 4) {
                            $num++; ?>
                            <a href="profile.php?id=<?= $row5['AID'] ?>">
                                <img src="<?= fetch_profile_image($row5['AID'], $row5['IMG_TYPE']); ?>"
                                     alt="professional-profile">
                                <h3><?php echo $row5['FNAME'] . ' ' . $row5['LNAME']; ?></h3>
                                <h4>Jobs completed: <?php echo $jobs['JOBS']; ?></h4>
                                <?php $sql = oci_parse($db, "SELECT R.JOB_RATING
                                                       FROM REQUESTS R, WORK_OFFERS W
                                                       WHERE R.WORK_OFFER = W.WID
                                                       AND W.PROFESSIONAL = {$row5['AID']}
                                                       AND R.JOB_RATING IS NOT NULL");
                                oci_execute($sql);

                                $sum_rates = 0;
                                $num_of_rates = 0;

                                while ($request_row = oci_fetch_assoc($sql)) {
                                    $sum_rates = $sum_rates + $request_row['JOB_RATING'];
                                    $num_of_rates++;
                                }
                                $rating = 1.0;
                                if ($num_of_rates != 0) {
                                    $rating = $sum_rates / $num_of_rates;
                                }
                                $empty_stars = 5; ?>
                                <div class="rating_pro">
                                    <span>Rating: </span>
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
                                        <span><i class="fa fa-star-o"></i></span>
                                    <?php endwhile; ?>
                                </div>
                            </a>
                        <?php } ?>
                    <?php endwhile; ?>
                    <?php if ($num == 0) { ?>
                        <h4 class="display_message">No professional available</h4>
                    <?php } else if ($num == 1) { ?>
                        <h4 class="display_available"><?php echo $num; ?> professional available</h4>
                    <?php } else { ?>
                        <h4 class="display_available"><?php echo $num; ?> professionals available</h4>
                    <?php } ?>
                <?php } ?>
            </div>
        </section>
    </main>

    <script>
        $(document).ready(function () {
            let counter1 = 1;
            $('.drop_link').click(function () {
                if (counter1 % 2 == 0) {
                    $(this).siblings().hide();
                    counter1++;
                } else {
                    $(this).siblings().show();
                    counter1++;
                }
            })
        });
    </script>

    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>