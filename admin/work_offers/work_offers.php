<?php
include('../../includes/db.php');




$query = oci_parse($db, "SELECT DISTINCT C.CID, C.CNAME, P.FNAME, P.LNAME, P.AID, P.AREA_CODE, P.PHONE_NUMBER, P.ROLE, W.SERVICE, S.CATEGORY 
                                FROM WORK_OFFERS W, SERVICES S, ACCOUNTS P, CITIES C
                                WHERE W.SERVICE = S.SID 
                                AND W.CITY = C.CID
                                ORDER BY C.CNAME");
oci_execute($query);
?>
<!doctype html>
<html lang="en">
<head>
    <?php include('../../includes/head.php') ?>
    <link rel="stylesheet" href="../admin.css">
    <title>Admin | Work Offers</title>
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
            <a href="../services/services.php">Services</a>
            <a href="#" id="stay">Work offers <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
            <a href="../users/users.php">Users</a>
            <a href="#">Payments</a>
        </div>
        <div class="rows">
            <table>
                <tr>
                    <th>CITY</th>
                    <th>PROFESSIONALS</th>
                    <th>Surname</th>
                    <th>Phone Number</th>
                    <th>Role</th>
                </tr>
                <?php while($row = oci_fetch_assoc($query)): ?>
                    <tr>
                        <td><?= $row['AID']; ?></td>
                        <td><?= $row['FNAME']; ?></td>
                        <td><?= $row['LNAME']; ?></td>
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