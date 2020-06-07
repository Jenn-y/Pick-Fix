<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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

    $query6 = oci_parse($db, "SELECT * FROM accounts WHERE role = 1 AND aid != '{$aid}'");
    oci_execute($query6);

    if (isset($_POST['city']) && isset($_POST['service'])){
        $list_specific = oci_parse($db, "SELECT p.lname, p.fname, w.* FROM work_offers w, accounts p WHERE w.service = {$_POST['service']} AND w.city = {$_POST['city']} AND w.professional = p.aid AND w.professional != '{$aid}'");
        oci_execute($list_specific);
    }
    if (!isset($_POST['city']) && isset($_POST['service'])){
        $list_by_service = oci_parse($db, "SELECT p.lname, p.fname, w.* FROM work_offers w, accounts p WHERE w.service = {$_POST['service']} AND w.professional = p.aid AND w.professional != '{$aid}'");
        oci_execute($list_by_service);
    }
    if (isset($_POST['city']) && !isset($_POST['service'])){
        $list_by_city = oci_parse($db, "SELECT p.lname, p.fname, w.professional  FROM work_offers w, accounts p WHERE w.city = {$_POST['city']} AND w.professional = p.aid GROUP BY p.lname, p.fname, w.professional AND w.professional != '{$aid}'");
        oci_execute($list_by_city);
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


    <title>Find Professionals</title>
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
                    <?php while($row2 = oci_fetch_assoc($query2)): ?>
                        <option value="<?= $row2['SID']; ?>"><?= $row2['CATEGORY']; ?></option>
                    <?php endwhile; ?>
                </select>

                <select name="city">
                    <option disabled selected value>&#128205;</option>
                    <?php while($row3 = oci_fetch_assoc($query3)): ?>
                        <option value="<?= $row3['CID']; ?>"><?= $row3['CNAME']; ?></option>
                    <?php endwhile; ?>
                </select>
                <button id="get-professionals">Get Started</button>

            </div>
        </form>
    </div>

    <main>
        <section class="flex-container popular-services">
            <h2>Choose a professional for your service <i class="fa fa-angle-double-down"
                                                          aria-hidden="true"></i></h2>

            <div class="allServices">
                <h1>All services</h1>

                <?php while($row4 = oci_fetch_assoc($query5)): ?>
                    <div class="dropdown">
                        <a class="dropLink"><?= $row4['CATEGORY']; ?> <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                        <div class="dropdown-content">
                            <p><?= $row4['CAT_DESCRIPTION']; ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="displayProfessionals">
                <?php if(isset($_POST['city']) && isset($_POST['service'])) { ?>
                    <?php while($row5 = oci_fetch_assoc($list_specific)): ?>
                        <a href="profile.php?id=<?= $row5['AID']?>">
                            <img src="images/default-user.png" alt="professional-profile">
                            <h4><?php echo $row5['FNAME'] . ' ' . $row5['LNAME'] ?></h4>
                            <h5>Charge per hour: 3.99BAM</h5>
                            <p>Rating: &#11088;&#11088;&#11088;</p>
                        </a>
                    <?php endwhile; ?>
                <?php } else if(!isset($_POST['city']) && isset($_POST['service'])) { ?>
                    <?php while($row6 = oci_fetch_assoc($list_by_service)): ?>
                        <a href="profile.php?id=<?= $row6['AID']?>">
                            <img src="images/default-user.png" alt="professional-profile">
                            <h4><?php echo $row6['FNAME'] . ' ' . $row6['LNAME'] ?></h4>
                            <h5>Charge per hour: 3.99BAM</h5>
                            <p>Rating: &#11088;&#11088;&#11088;</p>
                        </a>
                    <?php endwhile; ?>
                <?php } else if(isset($_POST['city']) && !isset($_POST['service'])) { ?>
                    <?php while($row7 = oci_fetch_assoc($list_by_city)): ?>
                        <a href="profile.php?id=<?= $row7['AID']?>">
                            <img src="images/default-user.png" alt="professional-profile">
                            <h4><?php echo $row7['FNAME'] . ' ' . $row7['LNAME'] ?></h4>
                            <h5>Charge per hour: 3.99BAM</h5>
                            <p>Rating: &#11088;&#11088;&#11088;</p>
                        </a>
                    <?php endwhile; ?>
                <?php } else { ?>
                    <?php while($row5 = oci_fetch_assoc($query6)): ?>
                        <a href="profile.php?id=<?= $row5['AID']?>">
                            <img src="images/default-user.png" alt="professional-profile">
                            <h4><?php echo $row5['FNAME'] . ' ' . $row5['LNAME'] ?></h4>
                            <h5>Charge per hour: 3.99BAM</h5>
                            <p>Rating: &#11088;&#11088;&#11088;</p>
                        </a>
                    <?php endwhile; ?>
                <?php } ?>
            </div>
        </section>
    </main>

    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>