<?php
include('../../includes/db.php');
$query1 = oci_parse($db, 'SELECT * FROM services
                                  LEFT JOIN (
                                             SELECT service, COUNT(*) as no_of_pros
                                             FROM ( 
                                                   SELECT service, professional FROM work_offers 
                                                   GROUP BY service, professional
                                                   )
                                             GROUP BY service
                                             )
                                  ON sid = service
                                  WHERE date_deleted IS NULL
                                  ORDER BY sid');
oci_execute($query1);
$query2 = oci_parse($db, 'SELECT * FROM services WHERE date_deleted IS NOT NULL');
oci_execute($query2);
?>
<!doctype html>
<html lang="en">
<head>
    <?php include('../../includes/head.php') ?>
    <link rel="stylesheet" href="../admin.css">
    <link rel="icon" href="../../images/hammer.png">
    <title>Admin | Services</title>
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
            <a href="../cities/cities.php">Cities</a>
            <a href="services.php" id="stay">Services <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
            <a href="../work_offers/work_offers.php">Work offers</a>
            <a href="../users/users.php">Users</a>
            <a href="../payments/payments.php">Payments</a>
        </div>
        <div class="rows">
            <table>
                <tr>
                    <th>SID</th>
                    <th>Category</th>
                    <th>No. of Pros</th>
                </tr>
                <?php while($row = oci_fetch_assoc($query1)): ?>
                    <tr>
                        <td><?= $row['SID']; ?></td>
                        <td><?= $row['CATEGORY']; ?></td>
                        <?php if($row['NO_OF_PROS'] == NULL): ?>
                            <td>0</td>
                        <?php else: ?>
                            <td><?= $row['NO_OF_PROS']; ?></td>
                        <?php endif; ?>
                        <td><a href="delete_service.php?id=<?=$row['SID']; ?>" onclick="return confirm('Are you sure that you want to delete category <?=$row['CATEGORY']; ?>?')">delete</a></td>
                        <td><a href="update_service.php?id=<?=$row['SID']; ?>">edit</a></td>
                    </tr>
                <?php endwhile; ?>
            </table>
            <?php include('insert_service.php'); ?>
        </div>
        <div class="rows">
            <table>
                <tr>
                    <th>SID</th>
                    <th>Category</th>
                </tr>
                <?php while($row = oci_fetch_assoc($query2)): ?>
                    <tr>
                        <td><?= $row['SID']; ?></td>
                        <td><?= $row['CATEGORY']; ?></td>
                        <td><a href="update_deleted_service.php?id=<?=$row['SID']; ?>">re-add</a></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</main>
</body>
</html>