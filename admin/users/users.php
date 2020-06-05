<?php
include('../../includes/db.php');
$query1 = oci_parse($db, 'SELECT * FROM accounts where role != 0 ORDER BY role');
oci_execute($query1);

$row = oci_fetch_assoc($query1);
$query = oci_parse($db, "SELECT DISTINCT W.SERVICE, S.CATEGORY 
FROM WORK_OFFERS W, SERVICES S
WHERE W.SERVICE = S.SID AND
                  W.professional = {$row['AID']}
ORDER BY S.CATEGORY");
oci_execute($query);

$query2 = oci_parse($db, "SELECT DISTINCT W.CITY, C.CNAME 
FROM WORK_OFFERS W, CITIES C
WHERE W.CITY = C.CID AND
                  W.professional = {$row['AID']}
ORDER BY C.CNAME");
oci_execute($query2);

if (isset($_POST['role'])){
    $role = 1;
    if ($_POST['role'] == 2){
        $role = 2;
    }
    $query3 = oci_parse($db, "SELECT * FROM ACCOUNTS WHERE ROLE = {$role}");
    oci_execute($query3);
}

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
        <a href="../../includes/logout.php">Log out</a>
    </div>
</header>

<main class="center">
    <h2>Admin page</h2>
    <div class="flex-container">
        <div class="tables flex-container">
            <a href="../cities/cities.php">Cities</a>
            <a href="../services/services.php">Services</a>
            <a href="#">Work offers</a>
            <a href="users.php" id="stay">Users <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
        </div>
<div id="filter_block">
        <form method="post">
            <select name="role" id="role">
                <option disabled selected value>Filter user type</option>
                <option value="1">Professionals</option>
                <option value="2">Regular Users</option>
                <option value="professionals_users">Professionals who requested services</option>
                <option value="all">All</option>
            </select>
            <button type="submit">Filter</button>
        </form>


        <div class="rows">
            <table>
                <tr>
                    <th>SID</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Phone Number</th>
                    <th>Role</th>
                    <th>Services</th>
                    <th>Cities</th>
                </tr>
                <?php if (isset($_POST['role'])) { ?>
                <?php while ($row = oci_fetch_assoc($query3)): ?>
                <tr>
                    <td><?= $row['AID']; ?></td>
                    <td><?= $row['FNAME']; ?></td>
                    <td><?= $row['LNAME']; ?></td>
                    <td><?= $row['AREA_CODE'] . $row['PHONE_NUMBER']; ?></td>
                    <?php if($row['ROLE'] == 1) : ?>
                    <td>Professional</td>
                    <?php else: ?>
                    <td>User</td>
                    <?php endif; ?>
                    <?php if($row['ROLE'] == 1) { ?>
                    <td>
                        <button onclick="getServices()" id="display_button">Display services</button>
                        <div id="display_services" style="display: none">
                        <?php while ($services = oci_fetch_assoc($query)) : ?>
                        <p><?= $services['CATEGORY']; ?></p>
                        <?php endwhile; ?>
                        </div>
                    </td>
                    <?php } else { ?>
                        <td>No services offered</td>
                    <?php } ?>
                    <?php if($row['ROLE'] == 1) { ?>
                        <td>
                            <button onclick="getCities()" id="display_btn">Display services</button>
                            <div id="display_cities" style="display: none">
                                <?php while ($cities = oci_fetch_assoc($query2)) : ?>
                                    <p><?= $cities['CNAME']; ?></p>
                                <?php endwhile; ?>
                            </div>
                        </td>
                    <?php } else { ?>
                        <td>No services offered</td>
                    <?php } ?>
                </tr>
                <?php endwhile; ?>
                <?php } else { ?>
                <?php while ($row = oci_fetch_assoc($query1)): ?>
                    <tr>
                        <td><?= $row['AID']; ?></td>
                        <td><?= $row['FNAME']; ?></td>
                        <td><?= $row['LNAME']; ?></td>
                        <td><?= $row['AREA_CODE'] . $row['PHONE_NUMBER']; ?></td>
                        <?php if($row['ROLE'] == 1) : ?>
                            <td>Professional</td>
                        <?php else: ?>
                            <td>User</td>
                        <?php endif; ?>
                        <?php if($row['ROLE'] == 1) { ?>
                            <td>
                                <button onclick="getServices()" id="display_button">Display services</button>
                                <div id="display_services" style="display: none">
                                    <?php while ($services = oci_fetch_assoc($query)) : ?>
                                        <p><?= $services['CATEGORY']; ?></p>
                                    <?php endwhile; ?>
                                </div>
                            </td>
                        <?php } else { ?>
                            <td>No services offered</td>
                        <?php } ?>
                        <?php if($row['ROLE'] == 1) { ?>
                            <td>
                                <button onclick="getCities()" id="display_btn">Display services</button>
                                <div id="display_cities" style="display: none">
                                    <?php while ($cities = oci_fetch_assoc($query2)) : ?>
                                        <p><?= $cities['CNAME']; ?></p>
                                    <?php endwhile; ?>
                                </div>
                            </td>
                        <?php } else { ?>
                            <td>No services offered</td>
                        <?php } ?>
                    </tr>
                <?php endwhile; ?>
                <?php } ?>
            </table>
    </div>
    <script>
        function getServices() {
            document.getElementById('display_services').style.display = 'block';
            document.getElementById('display_button').style.display = 'none';
        }
        function getCities() {
            document.getElementById('display_cities').style.display = 'block';
            document.getElementById('display_btn').style.display = 'none';
        }
    </script>
    </div>
    </div>
</main>
</body>
</html>