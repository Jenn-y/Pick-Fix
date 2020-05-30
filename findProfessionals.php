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
    <?php include('includes/header-signed-in.php'); ?>

    <div class="welcome backImage">
        <div class="color-overlay"></div>
        <div class="flex-container">
            <select name="services-dropdown">
                <option disabled selected value>Select a service</option>
                <?php while($row2 = oci_fetch_assoc($query2)): ?>
                    <option value="<?= $row2['CATEGORY']; ?>"><?= $row2['CATEGORY']; ?></option>
                <?php endwhile; ?>
            </select>

            <select name="cities">
                <option disabled selected value>&#128205;</option>
                <?php while($row3 = oci_fetch_assoc($query3)): ?>
                    <option value="<?= $row3['CNAME']; ?>"><?= $row3['CNAME']; ?></option>
                <?php endwhile; ?>
            </select>
            <a href="#">Get Started</a>
        </div>
    </div>

    <main>
        <section class="flex-container popular-services">
            <h1>Choose a professional for your service <i class="fa fa-angle-double-down"
                                                          aria-hidden="true"></i></h1>

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
                <?php while($row5 = oci_fetch_assoc($query6)): ?>
                    <a href="pro-profile.php">
                        <img src="images/default-user.png" alt="professional-profile">
                        <h4><?php echo $row5['FNAME'] . ' ' . $row5['LNAME'] ?></h4>
                        <h6>Charge per hour: 3.99BAM</h6>
                        <p>Rating: &#11088;&#11088;&#11088;</p>
                    </a>
                <?php endwhile; ?>
            </div>
        </section>
    </main>

    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>