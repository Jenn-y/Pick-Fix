<?php
include('../../includes/db.php');
$query1 = oci_parse($db, 'SELECT * FROM accounts');
oci_execute($query1);
?>
<!doctype html>
<html lang="en">
<head>
    <?php include('../../includes/head.php') ?>
    <link rel="stylesheet" href="../admin.css">
    <title>Admin | Cities</title>
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
            <a href="../cities/cities.php" id="stay">Cities <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
            <a href="../services/services.php">Services</a>
            <a href="#">Work offers</a>
            <a href="#">Users</a>
        </div>
        <div class="rows">
            <table>
                <tr>
                    <th>SID</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Role</th>
                </tr>
                <?php while($row = oci_fetch_assoc($query1)): ?>
                    <tr>
                        <td><?= $row['CID']; ?></td>
                        <td><?= $row['FNAME']; ?></td>
                        <td><?= $row['LNAME']; ?></td>
                        <td><?= $row['EMAIL']; ?></td>
                        <td><?= $row['AREA_CODE'] . $row['PHONE_NUMBER']; ?></td>
                        <td><?= $row['ROLE']; ?></td>
                    </tr>
                <?php endwhile; ?>

            </table>
        </div>
    </div>
</main>
</body>
</html>