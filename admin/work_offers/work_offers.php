<?php
include('../../includes/db.php');

$query2 = oci_parse($db, 'SELECT * FROM services WHERE date_deleted IS NULL ORDER BY category');
oci_execute($query2);
$query3 = oci_parse($db, 'SELECT * FROM cities WHERE date_deleted IS NULL ORDER BY cname');
oci_execute($query3);
$query4 = oci_parse($db, "SELECT * FROM accounts WHERE role = 1 ORDER BY FNAME, LNAME");
oci_execute($query4);

if (isset($_POST['city']) && isset($_POST['service'])) {
    $list_specific = oci_parse($db, "SELECT p.aid, p.lname, p.fname, w.*, s.category, c.cname
                                                FROM work_offers w, accounts p, services s, cities c
                                                WHERE w.service = {$_POST['service']} 
                                                AND w.city = {$_POST['city']} 
                                                AND w.professional = p.aid
                                                AND w.service = s.sid
                                                AND w.city = c.cid
                                                ORDER BY P.FNAME, P.LNAME");
    oci_execute($list_specific);
}
if (!isset($_POST['city']) && isset($_POST['service'])) {
    $list_by_service = oci_parse($db, "SELECT DISTINCT W.PROFESSIONAL, P.FNAME, P.LNAME, P.AID, s.category
                                                  FROM work_offers w, accounts p, services s 
                                                  WHERE w.service = {$_POST['service']} 
                                                  AND w.professional = p.aid  
                                                  and w.service = s.sid
                                                  ORDER BY P.FNAME, P.LNAME");
    oci_execute($list_by_service);
}
if (isset($_POST['city']) && !isset($_POST['service'])) {
    $list_by_city = oci_parse($db, "SELECT DISTINCT W.PROFESSIONAL, P.FNAME, P.LNAME, P.AID, C.CNAME
                                                FROM work_offers w, accounts p, cities c
                                                WHERE w.city = {$_POST['city']}
                                                and w.city = c.cid
                                                AND w.professional = p.aid
                                                ORDER BY P.FNAME, P.LNAME");
    oci_execute($list_by_city);
}
?>
<!doctype html>
<html lang="en">
<head>
    <?php include('../../includes/head.php') ?>
    <link rel="stylesheet" href="../admin.css">
    <link rel="stylesheet" href="../test.css">
    <link rel="icon" href="../../images/hammer.png">
    <title>Admin | Work Offers</title>
</head>
<body id="work_offers">

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
            <a href="../payments/payments.php">Payments</a>
        </div>
        <div class="report-view">
            <form method="post">
                <div class="flex-container">

                    <select name="service">
                        <option disabled selected value>Select a service</option>
                        <?php while ($row2 = oci_fetch_assoc($query2)): ?>
                            <option value="<?= $row2['SID']; ?>"><?= $row2['CATEGORY']; ?></option>
                        <?php endwhile; ?>
                    </select>

                    <select name="city">
                        <option disabled selected value>&#128205;</option>
                        <?php while ($row3 = oci_fetch_assoc($query3)): ?>
                            <option value="<?= $row3['CID']; ?>"><?= $row3['CNAME']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <button id="get-professionals">Get Started</button>

                </div>
            </form>
            <div class="rows">
                <?php if (isset($_POST['service']) && isset($_POST['city'])) { ?>
                <table>
                    <tr>
                        <th colspan="5">PROFESSIONALS</th>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <th>Surname</th>
                        <th># accepted <br> requests</th>
                        <th># rejected <br> requests</th>
                        <th># total <br> requests</th>
                    </tr>
                    <?php while ($row = oci_fetch_assoc($list_specific)):
                        $sql = oci_parse($db, "SELECT COUNT(*) AS ACCEPTED
                                                      FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W
                                                      WHERE R.RID = H.REQUEST
                                                      AND H.STATUS = 1
                                                      AND W.SERVICE = {$_POST['service']}
                                                      AND W.CITY = {$_POST['city']}
                                                      AND R.WORK_OFFER = W.WID
                                                      AND W.PROFESSIONAL = {$row['AID']}");
                        oci_execute($sql);
                        $num_of_accepted = oci_fetch_assoc($sql);
                        $sql2 = oci_parse($db, "SELECT COUNT(*) AS REJECTED
                                                      FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W
                                                      WHERE R.RID = H.REQUEST
                                                      AND H.STATUS = 2
                                                      AND W.SERVICE = {$_POST['service']}
                                                      AND W.CITY = {$_POST['city']}
                                                      AND R.WORK_OFFER = W.WID
                                                      AND W.PROFESSIONAL = {$row['AID']}");
                        oci_execute($sql2);
                        $num_of_rejected = oci_fetch_assoc($sql2);
                        $sql3 = oci_parse($db, "SELECT COUNT(*) AS TOTAL
                                                      FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W
                                                      WHERE R.WORK_OFFER = W.WID
                                                      AND W.SERVICE = {$_POST['service']}
                                                      AND W.CITY = {$_POST['city']}
                                                      AND W.PROFESSIONAL = {$row['AID']}");
                        oci_execute($sql3);
                        $total = oci_fetch_assoc($sql3);
                        ?>
                        <tr>
                            <td><?= $row['FNAME']; ?></td>
                            <td><?= $row['LNAME']; ?></td>
                            <td><?= $num_of_accepted['ACCEPTED']; ?></td>
                            <td><?= $num_of_rejected['REJECTED']; ?></td>
                            <td><?= $total['TOTAL']; ?></td>
                        </tr>
                    <?php endwhile; ?>

                </table>
                <?php } else if (!isset($_POST['service']) && isset($_POST['city'])) { ?>
                    <table>
                        <tr>
                            <th colspan="5">PROFESSIONALS</th>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <th>Surname</th>
                            <th># accepted <br> requests</th>
                            <th># rejected <br> requests</th>
                            <th># total <br> requests</th>
                        </tr>
                        <?php while ($row = oci_fetch_assoc($list_by_city)):
                            $sql = oci_parse($db, "SELECT COUNT(*) AS ACCEPTED
                                                      FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W
                                                      WHERE R.RID = H.REQUEST
                                                      AND H.STATUS = 1
                                                      AND W.CITY = {$_POST['city']}
                                                      AND R.WORK_OFFER = W.WID
                                                      AND W.PROFESSIONAL = {$row['AID']}");
                            oci_execute($sql);
                            $num_of_accepted = oci_fetch_assoc($sql);
                            $sql2 = oci_parse($db, "SELECT COUNT(*) AS REJECTED
                                                      FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W
                                                      WHERE R.RID = H.REQUEST
                                                      AND H.STATUS = 2
                                                      AND W.CITY = {$_POST['city']}
                                                      AND R.WORK_OFFER = W.WID
                                                      AND W.PROFESSIONAL = {$row['AID']}");
                            oci_execute($sql2);
                            $num_of_rejected = oci_fetch_assoc($sql2);
                            $sql3 = oci_parse($db, "SELECT COUNT(*) AS TOTAL
                                                      FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W
                                                      WHERE R.WORK_OFFER = W.WID
                                                      AND W.CITY = {$_POST['city']}
                                                      AND W.PROFESSIONAL = {$row['AID']}");
                            oci_execute($sql3);
                            $total = oci_fetch_assoc($sql3);
                            ?>
                            <tr>
                                <td><?= $row['FNAME']; ?></td>
                                <td><?= $row['LNAME']; ?></td>
                                <td><?= $num_of_accepted['ACCEPTED']; ?></td>
                                <td><?= $num_of_rejected['REJECTED']; ?></td>
                                <td><?= $total['TOTAL']; ?></td>
                            </tr>
                        <?php endwhile; ?>

                    </table>
                <?php } else if (isset($_POST['service']) && !isset($_POST['city'])) { ?>
                    <table>
                        <tr>
                            <th colspan="5">PROFESSIONALS</th>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <th>Surname</th>
                            <th># accepted <br> requests</th>
                            <th># rejected <br> requests</th>
                            <th># total <br> requests</th>
                        </tr>
                        <?php while ($row = oci_fetch_assoc($list_by_service)):
                            $sql = oci_parse($db, "SELECT COUNT(*) AS ACCEPTED
                                                      FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W
                                                      WHERE R.RID = H.REQUEST
                                                      AND H.STATUS = 1
                                                      AND W.SERVICE = {$_POST['service']}
                                                      AND R.WORK_OFFER = W.WID
                                                      AND W.PROFESSIONAL = {$row['AID']}");
                            oci_execute($sql);
                            $num_of_accepted = oci_fetch_assoc($sql);
                            $sql2 = oci_parse($db, "SELECT COUNT(*) AS REJECTED
                                                      FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W
                                                      WHERE R.RID = H.REQUEST
                                                      AND H.STATUS = 2
                                                      AND W.SERVICE = {$_POST['service']}
                                                      AND R.WORK_OFFER = W.WID
                                                      AND W.PROFESSIONAL = {$row['AID']}");
                            oci_execute($sql2);
                            $num_of_rejected = oci_fetch_assoc($sql2);
                            $sql3 = oci_parse($db, "SELECT COUNT(*) AS TOTAL
                                                      FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W
                                                      WHERE R.WORK_OFFER = W.WID
                                                      AND W.SERVICE = {$_POST['service']}
                                                      AND W.PROFESSIONAL = {$row['AID']}");
                            oci_execute($sql3);
                            $total = oci_fetch_assoc($sql3);
                            ?>
                            <tr>
                                <td><?= $row['FNAME']; ?></td>
                                <td><?= $row['LNAME']; ?></td>
                                <td><?= $num_of_accepted['ACCEPTED']; ?></td>
                                <td><?= $num_of_rejected['REJECTED']; ?></td>
                                <td><?= $total['TOTAL']; ?></td>
                            </tr>
                        <?php endwhile; ?>

                    </table>
                <?php } else { ?>
                    <table>
                        <tr>
                            <th colspan="5">PROFESSIONALS</th>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <th>Surname</th>
                            <th># accepted <br> requests</th>
                            <th># rejected <br> requests</th>
                            <th># total <br> requests</th>
                        </tr>
                        <?php while ($row = oci_fetch_assoc($query4)):
                            $sql = oci_parse($db, "SELECT COUNT(*) AS ACCEPTED
                                                      FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W
                                                      WHERE R.RID = H.REQUEST
                                                      AND H.STATUS = 1
                                                      AND R.WORK_OFFER = W.WID
                                                      AND W.PROFESSIONAL = {$row['AID']}");
                            oci_execute($sql);
                            $num_of_accepted = oci_fetch_assoc($sql);
                            $sql2 = oci_parse($db, "SELECT COUNT(*) AS REJECTED
                                                      FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W
                                                      WHERE R.RID = H.REQUEST
                                                      AND H.STATUS = 2
                                                      AND R.WORK_OFFER = W.WID
                                                      AND W.PROFESSIONAL = {$row['AID']}");
                            oci_execute($sql2);
                            $num_of_rejected = oci_fetch_assoc($sql2);
                            $sql3 = oci_parse($db, "SELECT COUNT(*) AS TOTAL
                                                      FROM REQUESTS R, REQUESTS_HISTORY H, WORK_OFFERS W
                                                      WHERE R.WORK_OFFER = W.WID
                                                      AND W.PROFESSIONAL = {$row['AID']}");
                            oci_execute($sql3);
                            $total = oci_fetch_assoc($sql3);
                            ?>
                            <tr>
                                <td><?= $row['FNAME']; ?></td>
                                <td><?= $row['LNAME']; ?></td>
                                <td><?= $num_of_accepted['ACCEPTED']; ?></td>
                                <td><?= $num_of_rejected['REJECTED']; ?></td>
                                <td><?= $total['TOTAL']; ?></td>
                            </tr>
                        <?php endwhile; ?>

                    </table>
                <?php } ?>
                <?php
                $query_c = oci_parse($db, "SELECT DISTINCT A.AID, A.FNAME, A.LNAME, A.PRIMARY_CITY
                                                  FROM ACCOUNTS A, WORK_OFFERS W, CITIES C 
                                                  WHERE W.PROFESSIONAL = A.AID
                                                  AND W.CITY = C.CID
                                                  AND UPPER (C.CNAME) NOT IN (SELECT UPPER (PRIMARY_CITY) FROM ACCOUNTS
                                                                              WHERE AID = A.AID)
                                                  ORDER BY A.FNAME, A.LNAME");
                oci_execute($query_c);
                ?>
                <table>
                    <tr><th colspan="4">List of professionals that offer services outside of their place of residence</th></tr>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>City of Residence</th>
                        <th>Service offered in: </th>
                    </tr>
                    <?php
                    $num = 1;
                    while ($professionals = oci_fetch_assoc($query_c)) {
                        $cities = oci_parse($db, "SELECT DISTINCT W.CITY, C.CNAME
                                                         FROM WORK_OFFERS W, CITIES C
                                                         WHERE W.PROFESSIONAL = {$professionals['AID']}
                                                         AND W.CITY = C.CID
                                                         AND UPPER(C.CNAME) != UPPER('{$professionals['PRIMARY_CITY']}')");
                        oci_execute($cities);
                        ?>
                    <tr>
                        <td><?php echo $num; $num++; ?></td>
                        <td><?= $professionals['FNAME'] ?></td>
                        <td><?= $professionals['LNAME'] ?></td>
                        <td><?= $professionals['PRIMARY_CITY'] ?></td>
                        <td>
                        <?php while ($list_cities = oci_fetch_assoc($cities)) : ?>
                        <p><?= $list_cities['CNAME'] ?></p>
                        <?php endwhile; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
        
    </div>
</main>
</body>
</html>