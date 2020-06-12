<?php
include('../../includes/db.php');
$query1 = oci_parse($db, 'SELECT * FROM accounts where role != 0 ORDER BY role');
oci_execute($query1);

$row = oci_fetch_assoc($query1);

if (isset($_POST['role'])){
    $role = 1;
    if ($_POST['role'] == 3) {
        $query3 = oci_parse($db, "SELECT A.AID, A.FNAME, A.LNAME, A.AREA_CODE, A.PHONE_NUMBER, A.ROLE, COUNT (R.RID) AS TOTAL
                                         FROM ACCOUNTS A, REQUESTS R
                                         WHERE A.ROLE = 1
                                         AND R.USER_ID = A.AID
                                         GROUP BY A.AID, A.FNAME, A.LNAME, A.AREA_CODE, A.PHONE_NUMBER, A.ROLE");
        oci_execute($query3);
    }
    else if ($_POST['role'] != 4) {
        if ($_POST['role'] == 2) {
            $role = 2;
        }
        $query3 = oci_parse($db, "SELECT * FROM ACCOUNTS WHERE ROLE = {$role}");
        oci_execute($query3);
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <?php include('../../includes/head.php') ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../admin.css">
    <link rel="stylesheet" href="../test2.css">
    <link rel="icon" href="../../images/hammer.png">
    <title>Admin | Users</title>
</head>
<body id="users">

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
            <a href="../work_offers/work_offers.php">Work offers</a>
            <a href="#" id="stay">Users <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
            <a href="../payments/payments.php">Payments</a>
        </div>

<div id="filter_block">
        <form method="post">
            <select name="role" id="role">
                <option disabled selected value>Filter user type</option>
                <option value="1">Professionals</option>
                <option value="2">Regular Users</option>
                <option value="3">Professionals who requested services</option>
                <option value="4">All</option>
            </select>
            <button type="submit">Filter</button>
        </form>


        <div class="rows">
            <?php if (isset($_POST['role']) && ($_POST['role'] == 1 || $_POST['role'] == 2 || $_POST['role'] == 3)) {
                $num = 1; ?>
            <table>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Phone Number</th>
                    <th>Role</th>
                    <?php if ($_POST['role'] == 1) { ?>
                    <th>Services</th>
                    <th>Cities</th>
                    <?php } else if ($_POST['role'] == 3) { ?>
                    <th># of requested services </th>
                    <?php } ?>
                </tr>

                <?php while ($row = oci_fetch_assoc($query3)): ?>
                <tr>
                    <td><?php echo $num; ?></td>
                    <td><?= $row['FNAME']; ?></td>
                    <td><?= $row['LNAME']; ?></td>
                    <td><?= '+' . $row['AREA_CODE'] . ' ' . $row['PHONE_NUMBER']; ?></td>
                    <?php if($row['ROLE'] == 1) : ?>
                    <td>Professional</td>
                    <?php else: ?>
                    <td>User</td>
                    <?php endif; ?>
                    <?php if($row['ROLE'] == 1 && $_POST['role'] == 1) { ?>
                    <td>
                        <button class="display_services">SHOW</button>
                        <div class="all_services" style="display: none;">
                        <?php $query = oci_parse($db, "SELECT DISTINCT W.SERVICE, S.CATEGORY 
                                                                    FROM WORK_OFFERS W, SERVICES S
                                                                    WHERE W.SERVICE = S.SID AND
                                                                                      W.professional = {$row['AID']}
                                                                    ORDER BY S.CATEGORY");
                        oci_execute($query);
                        while ($services = oci_fetch_assoc($query)) : ?>
                        <p><?= $services['CATEGORY']; ?></p>
                        <?php endwhile; ?>
                        </div>
                    </td>
                    <?php } ?>
                    <?php if($row['ROLE'] == 1 && $_POST['role'] == 1) { ?>
                        <td>
                            <button class="display_cities">SHOW</button>
                            <div class="all_cities" style="display: none;">
                                <?php $query2 = oci_parse($db, "SELECT DISTINCT W.CITY, C.CNAME 
                                                                        FROM WORK_OFFERS W, CITIES C
                                                                        WHERE W.CITY = C.CID AND
                                                                                          W.professional = {$row['AID']}
                                                                        ORDER BY C.CNAME");
                                oci_execute($query2);
                                while ($cities = oci_fetch_assoc($query2)) : ?>
                                    <p><?= $cities['CNAME']; ?></p>
                                <?php endwhile; ?>
                            </div>
                        </td>
                    <?php } ?>
                    <?php if ($_POST['role'] == 3) { ?>
                        <td><?= $row['TOTAL']; ?></td>
                    <?php } ?>
                </tr>
                <?php $num++; endwhile; ?>
            </table>
            <?php } else if (!isset($_POST['role']) || (isset($_POST['role']) && $_POST['role'] == 4)) {
                    $num = 1; ?>
                <table>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Phone Number</th>
                        <th>Role</th>
                        <th>Services</th>
                        <th>Cities</th>
                    </tr>
                <?php while ($row = oci_fetch_assoc($query1)): ?>
                    <tr>
                        <td><?php echo $num; ?></td>
                        <td><?= $row['FNAME']; ?></td>
                        <td><?= $row['LNAME']; ?></td>
                        <td><?= '+' . $row['AREA_CODE'] . ' ' . $row['PHONE_NUMBER']; ?></td>
                        <?php if($row['ROLE'] == 1) : ?>
                            <td>Professional</td>
                        <?php else: ?>
                            <td>User</td>
                        <?php endif; ?>
                        <?php if($row['ROLE'] == 1) { ?>
                            <td>
                                <button class="display_services">SHOW</button>
                                <div class="all_services" style="display: none;">
                                    <?php $row = oci_fetch_assoc($query1);
                                    $query = oci_parse($db, "SELECT DISTINCT W.SERVICE, S.CATEGORY 
                                                                    FROM WORK_OFFERS W, SERVICES S
                                                                    WHERE W.SERVICE = S.SID AND
                                                                                      W.professional = {$row['AID']}
                                                                    ORDER BY S.CATEGORY");
                                    oci_execute($query);
                                    while ($services = oci_fetch_assoc($query)) : ?>
                                        <p><?= $services['CATEGORY']; ?></p>
                                    <?php endwhile; ?>
                                </div>
                            </td>
                        <?php } else { ?>
                            <td>Not applicable</td>
                        <?php } ?>
                        <?php if($row['ROLE'] == 1) { ?>
                            <td>
                                <button class="display_cities">SHOW</button>
                                <div class="all_cities" style="display: none; ">
                                    <?php $query2 = oci_parse($db, "SELECT DISTINCT W.CITY, C.CNAME 
                                                                            FROM WORK_OFFERS W, CITIES C
                                                                            WHERE W.CITY = C.CID AND
                                                                                              W.professional = {$row['AID']}
                                                                            ORDER BY C.CNAME");
                                    oci_execute($query2);
                                    while ($cities = oci_fetch_assoc($query2)) : ?>
                                        <p><?= $cities['CNAME']; ?></p>
                                    <?php endwhile; ?>
                                </div>
                            </td>
                        <?php } else { ?>
                            <td>Not applicable</td>
                        <?php } ?>
                    </tr>
                <?php $num++; endwhile; ?>
                <?php } ?>
            </table>
    </div>
    <script>
        $(document).ready(function() {
            let counter1 = 1;
            $('.display_services').click(function() {
                if (counter1 % 2 == 0) {
                    $(this).html('SHOW');
                    $(this).siblings().hide();
                    counter1++;
                }
                else {
                    $(this).html('HIDE');
                    $(this).siblings().show();
                    counter1++;
                }
            })
            let counter2 = 1;
            $('.display_cities').click(function() {
                if (counter2 % 2 == 0) {
                    $(this).html('SHOW');
                    $(this).siblings().hide();
                    counter2++;
                }
                else {
                    $(this).html('HIDE');
                    $(this).siblings().show();
                    counter2++;
                }
            })
        });
    </script>
    </div>
    </div>
</main>
</body>
</html>