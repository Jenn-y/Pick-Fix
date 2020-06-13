<?php

include_once('../../includes/db.php');

if (isset($_POST['from_date1']) && isset($_POST['to_date1'])) {
    $from_date = $_POST['from_date1'];
    $to_date = $_POST['to_date1'];

    $query = oci_parse($db, "SELECT aid, fname, lname, money_earned, jobs FROM accounts
                                     FULL JOIN (
                                        SELECT professional, sum(num_of_hrs*REQUESTS.charge_per_hour) AS money_earned, count (rhid) AS jobs FROM requests_history
                                        JOIN REQUESTS ON request = rid
                                        JOIN WORK_OFFERS ON work_offer = wid
                                        WHERE status = 1 AND datetime >= to_date('{$from_date}','YYYY-MM-DD') AND datetime <= to_date('{$to_date}','YYYY-MM-DD')
                                        GROUP BY professional
                                        )
                                     ON aid = professional
                                     WHERE role = 1
                                     ORDER BY money_earned desc");
    oci_execute($query);
}
if (isset($_POST['from_date2']) && isset($_POST['to_date2'])) {
    $from_date = $_POST['from_date2'];
    $to_date = $_POST['to_date2'];
    $query2 = oci_parse($db, "SELECT sid, category, min_billed, max_billed, avg_billed, n_of_requests FROM services
                                      FULL JOIN (
                                        SELECT service, MIN(num_of_hrs*REQUESTS.charge_per_hour) AS min_billed, MAX(num_of_hrs*REQUESTS.charge_per_hour) AS max_billed, ROUND(AVG(num_of_hrs*REQUESTS.charge_per_hour), 3) AS avg_billed, count(*) AS n_of_requests, status 
                                        FROM REQUESTS_HISTORY
                                        JOIN REQUESTS ON request = rid
                                        JOIN WORK_OFFERS ON work_offer = wid
                                        WHERE status = 1 AND datetime >= to_date('{$from_date}','YYYY-MM-DD') AND datetime <= to_date('{$to_date}','YYYY-MM-DD')
                                        GROUP BY service, status
                                        )
                                      ON sid = service
                                      WHERE date_deleted IS NULL
                                      ORDER BY category");
    oci_execute($query2);
}
if (isset($_POST['from_date5']) && isset($_POST['to_date5'])) {
    $from_date = $_POST['from_date5'];
    $to_date = $_POST['to_date5'];
    $query2 = oci_parse($db, "SELECT cid, cname, min_billed, max_billed, avg_billed, n_of_requests FROM cities
                                      FULL JOIN (
                                        SELECT city, MIN(num_of_hrs*REQUESTS.charge_per_hour) AS min_billed, MAX(num_of_hrs*REQUESTS.charge_per_hour) AS max_billed, ROUND(AVG(num_of_hrs*REQUESTS.charge_per_hour), 3) AS avg_billed, count(*) AS n_of_requests, status 
                                        FROM REQUESTS_HISTORY
                                        JOIN REQUESTS ON request = rid
                                        JOIN WORK_OFFERS ON work_offer = wid
                                        WHERE status = 1 AND datetime >= to_date('{$from_date}','YYYY-MM-DD') AND datetime <= to_date('{$to_date}','YYYY-MM-DD')
                                        GROUP BY city, status
                                        )
                                      ON cid = city
                                      WHERE date_deleted IS NULL
                                      ORDER BY cname");
    oci_execute($query2);
}
if (isset($_POST['from_date3']) && isset($_POST['to_date3'])) {
    $from_date = $_POST['from_date3'];
    $to_date = $_POST['to_date3'];
    $query3 = oci_parse($db, "SELECT aid, fname, lname, total_money_spent FROM accounts
                                      FULL JOIN (
                                        SELECT professional, sum(amount) total_money_spent FROM fee_payments
                                        WHERE date_paid >= to_date('{$from_date}','YYYY-MM-DD') AND date_paid <= to_date('{$to_date}','YYYY-MM-DD')
                                        GROUP BY professional
                                        )
                                      ON aid = professional
                                      ORDER BY total_money_spent desc");
    oci_execute($query3);
}
if (isset($_POST['from_date4']) && isset($_POST['to_date4'])) {
    $from_date = $_POST['from_date4'];
    $to_date = $_POST['to_date4'];
    $query4 = oci_parse($db, "SELECT aid, fname, lname, total_paid FROM accounts
                                      FULL JOIN (
                                        SELECT user_id, sum(num_of_hrs*charge_per_hour) AS total_paid FROM requests
                                        JOIN requests_history ON rid = request
                                        WHERE status = 1 AND datetime >= to_date('{$from_date}','YYYY-MM-DD') AND datetime <= to_date('{$to_date}','YYYY-MM-DD')
                                        GROUP BY user_id
                                        )
                                      ON aid = user_id
                                      ORDER BY total_paid desc");
    oci_execute($query4);
}
?>
<!doctype html>
<html lang="en">
<head>
    <?php include('../../includes/head.php') ?>
    <link rel="stylesheet" href="../admin.css">
    <link rel="stylesheet" href="../test.css">
    <link rel="icon" href="../../images/hammer.png">
    <title>Admin | Payments</title>
    <style>
        h3 {
            padding-top: 1rem;
        }
    </style>
</head>
<body id="payments">

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
            <a href="../users/users.php">Users</a>
            <a href="#" id="stay">Payments <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
        </div>
        <div class="report-view">

            <p>Number of professionals that continued payment after the 1st payment plan expiration:
                <?php $sql = oci_parse($db, "SELECT COUNT (A.AID) AS CONT
                                                          FROM ACCOUNTS A
                                                          WHERE (SELECT COUNT(*) FROM FEE_PAYMENTS WHERE PROFESSIONAL = A.AID) >= 2");
                oci_execute($sql);
                $num_of_pros = oci_fetch_assoc($sql);
                ?>
                <span><?php echo $num_of_pros['CONT']; ?></span></p>

            <form method="post">
                <p>Amount of money professionals earned</p>
                <label for="from">From</label>
                <input name="from_date1" type="date" id="from">

                <label for="to">To</label>
                <input name="to_date1" type="date" id="to">

                <button type="submit">Go</button>
            </form>
            <?php if (isset($_POST['from_date1']) && isset($_POST['to_date1'])): ?>
                <table>
                    <tr>
                        <th colspan="5"><?php echo $_POST['from_date1']; ?> - <?php echo $_POST['to_date1']; ?></th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Amount Earned</th>
                        <th># of accepted <br> jobs</th>
                    </tr>
                    <?php $num = 1; $total = 0;
                    while ($row = oci_fetch_assoc($query)):
                        $total += $row['MONEY_EARNED'];?>
                        <tr>
                            <td><?php echo $num++; ?></td>
                            <td><?= $row['FNAME'] ?></td>
                            <td><?= $row['LNAME'] ?></td>
                            <?php if ($row['MONEY_EARNED'] == 0) : ?>
                            <td><?php echo '0 BAM'; ?></td>
                            <?php else : ?>
                            <td><?= $row['MONEY_EARNED'] . ' BAM'?></td>
                            <?php endif; ?>
                            <td><?= $row['JOBS'] ?? 0 ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <tr>
                        <th colspan="5" style="border: 2px solid grey;">TOTAL MONEY EARNED by professionals: <?php echo $total . ' BAM'; ?> </th>
                    </tr>
                </table>
            <?php endif; ?>

            <form method="post">
                <p>Minimum, maximum, average and total amount billed per service and the number of completed jobs</p>
                <label for="from">From</label>
                <input name="from_date2" type="date" id="from">

                <label for="to">To</label>
                <input name="to_date2" type="date" id="to">

                <button type="submit">Go</button>
            </form>
            <?php if (isset($_POST['from_date2']) && isset($_POST['to_date2'])): ?>
                <table>
                    <tr>
                        <th colspan="6"><?php echo $_POST['from_date2']; ?> - <?php echo $_POST['to_date2']; ?></th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Min billed</th>
                        <th>Max billed</th>
                        <th>Avg billed</th>
                        <th># of completed jobs</th>
                    </tr>
                    <?php $num = 1;
                    while ($row = oci_fetch_assoc($query2)): ?>
                        <tr>
                            <td><?php echo $num++; ?></td>
                            <td><?= $row['CATEGORY'] ?></td>
                            <td><?php echo isset($row['MIN_BILLED'])? $row['MIN_BILLED'] . ' BAM' : 0 . ' BAM' ?></td>
                            <td><?php echo isset($row['MAX_BILLED'])? $row['MAX_BILLED'] . ' BAM' : 0 . ' BAM' ?></td>
                            <td><?php echo isset($row['AVG_BILLED'])? $row['AVG_BILLED'] . ' BAM' : 0 . ' BAM' ?></td>
                            <td><?= $row['N_OF_REQUESTS'] ?? 0 ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php endif; ?>

            <form method="post">
                <p>Minimum, maximum, average and total amount billed per city and the number of completed jobs</p>
                <label for="from">From</label>
                <input name="from_date5" type="date" id="from">

                <label for="to">To</label>
                <input name="to_date5" type="date" id="to">

                <button type="submit">Go</button>
            </form>
            <?php if (isset($_POST['from_date5']) && isset($_POST['to_date5'])): ?>
                <table>
                    <tr>
                        <th colspan="6"><?php echo $_POST['from_date5']; ?> - <?php echo $_POST['to_date5']; ?></th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>City</th>
                        <th>Min billed</th>
                        <th>Max billed</th>
                        <th>Avg billed</th>
                        <th># of completed jobs</th>
                    </tr>
                    <?php $num = 1;
                    while ($row = oci_fetch_assoc($query2)): ?>
                        <tr>
                            <td><?php echo $num++; ?></td>
                            <td><?= $row['CNAME'] ?></td>
                            <td><?php echo isset($row['MIN_BILLED'])? $row['MIN_BILLED'] . ' BAM' : 0 . ' BAM' ?></td>
                            <td><?php echo isset($row['MAX_BILLED'])? $row['MAX_BILLED'] . ' BAM' : 0 . ' BAM' ?></td>
                            <td><?php echo isset($row['AVG_BILLED'])? $row['AVG_BILLED'] . ' BAM' : 0 . ' BAM' ?></td>
                            <td><?= $row['N_OF_REQUESTS'] ?? 0 ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php endif; ?>

            <form method="post">
                <p>Amount of money professionals paid for their usage fees</p>
                <label for="from">From</label>
                <input name="from_date3" type="date" id="from">

                <label for="to">To</label>
                <input name="to_date3" type="date" id="to">

                <button type="submit">Go</button>
            </form>
            <?php if (isset($_POST['from_date3']) && isset($_POST['to_date3'])): ?>
                <table>
                    <tr>
                        <th colspan="4"><?php echo $_POST['from_date3']; ?> - <?php echo $_POST['to_date3']; ?></th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Amount</th>
                    </tr>
                    <?php $num = 1; $total = 0;
                    while ($row = oci_fetch_assoc($query3)):
                        $total += $row['TOTAL_MONEY_SPENT'];?>
                        <tr>
                            <td><?php echo $num++; ?></td>
                            <td><?= $row['FNAME'] ?></td>
                            <td><?= $row['LNAME'] ?></td>
                            <td><?php echo isset($row['TOTAL_MONEY_SPENT'])? $row['TOTAL_MONEY_SPENT'] . ' BAM' : 0 . ' BAM' ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <tr>
                        <th colspan="4" style="border: 2px solid grey;">TOTAL MONEY EARNED: <?php echo $total . ' BAM'; ?> </th>
                    </tr>
                </table>
            <?php endif; ?>

            <form method="post">
                <p>Amount of money users spent for services</p>
                <label for="from">From</label>
                <input name="from_date4" type="date" id="from">

                <label for="to">To</label>
                <input name="to_date4" type="date" id="to">

                <button type="submit">Go</button>
            </form>
            <?php if (isset($_POST['from_date4']) && isset($_POST['to_date4'])): ?>
                <table>
                    <tr>
                        <th colspan="4"><?php echo $_POST['from_date4']; ?> - <?php echo $_POST['to_date4']; ?></th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Amount</th>
                    </tr>
                    <?php $num = 1; $total = 0;
                    while ($row = oci_fetch_assoc($query4)):
                        $total += $row['TOTAL_PAID']; ?>
                        <tr>
                            <td><?php echo $num++; ?></td>
                            <td><?= $row['FNAME'] ?></td>
                            <td><?= $row['LNAME'] ?></td>
                            <td><?php echo isset($row['TOTAL_PAID'])? $row['TOTAL_PAID'] . ' BAM' : 0 . ' BAM' ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <tr>
                        <th colspan="4" style="border: 2px solid grey;">TOTAL MONEY SPENT ON SERVICES: <?php echo $total . ' BAM'; ?> </th>
                    </tr>
                </table>
            <?php endif; ?>

        </div>
    </div>
</main>

</body>
</html>