<?php

include_once('../../includes/db.php');

$months = [1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
$years = range(2019, 2022);

$flag = 0;

if($_POST) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

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
                                     ORDER BY aid");
    oci_execute($query);

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
                                      ORDER BY sid");
    oci_execute($query2);

    $query3 = oci_parse($db, "SELECT aid, fname, lname, total_money_spent FROM accounts
                                      FULL JOIN (
                                        SELECT professional, sum(amount) total_money_spent FROM fee_payments
                                        WHERE date_paid >= to_date('{$from_date}','YYYY-MM-DD') AND date_paid <= to_date('{$to_date}','YYYY-MM-DD')
                                        GROUP BY professional
                                        )
                                      ON aid = professional
                                      ORDER BY aid");
    oci_execute($query3);

    $query4 = oci_parse($db, "SELECT aid, fname, lname, total_paid FROM accounts
                                      FULL JOIN (
                                        SELECT user_id, sum(num_of_hrs*charge_per_hour) AS total_paid FROM requests
                                        JOIN requests_history ON rid = request
                                        WHERE status = 1 AND datetime >= to_date('{$from_date}','YYYY-MM-DD') AND datetime <= to_date('{$to_date}','YYYY-MM-DD')
                                        GROUP BY user_id
                                        )
                                      ON aid = user_id
                                      ORDER BY aid");
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
        <div class="tables flex-container" style="padding-right: 5rem;">
            <a href="../cities/cities.php">Cities</a>
            <a href="../services/services.php">Services</a>
            <a href="../work_offers/work_offers.php">Work offers</a>
            <a href="../users/users.php">Users</a>
            <a href="#" id="stay">Payments <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
        </div>
        <div>
            <form method="post">
                <label for="from">From</label>
                <input name="from_date" type="date" id="from">
                
                <label for="to">To</label>
                <input name="to_date" type="date" id="to">
                
                <button type="submit" style="padding: 0 2rem">Go</button>
            </form>
            <?php if(!empty($_POST)): ?>
            <h3>Amount of money professionals earned in a given period</h3>
            <table>
                <tr>
                    <th>AID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Amount Earned</th>
                    <th># of accepted <br> jobs</th>
                </tr>
                <?php while($row = oci_fetch_assoc($query)): ?>
                <tr>
                    <td><?= $row['AID'] ?></td>
                    <td><?= $row['FNAME'] ?></td>
                    <td><?= $row['LNAME'] ?></td>
                    <td><?= $row['MONEY_EARNED'] ?? 0 ?></td>
                    <td><?= $row['JOBS'] ?? 0?></td>
                </tr>
                <?php endwhile; ?>
            </table>

            <table>
                <h3>Services -> minimum, maximum, average and total amount billed for a given period</h3>
                <h3>+ The total amount of accepted requests for each service</h3>
                <tr>
                    <th>SID</th>
                    <th>Category</th>
                    <th>Min billed</th>
                    <th>Max billed</th>
                    <th>Avg billed</th>
                    <th>Number of requests</th>
                </tr>
                <?php while($row = oci_fetch_assoc($query2)): ?>
                    <tr>
                        <td><?= $row['SID'] ?></td>
                        <td><?= $row['CATEGORY'] ?></td>
                        <td><?= $row['MIN_BILLED'] ?? 0?></td>
                        <td><?= $row['MAX_BILLED'] ?? 0?></td>
                        <td><?= $row['AVG_BILLED'] ?? 0?></td>
                        <td><?= $row['N_OF_REQUESTS'] ?? 0?></td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <h3>Amount of money professionals paid us for the usage of this system</h3>
            <table>
                <tr>
                    <th>AID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Amount</th>
                </tr>
                <?php while($row = oci_fetch_assoc($query3)): ?>
                    <tr>
                        <td><?= $row['AID'] ?></td>
                        <td><?= $row['FNAME'] ?></td>
                        <td><?= $row['LNAME'] ?></td>
                        <td><?= $row['TOTAL_MONEY_SPENT'] ?? 0?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
            <table>
                <tr>
                    <td>Number of professionals that continued payment after the 1st payment plan expiration: </td>
                    <?php  $sql = oci_parse($db, "SELECT COUNT (A.AID) AS CONT, COUNT(F.FID) AS PAYMENT
                                                          FROM FEE_PAYMENTS F, ACCOUNTS A
                                                          WHERE F.PROFESSIONAL = A.AID
                                                          HAVING COUNT (F.FID) > 1");
                    oci_execute($sql);
                    $num_of_pros = oci_fetch_assoc($sql);
                    ?>
                    <td><?php echo $num_of_pros['CONT']; ?></td>
                </tr>
            </table>
            <h3>Amount of money users paid in a given period</h3>
            <table>
                <tr>
                    <th>AID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Amount</th>
                </tr>
                <?php while($row = oci_fetch_assoc($query4)): ?>
                    <tr>
                        <td><?= $row['AID'] ?></td>
                        <td><?= $row['FNAME'] ?></td>
                        <td><?= $row['LNAME'] ?></td>
                        <td><?= $row['TOTAL_PAID'] ?? 0?></td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <?php else: ?>
            <h3>No query submitted</h3>
            <?php endif; ?>

        </div>
    </div>
</main>

</body>
</html>