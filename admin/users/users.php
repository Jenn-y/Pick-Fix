<?php
include('../../includes/db.php');
$query1 = oci_parse($db, 'SELECT * FROM accounts where role != 0 ORDER BY role');
oci_execute($query1);


?>
<!doctype html>
<html lang="en">
<head>
    <?php include('../../includes/head.php') ?>
    <link rel="stylesheet" href="../admin.css">
    <link rel="stylesheet" href="../test.css">
    <title>Admin | Cities</title>
</head>
<body>

<header>
    <div class="inner-header flex-container center">
        <h1><a href="#">Pick&Fix</a></h1>
        <a href="../../includes/logout.php">Log out</a>
    </div>
</header>

<main class="center">
    <h2>Admin page</h2>
    <div class="flex-container">
        <div class="tables flex-container">
            <a href="../cities/cities.php" id="stay">Cities <i class="fa fa-long-arrow-right"
                                                               aria-hidden="true"></i></a>
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
                    <th>Phone Number</th>
                    <th>Role</th>
                    <th>Services</th>
                </tr>
                <?php while ($row = oci_fetch_assoc($query1)): ?>
                <tr>
                    <td><?= $row['AID']; ?></td>
                    <td><?= $row['FNAME']; ?></td>
                    <td><?= $row['LNAME']; ?></td>
                    <td><?= $row['AREA_CODE'] . $row['PHONE_NUMBER']; ?></td>
                    <td><?= $row['ROLE']; ?></td>
                    <td>
                        <button onclick="getServices()">Display services</button>
                    </td>
                </tr>
                    <?php if ($row['ROLE'] == 1) {
                    $query = oci_parse($db, "SELECT DISTINCT W.SERVICE, S.CATEGORY 
FROM WORK_OFFERS W, SERVICES S
WHERE W.SERVICE = S.SID AND
                  W.professional = {$row['AID']}
ORDER BY S.CATEGORY");
                    oci_execute($query);
                    ?>
                    <table id="get-services">
                        <tr>
                        <th>Services</th>
                        </tr>
                        <tr>
                        <td>
                            <?php while ($row = oci_fetch_assoc($query)) : ?>
                                <p><?= $row['CATEGORY']; ?></p>
                            <?php endwhile; ?>
                        </td>
                        </tr>
                    </table>
                <?php } ?>
        <?php endwhile; ?>
        </table>
    </div>
    </div>
    <script>
        function getServices() {
            document.getElementById('get-services').style.display = 'block';
        }
    </script>
</main>
</body>
</html>