<?php
include('../../includes/db.php');
$query1 = oci_parse($db, 'SELECT * FROM cities WHERE date_deleted IS NULL ORDER BY CNAME');
oci_execute($query1);
$query2 = oci_parse($db, 'SELECT * FROM cities WHERE date_deleted IS NOT NULL ORDER BY CNAME');
oci_execute($query2);
?>
<!doctype html>
<html lang="en">
<head>
    <?php include('../../includes/head.php') ?>
    <link rel="stylesheet" href="../admin.css">
    <link rel="stylesheet" href="../test.css">
    <link rel="icon" href="../../images/hammer.png">
    <title>Admin | Cities</title>
</head>
<body id="cities">

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
        <div class="report-view">
        <div id="add_city">
            <?php include('insert_city.php'); ?>
        </div>
            <div class="flex-container">
        <div class="rows">
            <h4 style="margin-left: 2.8rem;">ACTIVE CITIES</h4>
            <table>
                <tr>
                    <th>#</th>
                    <th>NAME</th>
                    <th># OF PROs</th>
                    <th>DELETE</th>
                    <th>EDIT</th>
                </tr>
                <?php $num = 1;
                while($row = oci_fetch_assoc($query1)):
                    $sql = oci_parse($db, "SELECT COUNT(DISTINCT W.PROFESSIONAL) as PRO_NUM
                                                   FROM WORK_OFFERS W
                                                   WHERE W.CITY = {$row['CID']}");
                    oci_execute($sql);
                    $pro_num = oci_fetch_assoc($sql);
                    ?>
                    <tr>
                        <td><?php echo $num++; ?></td>
                        <td><?= $row['CNAME']; ?></td>
                        <td><?php echo $pro_num['PRO_NUM']; ?></td>
                        <td><a href="delete_city.php?id=<?=$row['CID']; ?>" onclick="return confirm('Are you sure that you want to delete city <?=$row['CNAME']; ?>?')">DELETE</a></td>
                        <td><a href="update_city.php?id=<?=$row['CID']; ?>">EDIT</a></td>
                    </tr>
                <?php endwhile; ?>

            </table>
        </div>
                <hr style="margin-left: 2rem;">
        <div class="rows">
            <h4 style="margin-left: 2.8rem;">DELETED CITIES</h4>
            <table>
                <tr>
                    <th>#</th>
                    <th>NAME</th>
                    <th>RE-ADD</th>
                </tr>
                <?php $num = 1;
                while($row = oci_fetch_assoc($query2)): ?>
                    <tr>
                        <td><?php echo $num++; ?></td>
                        <td><?= $row['CNAME']; ?></td>
                        <td><a href="update_deleted_city.php?id=<?=$row['CID']; ?>" onclick="return confirm('Are you sure that you want to add again city <?=$row['CNAME']; ?>?')">RE-ADD</a></td>
                    </tr>
                <?php endwhile; ?>

            </table>
        </div>
            </div>
            <hr>
    </div>
    </div>
</main>
</body>
</html>