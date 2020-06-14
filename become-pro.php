<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("includes/functions.php");
check_if_logged_in();
include_once("includes/db.php");

$query1 = oci_parse($db, 'SELECT * FROM cities WHERE date_deleted IS NULL ORDER BY cname');
oci_execute($query1);
$query2 = oci_parse($db, 'SELECT * FROM services WHERE date_deleted IS NULL ORDER BY category');
oci_execute($query2);
$list_cities = oci_parse($db, 'SELECT * FROM cities WHERE date_deleted IS NULL ORDER BY cname');
oci_execute($list_cities);
$months = [1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
$years = range(2020, date('Y') + 5);

if ($_POST && $_GET['plan']) {

    if (checkRequiredField($_POST['area_code']) && checkRequiredField($_POST['phone_number']) && checkRequiredField($_POST['card_num']) && checkRequiredField($_POST['month']) && checkRequiredField($_POST['year']) && checkRequiredField($_POST['cvv']) && isset($_POST['cities']) && isset($_POST['services'])) {

        $sql = "UPDATE accounts SET area_code = {$_POST['area_code']}, phone_number = {$_POST['phone_number']}, role = 1 WHERE aid = {$_SESSION['user_id']}";
        $result = oci_parse($db, $sql);
        oci_execute($result);
        oci_commit($db);

        $cities_array = $_POST['cities'];
        $services_array = $_POST['services'];
        for ($i = 0; $i < count($cities_array); $i++) {
            $city = $cities_array[$i];
            for ($j = 0; $j < count($services_array); $j++) {
                $service = $services_array[$j];
                $statement = oci_parse($db, "INSERT INTO work_offers(service, city, charge_per_hour, professional) VALUES ($service, $city, 4, {$_SESSION['user_id']})");
                oci_execute($statement);

                oci_commit($db);
            }
        }

        $amount = 11.95;
        $num_of_months = 1;
        if ($_GET['plan'] == 1) {
            $amount = 83.88;
            $num_of_months = 12;
        } else if ($_GET['plan'] == 2) {
            $amount = 119.76;
            $num_of_months = 24;
        } else if ($_GET['plan'] == 3) {
            $amount = 125.64;
            $num_of_months = 36;
        }

        $findProfessional = oci_parse($db, "SELECT * FROM accounts WHERE aid = {$_SESSION['user_id']}");
        oci_execute($findProfessional);
        $professional = oci_fetch_assoc($findProfessional);

        $query3 = "INSERT INTO fee_payments (card_number, cvv, amount, professional, date_paid, exp_year, exp_month, payment_plan, payment_expiration)
                VALUES ({$_POST['card_num']}, {$_POST['cvv']}, {$amount}, {$professional['AID']}, SYSDATE, {$_POST['year']}, {$_POST['month']}, {$_GET['plan']}, ADD_MONTHS(SYSDATE, {$num_of_months}))";
        $result1 = oci_parse($db, $query3);
        oci_execute($result1);

        oci_commit($db);

        if ($professional) {
            $_SESSION['role'] = $professional['ROLE'];


            header('Location: findProfessionals.php');
            exit();
        }
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link href="css/professionalsRegistration.css" rel="stylesheet">
    <link href="css/footer.css" rel="stylesheet">

    <title>Become a Pro</title>
</head>
<body id="become-pro">
<div id="page-container">
    <div id="header">
        <header>
            <h1><a href="index.php">pick&fix</a></h1>
            <nav>
                <a href="profile.php">Back to Profile</a>
            </nav>
        </header>
        <hr>
    </div>

    <main>

        <div class="backShape">
            <div class="textBlockForProfessionals">
                <h3>WANT TO JOIN US?</h3>
                <h4>WHY</h4>
                <p>We offer You a chance to join our community of professionals who provide their services and
                    broad set of skills on our platform.
                    <br><strong>Start receiving job requests in one place - easy and fast!</strong></p>
                <h4>HOW</h4>
                <p>Fill out the registration form below, and with a chosen payment plan
                    enjoy all the benefits of our platform. Sign up for your 30-day FREE trial today!</p>
                <div class="arrow_box"></div>
            </div>
        </div>

        <div class="registrationWrapper">
            <div class="registrationBlockLeft">

                <h1 id="signUp">Register as a professional</h1>
                <form method="post">


                    <div class="checkboxWrapper">
                        <div>
                            <label>Available for work in:</label>
                            <div class="checkbox">
                                <?php while ($row = oci_fetch_assoc($query1)): ?>
                                    <input name="cities[]" type="checkbox" value="<?= $row['CID']; ?>">
                                    <label><?= $row['CNAME']; ?></label><br>
                                <?php endwhile; ?>
                            </div>
                        </div>

                        <div>
                            <label>Categories of work</label>
                            <div class="checkbox">
                                <?php while ($row = oci_fetch_assoc($query2)): ?>
                                    <input name="services[]" type="checkbox" value="<?= $row['SID']; ?>">
                                    <label><?= $row['CATEGORY']; ?></label><br>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label>Phone Number<br>
                            <input name="area_code" id="area_code" type="number" placeholder="ex. 387"
                                   value="<?= isset($_POST['area_code']) ?? isset($_GET['area_code']) ?>" required>
                            <input name="phone_number" id="phone_number" type="number" placeholder="ex. 60856365"
                                   value="<?= $_POST['phone_number'] ?? isset($_GET['phone_number']) ?>" required>
                        </label>
                    </div>

            </div>
            <div class="verticalLine"></div>

            <div class="registrationBlockRight">
                <h3 class="title">Credit card detail</h3><br>

                <div class="acceptedCards">
                    <label>Accepted Cards</label>
                    <div class="icon-container">
                        <i class="fa fa-cc-visa" style="color:navy;"></i>
                        <i class="fa fa-cc-amex" style="color:blue;"></i>
                        <i class="fa fa-cc-mastercard" style="color:red;"></i>
                        <i class="fa fa-cc-discover" style="color:orange;"></i>
                    </div>
                </div>
                <input type="text" name="card_num" class="card-number" placeholder="Card Number">
                <div class="dateAndCvv">
                    <div class="month">
                        <select name="month" id="month">
                            <?php foreach ($months as $key => $month) { ?>
                                <option value="<?= $key ?>"><?= $month ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="year">
                        <select name="year" id="year">
                            <?php foreach ($years as $year) { ?>
                                <option value="<?= $year ?>"><?= $year ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="cvv-input">
                        <?php create_input("number", "cvv", "CVV", true); ?>
                    </div>
                </div>
                <div class="submission">
                    <p>By creating an account you agree to our <a href="#">Terms & Privacy</a></p>
                    <button type="submit" class="buttonStyle">START WORKING</button>
                </div>
                </form>
            </div>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>