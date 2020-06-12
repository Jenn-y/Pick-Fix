<?php
include('../../includes/db.php');
$query1 = oci_parse($db, 'SELECT * FROM cities WHERE date_deleted IS NULL');
oci_execute($query1);
$query2 = oci_parse($db, 'SELECT * FROM cities WHERE date_deleted IS NOT NULL');
oci_execute($query2);
?>
<!doctype html>
<html lang="en">
<head>
    <?php include('../../includes/head.php') ?>
    <link rel="stylesheet" href="../admin.css">
    <link rel="icon" href="../../images/hammer.png">
    <title>Admin | Cities</title>
</head>
<body>

<header>
    <div class="inner-header flex-container center">
        <h1><a href="../admin.php">Pick&Fix</a></h1>
        <a href="../../includes/logout.php">Log out</a>
    </div>
</header>

<main class="center">
    <h2>Admin page</h2>
    <div class="flex-container">
        <div class="tables flex-container">
            <a href="cities.php" id="stay">Cities <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
            <a href="../services/services.php">Services</a>
            <a href="../work_offers/work_offers.php">Work offers</a>
            <a href="../users/users.php">Users</a>
            <a href="../payments/payments.php">Payments</a>
        </div>
        <div class="rows">
            <table>
                <tr>
                    <th>CID</th>
                    <th>Name</th>
                </tr>
                <?php while($row = oci_fetch_assoc($query1)): ?>
                    <tr>
                        <td><?= $row['CID']; ?></td>
                        <td><?= $row['CNAME']; ?></td>
                        <td><a href="delete_city.php?id=<?=$row['CID']; ?>" onclick="return confirm('Are you sure that you want to delete city <?=$row['CNAME']; ?>?')">delete</a></td>
                        <td><a href="update_cities.php?id=<?=$row['CID']; ?>">edit</a></td>
                    </tr>
                <?php endwhile; ?>

            </table>
            <?php include('insert_city.php'); ?>
        </div>
        <div class="rows">
            <table>
                <tr>
                    <th>CID</th>
                    <th>Name</th>
                </tr>
                <?php while($row = oci_fetch_assoc($query2)): ?>
                    <tr>
                        <td><?= $row['CID']; ?></td>
                        <td><?= $row['CNAME']; ?></td>
                        <td><a href="update_deleted_city.php?id=<?=$row['CID']; ?>">re-add</a></td>
                    </tr>
                <?php endwhile; ?>

            </table>
        </div>
    </div>
</main>
</body>
</html>