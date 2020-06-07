<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("includes/form-functions.php");
include_once("includes/db.php");

function checkRequiredField($value)
{
    return isset($value) && !empty($value);
}

$query1 = oci_parse($db, 'SELECT * FROM cities WHERE date_deleted IS NULL ORDER BY cname');
oci_execute($query1);
$query2 = oci_parse($db, 'SELECT * FROM services WHERE date_deleted IS NULL ORDER BY category');
oci_execute($query2);
$list_cities = oci_parse($db, 'SELECT * FROM cities WHERE date_deleted IS NULL ORDER BY cname');
oci_execute($list_cities);
$months = [1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
$years = range(2020, date('Y') + 5);

if($_POST) {

    if (checkRequiredField($_POST['first_name']) && checkRequiredField($_POST['last_name']) && checkRequiredField($_POST['email'])
        && checkRequiredField($_POST['password']) && checkRequiredField($_POST['area_code']) && checkRequiredField($_POST['phone_number'])
        && checkRequiredField($_POST['card_num']) && checkRequiredField($_POST['month']) && checkRequiredField($_POST['year']) && checkRequiredField($_POST['cvv']) && isset($_POST['cities']) && isset($_POST['primary_city']) && isset($_POST['services'])) {

        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO accounts(fname, lname, email, password, area_code, phone_number, primary_city, role) VALUES('{$_POST['first_name']}', '{$_POST['last_name']}', '{$_POST['email']}', '$password', {$_POST['area_code']}, {$_POST['phone_number']}, '{$_POST['primary_city']}', 1)";
        $result = oci_parse($db, $sql);
        oci_execute($result);
        oci_commit($db);

        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = oci_parse($db, "select * from accounts where email = '{$email}'");
        oci_execute($query);
        $row = oci_fetch_assoc($query);

        $cities_array = $_POST['cities'];
        $services_array = $_POST['services'];
        for ($i = 0; $i < count($cities_array); $i++) {
            $city = $cities_array[$i];
            for ($j = 0; $j < count($services_array); $j++) {
                $service = $services_array[$j];
                $statement = oci_parse($db, "INSERT INTO work_offers(service, city, charge_per_hour, professional, service_level) VALUES ($service, $city, 4, {$row['AID']}, 'Beginner')");
                oci_execute($statement);
                oci_commit($db);
            }
        }

        if ($row) {
            $query3 = "INSERT INTO fee_payments (card_number, exp_month, exp_year, cvv, professional)
                VALUES ({$_POST['card_num']}, {$_POST['month']}, {$_POST['year']}, {$_POST['cvv']}, {$row['AID']})";
            $result = oci_parse($db, $query3);
            oci_execute($result);
            oci_commit($db);
        }
        $_SESSION['user_id'] = $row['AID'];
        $_SESSION['fname'] = $row['FNAME'];
        $_SESSION['lname'] = $row['LNAME'];
        $_SESSION['role'] = $row['ROLE'];

        header('Location: findProfessionals.php');
        exit();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link rel="stylesheet" href="css/professionalsRegistration.css">
    <link rel="stylesheet" href="css/footer.css">
    <title>Join as a Pro</title>
</head>
<body>
<div id="page-container">
    <div id="header">
        <header>
            <h1><a href="index.php">Pick & Fix</a></h1>
            <nav>
                <a href="index.php">Home</a>
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
                <p>Fill out the registration form below, and with a monthly fee of only 3,99BAM
                    enjoy all the benefits of our platform. Sign up for your 14-day FREE trial today!</p>
                <div class="arrow_box"></div>
            </div>
        </div>

        <div class="registrationWrapper">
            <div class="registrationBlockLeft">

                <h1 id="signUp">Register as a professional</h1>
                <form method="post">
                    <div>
                        <label>Full Name<br>
                            <?php create_input("text", "first_name", "First name", true); ?>
                            <?php create_input("text", "last_name", "Last name", true); ?>
                        </label>
                    </div>
                    <div class="loginFields">
                        <div>
                            <label for="email">Email</label><br>
                            <?php create_input("text", "email", "Email",true); ?>
                        </div>
                        <div>
                            <label for="password">Password</label><br>
                            <?php create_input("password", "password", "Password",true); ?>
                        </div>
                    </div>
                    <div class="checkboxWrapper">
                        <div>
                            <label>Available for work in:</label>
                            <div class="checkbox" id="city-checkbox">
                                <?php while($row = oci_fetch_assoc($query1)): ?>
                                    <input name="cities[]" type="checkbox" value="<?= $row['CID']; ?>">
                                    <label><?= $row['CNAME']; ?></label><br>
                                <?php endwhile; ?>
                            </div>
                            <select name="primary_city" id="city">
                                <option disabled selected value>City of residence</option>
                                <?php while($fetch_cities = oci_fetch_assoc($list_cities)): ?>
                                    <option value="<?= $fetch_cities['CNAME']; ?>"><?= $fetch_cities['CNAME']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label>Categories of work</label>
                            <div class="checkbox">
                                <?php while($row = oci_fetch_assoc($query2)): ?>
                                    <input name="services[]" type="checkbox" value="<?= $row['SID']; ?>">
                                    <label><?= $row['CATEGORY']; ?></label><br>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label>Phone Number<br>
                            <?php create_input("number", "area_code", "Area code",true); ?>
                            <?php create_input("number", "phone_number", "Phone number",true); ?>
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
                        <?php create_input("number", "cvv", "CVV",true); ?>
                    </div>
                </div>
                <div class="submission">
                    <p>By creating an account you agree to our <a href="#">Terms & Privacy</a></p>
                    <button type="submit" class="buttonStyle">START WORKING</button>
                </div>
                </form>
            </div>
        </div>

        <div id="signInBlock">
            <h4>Already have an account?</h4>
            <a class="buttonStyle" href="login.php">SIGN IN</a>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>