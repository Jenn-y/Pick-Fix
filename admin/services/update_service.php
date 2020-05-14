
<?php
include('../../includes/db.php');
$query1 = mysqli_query($db, "SELECT * FROM services WHERE status=1");
?>
<!doctype html>
<html lang="en">
<head>
    <?php include('../../includes/head.php') ?>
    <link rel="stylesheet" href="../admin.css">
    <title>Admin | Services</title>
</head>
<body>

<header>
    <div class="inner-header flex-container center">
        <h1><a href="#">Pick&Fix</a></h1>
        <a href="#">Log out</a>
    </div>
</header>

<main class="center">
    <h2>Admin page</h2>
    <div class="flex-container">
        <div class="tables flex-container">
            <a href="cities.php">Cities</a>
            <a href="services.php" id="stay">Services <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
            <a href="#">Work offers</a>
            <a href="#">Users</a>
        </div>
        <div class="rows">
            <?php include('edit_service.php'); ?>
        </div>
    </div>
</main>
</body>
</html>