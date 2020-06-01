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
    <link href="css/pro-profile.css" rel="stylesheet">
    <link rel="stylesheet" href="css/footer.css">


    <title>Profile</title>
</head>
<body>

<div id="page-container">
    <?php include('includes/header.php'); ?>

    <main class="center">
        <div class="shadow">
            <div class="user flex-container">
                <img src="images/default-user.png" alt="default-user-image">
                <div>
                    <h3><?= $row['FNAME'] . ' ' . $row['LNAME'] ?></h3>
                    <p><?= $row['PRIMARY_CITY'] ?>, Bosnia and Herzegovina</p>
                </div>
            </div>
            <div class="about">
                <h4>About: </h4>
                <?php if($row['SHORT_BIOGRAPHY'] == null): ?>
                    <p><i>No profile description provided . . .</i></p>
                <?php else: ?>
                    <p><?= $row['SHORT_BIOGRAPHY'] ?></p>
                <?php endif; ?>

                <?php if ($row['ROLE'] == 1){ ?>
                    <h4>Areas Served: </h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi, est maiores modi officiis perspiciatis recusandae totam vel veritatis voluptatem voluptatibus?</p>
                    <h4>Categories </h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi, est maiores modi officiis perspiciatis recusandae totam vel veritatis voluptatem voluptatibus?</p>
                <?php } ?>
                <h4>Contact:</h4>
                <p><?= '+' . $row['AREA_CODE'] . ' ' . $row['PHONE_NUMBER'] ?></p>
            </div>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>