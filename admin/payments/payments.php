<?php

include_once('../../includes/db.php');

$months = [1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
$years = range(2019, 2022);

$flag = 0;

if($_POST) {
    $from_date = "{$_POST['from_day']}-{$_POST['from_month']}-{$_POST['from_year']}";
    $to_date = "{$_POST['to_day']}-{$_POST['to_month']}-{$_POST['to_year']}";


    $query = oci_parse($db, "SELECT aid, fname, lname, money_earned FROM accounts
                                     FULL JOIN (
                                        SELECT professional, sum(num_of_hrs*REQUESTS.charge_per_hour) AS money_earned FROM requests_history
                                        JOIN REQUESTS ON request = rid
                                        JOIN WORK_OFFERS ON work_offer = wid
                                        WHERE status = 1 AND datetime >= to_date('{$from_date}','DD-MM-YYYY') AND datetime <= to_date('{$to_date}','DD-MM-YYYY')
                                        GROUP BY professional
                                        )
                                     ON aid = professional
                                     WHERE role = 1
                                     ORDER BY aid");
    oci_execute($query);

    $query2 = oci_parse($db, "SELECT sid, category, min_billed, max_billed, avg_billed FROM services
                                      FULL JOIN (
                                        SELECT service, MIN(num_of_hrs*REQUESTS.charge_per_hour) AS min_billed, MAX(num_of_hrs*REQUESTS.charge_per_hour) AS max_billed, ROUND(AVG(num_of_hrs*REQUESTS.charge_per_hour), 3) AS avg_billed, status 
                                        FROM REQUESTS_HISTORY
                                        JOIN REQUESTS ON request = rid
                                        JOIN WORK_OFFERS ON work_offer = wid
                                        WHERE status = 1 AND datetime >= to_date('{$from_date}','DD-MM-YYYY') AND datetime <= to_date('{$to_date}','DD-MM-YYYY')
                                        GROUP BY service, status
                                        )
                                      ON sid = service
                                      ORDER BY sid");
    oci_execute($query2);

    $query3 = oci_parse($db, "SELECT aid, fname, lname, total_money_spent FROM accounts
                                      FULL JOIN (
                                        SELECT professional, sum(amount) total_money_spent FROM fee_payments
                                        WHERE date_paid >= to_date('{$from_date}','DD-MM-YYYY') AND date_paid <= to_date('{$to_date}','DD-MM-YYYY')
                                        GROUP BY professional
                                        )
                                      ON aid = professional
                                      ORDER BY aid");
    oci_execute($query3);

    $query4 = oci_parse($db, "SELECT aid, fname, lname, total_paid FROM accounts
                                      FULL JOIN (
                                        SELECT user_id, sum(num_of_hrs*charge_per_hour) AS total_paid FROM requests
                                        JOIN requests_history ON rid = request
                                        WHERE status = 1 AND datetime >= to_date('{$from_date}','DD-MM-YYYY') AND datetime <= to_date('{$to_date}','DD-MM-YYYY')
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
    <title>Admin | Payments</title>
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
                <select name="from_day" id="from">
                    <?php for($i = 1; $i < 31; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
                <select name="from_month" id="from">
                    <?php for($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i ?>"><?= $months["$i"]; ?></option>
                    <?php endfor; ?>
                </select>
                <select name="from_year" id="from">
                    <?php for($i = 2019; $i <= 2022; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>

                <label for="to">To</label>
                <select name="to_day" id="to">
                    <?php for($i = 1; $i < 31; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
                <select name="to_month" id="to">
                    <?php for($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i ?>"><?= $months["$i"]; ?></option>
                    <?php endfor; ?>
                </select>
                <select name="to_year" id="to">
                    <?php for($i = 2019; $i <= 2022; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
                <button type="submit" style="padding: 0 2rem">Go</button>
            </form>
            <?php if(!empty($_POST)): ?>
            <table>
                <tr>
                    <th>aid</th>
                    <th>fname</th>
                    <th>lname</th>
                    <th>amount earned</th>
                </tr>
                <?php while($row = oci_fetch_assoc($query)): ?>
                <tr>
                    <td><?= $row['AID'] ?></td>
                    <td><?= $row['FNAME'] ?></td>
                    <td><?= $row['LNAME'] ?></td>
                    <td><?= $row['MONEY_EARNED'] ?? 0 ?></td>
                </tr>
                <?php endwhile; ?>
            </table>

            <table>
                <tr>
                    <th>sid</th>
                    <th>category</th>
                    <th>min billed</th>
                    <th>max billed</th>
                    <th>avg billed</th>
                </tr>
                <?php while($row = oci_fetch_assoc($query2)): ?>
                    <tr>
                        <td><?= $row['SID'] ?></td>
                        <td><?= $row['CATEGORY'] ?></td>
                        <td><?= $row['MIN_BILLED'] ?? 0?></td>
                        <td><?= $row['MAX_BILLED'] ?? 0?></td>
                        <td><?= $row['AVG_BILLED'] ?? 0?></td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <table>
                <tr>
                    <th>aid</th>
                    <th>fname</th>
                    <th>lname</th>
                    <th>total money spent on Pick-Fix</th>
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
                    <th>aid</th>
                    <th>fname</th>
                    <th>lname</th>
                    <th>total money spent on services by users</th>
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
            <h2>No query submitted</h2>
            <?php endif; ?>

        </div>
    </div>
</main>

</body>
</html>