<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once('includes/db.php');

if (isset($_POST['plan'])) {

    if (isset($_SESSION['user_id']) && $_SESSION['role'] == 1) {
        $amount = 11.95;
        $num_of_months = 1;
        if ($_POST['plan'] == 1) {
            $amount = 83.88;
            $num_of_months = 12;
        } else if ($_POST['plan'] == 2) {
            $amount = 119.76;
            $num_of_months = 24;
        } else if ($_POST['plan'] == 3) {
            $amount = 125.64;
            $num_of_months = 36;
        }

        $sql = "INSERT INTO fee_payments (payment_plan, professional, date_paid, amount, payment_expiration)
                VALUES ({$_POST['plan']}, {$_SESSION['user_id']}, SYSDATE, {$amount}, ADD_MONTHS(SYSDATE, {$num_of_months}))";
        $result = oci_parse($db, $sql);
        oci_execute($result);
        oci_commit($db);

        header("Location: findProfessionals.php");
    } else if ($_SESSION['role'] == 2) {
        header("Location: become-pro.php?plan={$_POST['plan']}");
    } else {
        header("Location: professionalsRegistration.php?plan={$_POST['plan']}");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link href="css/header.css" rel="stylesheet">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/pricing.css">
</head>
<body>

<div id="page-container">
    <main class="center">
        <div class="title flex-container">
            <h1>Pick & Fix takes your career to the next level</h1>
            <div class="money-back flex-container"><img src="images/money-back.svg" alt="Money Back Guarantee">Money-back
                <br>100% Guarantee
            </div>
        </div>
        <div>
            <p class="text"><i class="fa fa-check" aria-hidden="true"></i> Quick jobs</p>
            <p class="text"><i class="fa fa-check" aria-hidden="true"></i> Receive and send job requests</p>
            <p class="text"><i class="fa fa-check" aria-hidden="true"></i> 30-day money-back guarantee</p>
        </div>
        <h2>Choose a plan</h2>
        <form method="post">
            <div class="box flex-container">
                <label class="plan flex-container" for="best_value">
                    <input type="radio" name="plan" value="3" id="best_value">
                    <p class="type">Best Value Plan</p>
                    <p class="duration">3 years</p>
                    <h3 class="price"><sup class="currency">BAM</sup>3.49<span style="font-size: 14px;font-weight: 500">/mo</span>
                    </h3>
                    <p class="saving">Save 70%</p>
                    <p class="value">Get the best value — save <br>70%</p>
                    <p class="billing"><strike style="color: #f64f64">BAM 430.20</strike> KM 125.64 billed every 3<br>years
                    </p>
                </label>
                <label class="plan flex-container" for="loyal_plan">
                    <input type="radio" name="plan" value="2" id="loyal_plan">
                    <p class="type">Loyal Plan</p>
                    <p class="duration">2 years</p>
                    <h3 class="price"><sup class="currency">BAM</sup>4.99<span style="font-size: 14px;font-weight: 500">/mo</span>
                    </h3>
                    <p class="saving">Save 58%</p>
                    <p class="value">Our 2nd best offer. <br>730 days of Pick & Fix jobs.</p>
                    <p class="billing"><strike style="color: #f64f64">BAM 286.80</strike> KM 119.76 billed every 2<br>years
                    </p>
                </label>
                <label class="plan flex-container" for="basic_plan">
                    <input type="radio" name="plan" value="1" id="basic_plan">
                    <p class="type">Basic Plan</p>
                    <p class="duration">1 year</p>
                    <h3 class="price"><sup class="currency">BAM</sup>6.99<span style="font-size: 14px;font-weight: 500">/mo</span>
                    </h3>
                    <p class="saving">Save 41%</p>
                    <p class="value">Enjoy the basic plan.<br>Renew annually.</p>
                    <p class="billing"><strike style="color: #f64f64">BAM 143.40</strike> KM 83.88 billed every year</p>
                </label>
                <label class="plan flex-container" for="trial_plan">
                    <input type="radio" name="plan" value="0" id="trial_plan">
                    <p class="type">Trial Plan</p>
                    <p class="duration">30 days</p>
                    <h3 class="price"><sup class="currency">BAM</sup>11.95<span
                                style="font-size: 14px;font-weight: 500">/mo</span>
                    </h3>
                    <p class="saving">Save 0%</p>
                    <p class="value">Try out all Pick & Fix <br>features for a month.</p>
                    <p class="billing">BAM 11.95 billed every month</p>
                </label>
            </div>
            <div class="submit flex-container">
                <button type="submit">Continue</button>
            </div>
        </form>
    </main>
    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>