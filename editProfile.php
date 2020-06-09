<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("includes/functions.php");
check_if_logged_in();
include_once("includes/db.php");

$months = [1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
$years = range(2020, date('Y') + 5);
$incorrect_password = false;
$incorrect_new_password = false;
$success = false;
if (isset($_SESSION['user_id'])) {
    $aid = $_SESSION['user_id'];

    $query = oci_parse($db, "select * from accounts where aid = '{$aid}'");
    oci_execute($query);
    $row = oci_fetch_assoc($query);

    if (isset($_POST['fname'])) {
        if (checkRequiredField($_POST['fname']) && checkRequiredField($_POST['lname']) && checkRequiredField($_POST['email']) && checkRequiredField($_POST['phone_number']) && checkRequiredField($_POST['primary_city'])) {
            $result = oci_parse($db, "update accounts set fname = '{$_POST['fname']}', lname = '{$_POST['lname']}', email = '{$_POST['email']}', phone_number = '{$_POST['phone_number']}', primary_city = '{$_POST['primary_city']}' where aid = '{$aid}'");
            oci_execute($result);
            oci_commit($db);
        }
        echo '<script> location.replace("editProfile.php"); </script>';
    }
    else if(isset($_POST['about'])) {
        if (checkRequiredField($_POST['about'])) {
            $query2 = oci_parse($db, "update accounts set short_biography = '{$_POST['about']}' where aid = $aid");
            oci_execute($query2);
            oci_commit($db);
        }
        echo '<script> location.replace("editProfile.php"); </script>';
    }
    else if(isset($_POST['current_password'])) {
        if(checkRequiredField($_POST['current_password']) && checkRequiredField($_POST['new_password']) && checkRequiredField($_POST['new_password_repeat'])) {
            $query2 = oci_parse($db, "select * from accounts where aid={$_SESSION['user_id']}"); //where password = {$_POST['current_password']} AND
            oci_execute($query2);

            $curr = oci_fetch_assoc($query2);

            if(password_verify($_POST['current_password'], $curr['PASSWORD'])) {
                if($_POST['new_password'] == $_POST['new_password_repeat']) {
                    $password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                    $query2 = oci_parse($db, "update accounts set password = '$password' where aid = {$_SESSION['user_id']}");
                    oci_execute($query2);
                    oci_commit($db);
                    $success = true;
                }
                else $incorrect_new_password = true;
            }
            else $incorrect_password = true;
        }
    }
    else if (isset($_POST['new_city'])) {
        $check_deleted = oci_parse($db, "SELECT W.*
                                                    FROM WORK_OFFERS W
                                                    WHERE W.PROFESSIONAL = {$aid}
                                                    AND W.CITY = {$_POST['new_city']} 
                                                    AND W.DATE_DELETED IS NOT NULL");
        oci_execute($check_deleted);
        $check = oci_fetch_assoc($check_deleted);
        if (!$check) {
            $query2 = oci_parse($db, "SELECT DISTINCT W.SERVICE, S.CATEGORY
                                                 FROM WORK_OFFERS W, SERVICES S, CITIES C
                                                 WHERE W.SERVICE = S.SID
                                                 AND W.CITY = C.CID
                                                 AND W.DATE_DELETED IS NULL
                                                 AND W.PROFESSIONAL = {$aid}
                                                 ORDER BY S.CATEGORY");
            oci_execute($query2);
            while ($services = oci_fetch_assoc($query2)){
                $service = $services['SERVICE'];

                $sql1 = oci_parse($db, "INSERT INTO WORK_OFFERS(SERVICE, CITY, CHARGE_PER_HOUR, PROFESSIONAL)
                                          VALUES ({$service}, {$_POST['new_city']}, 4, {$aid})");
                oci_execute($sql1);
                oci_commit($db);



            }  }else {
            $query2 = oci_parse($db, "SELECT DISTINCT W.SERVICE, S.CATEGORY, S.SID
                                     FROM WORK_OFFERS W, SERVICES S
                                     WHERE W.SERVICE = S.SID
                                     AND W.PROFESSIONAL = {$aid}
                                     ORDER BY S.CATEGORY");
            oci_execute($query2);
            while ($row2 = oci_fetch_assoc($query2)) {
                $service = $row2['SERVICE'];
                $sql2 = oci_parse($db, "UPDATE WORK_OFFERS SET DATE_DELETED = NULL
                                          WHERE CITY = {$_POST['new_city']}
                                          AND SERVICE = {$service}
                                          AND PROFESSIONAL = {$aid}");
                oci_execute($sql2);
                oci_commit($db);
            }
        }

    }
    else if (isset($_POST['deleted_city'])) {
        $query2 = oci_parse($db, "SELECT DISTINCT W.SERVICE, S.CATEGORY, S.SID
                                     FROM WORK_OFFERS W, SERVICES S
                                     WHERE W.SERVICE = S.SID
                                     AND W.PROFESSIONAL = {$aid}
                                     ORDER BY S.CATEGORY");
        oci_execute($query2);
        while ($row = oci_fetch_assoc($query2)) {
            $service = $row['SID'];
            $sql = oci_parse($db, "UPDATE WORK_OFFERS SET DATE_DELETED = SYSDATE 
                                          WHERE CITY = {$_POST['deleted_city']}
                                          AND SERVICE = {$service}
                                          AND PROFESSIONAL = {$aid}");
            oci_execute($sql);
            oci_commit($db);
        }
        echo '<script> location.replace("editProfile.php"); </script>';
    }
    else if (isset($_POST['card_num'])) {
        if (checkRequiredField($_POST['card_num']) && isset($_POST['month']) && isset($_POST['year']) && checkRequiredField($_POST['cvv'])){
            $sql = oci_parse($db, "UPDATE FEE_PAYMENTS
                                          SET CARD_NUMBER = {$_POST['card_num']},
                                          EXP_MONTH = {$_POST['month']},
                                          EXP_YEAR = {$_POST['year']},
                                          CVV = {$_POST['cvv']}
                                          WHERE PROFESSIONAL = {$aid}");
            oci_execute($sql);
            oci_commit($db);
        }
        echo '<script> location.replace("editProfile.php"); </script>';
    }
    else if(isset($_POST['submit'])) {
        if (isset($_FILES)) {
            $imageSrc = $_FILES['image']['tmp_name'];
            $imageType = $_FILES['image']['type'];
            $ext = substr($imageType, 6);

            $path = "images/profiles/" . $aid . "." . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $path);

            $r = oci_parse($db, "UPDATE accounts SET img_type = '{$ext}' WHERE aid = {$aid}");
            oci_execute($r);
            oci_commit($db);
            echo '<script> location.replace("editProfile.php"); </script>';
        }
    }

    $query4 = oci_parse($db, "SELECT C.CID, C.CNAME
                                     FROM CITIES C
                                     WHERE C.DATE_DELETED IS NULL AND C.CID NOT IN
                                     (SELECT W.CITY FROM WORK_OFFERS W, CITIES C WHERE W.CITY = C.CID AND W.PROFESSIONAL = {$aid} AND W.DATE_DELETED IS NULL)
                                     ORDER BY C.CNAME");
    oci_execute($query4);
    $query5 = oci_parse($db, "SELECT DISTINCT W.CITY, C.CNAME, C.CID
                                     FROM WORK_OFFERS W, CITIES C
                                     WHERE W.CITY = C.CID
                                     AND W.PROFESSIONAL = {$aid}
                                     AND W.DATE_DELETED IS NULL
                                     ORDER BY C.CNAME");
    oci_execute($query5);

}

?>

<!doctype html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link rel="stylesheet" href="css/editProfile.css">
    <link href="css/header.css" rel="stylesheet">
    <link rel="stylesheet" href="css/footer.css">
    <title>Edit Profile</title>
</head>
<body>

<div id="page-container">
    <?php include('includes/header.php'); ?>

    <main>
        <div class="main center">
            <?php if($incorrect_password): ?>
                <div class="flex-container center">
                    <p class="center">Incorrect password!</p>
                </div>
            <?php endif; ?>
            <?php if($incorrect_new_password): ?>
                <div class="flex-container center">
                    <p class="center">Repeated password is incorrect!</p>
                </div>
            <?php endif; ?>
            <?php if($success): ?>
                <div class="flex-container center">
                    <p class="center">Success!</p>
                </div>
            <?php endif; ?>
            <div>
                <?php
                $sql = oci_parse($db, "select MAX(F.PAYMENT_EXPIRATION)
                                          from ACCOUNTS A, FEE_PAYMENTS F
                                          where F.PROFESSIONAL = A.AID
                                          and A.AID = {$aid}");
                oci_execute($sql);

                $exp_row = oci_fetch_assoc($sql);

                ?>
                <h4 style="color: #d42626; font-weight: unset; font-style: italic; ">Payment expires on: <?php echo $exp_row['MAX(F.PAYMENT_EXPIRATION)']; ?></h4>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div id="profilePhoto">
                    <img src="<?= fetch_profile_image($row['AID'], $row['IMG_TYPE']); ?>" alt="default-user-image">
                    <input type="file" name="image" style="margin-left: 5rem;">
                    <button type="submit" name="submit" class="buttonStyle">Change Profile Photo</button>
                </div>
            </form>

            <form method="post">
                <fieldset class="general">
                    <legend>General</legend>
                    <div>
                        <label for="firstName">First name</label>
                        <input id="firstName" name="fname" type="text" value="<?php
                        if (isset($row['FNAME'])){
                            echo $row['FNAME'];
                        }
                        ?>">
                    </div>
                    <div>
                        <label for="lastName">Last name</label>
                        <input id="lastName" name="lname" type="text" value="<?php
                        if (isset($row['LNAME'])){
                            echo $row['LNAME'];
                        }
                        ?>">
                    </div>
                    <div>
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="<?php
                        if (isset($row['EMAIL'])){
                            echo $row['EMAIL'];
                        }
                        ?>">
                    </div>
                    <div>
                        <label for="phoneNum">Phone number</label>
                        <input id="phoneNum" name="phone_number" type="number" value="<?php
                        if (isset($row['PHONE_NUMBER'])){
                            echo $row['PHONE_NUMBER'];
                        }
                        ?>">
                    </div>
                    <div>
                        <label for="primary_city">City of residence</label>
                        <input id="primary_city" name="primary_city" type="text" value="<?php
                        if (isset($row['PRIMARY_CITY'])){
                            echo $row['PRIMARY_CITY'];
                        }
                        ?>">
                    </div>
                    <button type="submit" class="buttonStyle">SAVE</button>
                </fieldset>
            </form>

            <form method="post">
                <fieldset>
                    <legend>Info</legend>
                    <div class="about">
                        <label for="about">About</label>
                        <textarea name="about" id="about" placeholder="Please tell us a little about yourself"><?php
                            if(isset($row['SHORT_BIOGRAPHY'])) { echo "{$row['SHORT_BIOGRAPHY']}"; }
                            ?></textarea>
                    </div>
                    <button type="submit" class="buttonStyle">SAVE</button>
                </fieldset>

            </form>

            <form method="post">
                <fieldset>
                    <legend>Password</legend>
                    <div>
                        <label for="currentPass">Current Password</label>
                        <input name="current_password" id="currentPass" placeholder="Enter your current password" type="password">
                    </div>
                    <div>
                        <label for="newPass">New password</label>
                        <input name="new_password" id="newPass" placeholder="Enter your new password" type="password">
                    </div>
                    <div>
                        <label for="repeatPass">Repeat New Password</label>
                        <input name="new_password_repeat" id="repeatPass" placeholder="Repeat your new password" type="password">
                    </div>
                    <button type="submit" class="buttonStyle">SAVE</button>
                </fieldset>
            </form>

            <?php if($row['ROLE'] == 1): ?>
                <fieldset>
                    <legend>Credit card</legend>
                    <div class="credit-card_fieldset" id="get_credit_info">
                        <form method="post">
                            <div class="acceptedCards">
                                <label>Accepted Cards</label>
                                <div class="icon-container">
                                    <i class="fa fa-cc-visa" style="color:navy;"></i>
                                    <i class="fa fa-cc-amex" style="color:blue;"></i>
                                    <i class="fa fa-cc-mastercard" style="color:red;"></i>
                                    <i class="fa fa-cc-discover" style="color:orange;"></i>
                                </div>
                            </div>
                            <div class="card_num">
                                <label for="card_num">Card number</label>
                                <input type="text" name="card_num" class="card-number" id="card_num" placeholder="Card Number">
                            </div>
                            <div class="dateAndCvv">
                                <div class="month">
                                    <label for="month">Expiration month</label>
                                    <select name="month" id="month">
                                        <?php foreach ($months as $key => $month) { ?>
                                            <option value="<?= $key ?>"><?= $month ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="year">
                                    <label for="year">Expiration year</label>
                                    <select name="year" id="year">
                                        <?php foreach ($years as $year) { ?>
                                            <option value="<?= $year ?>"><?= $year ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="cvv-input">
                                    <label for="cvv">CVV</label>
                                    <?php create_input("number", "cvv", "CVV",true); ?>
                                </div>
                            </div>
                            <button class="buttonStyle" type="submit">Save</button>
                        </form>
                    </div>
                    <button class="buttonStyle" onclick="get_credit()" id="remove_button">Update Credit Card Information</button>
                    <script>
                        function get_credit() {
                            document.getElementById('get_credit_info').style.display = 'block';
                            document.getElementById('remove_button').style.display = 'none';
                        }
                    </script>

                </fieldset>

                <fieldset>
                    <legend>Service Categories</legend>
                    <a href="editServices.php" class="buttonStyle" id="services_link">Update Service Categories</a>
                </fieldset>

                <fieldset id="cities_fieldset">
                    <legend>Service Cities</legend>
                    <form method="post">
                        <label for="new_city">Add a new city</label>
                        <select name="new_city">
                            <option disabled selected value>Choose a city</option>
                            <?php while ($get_cities = oci_fetch_assoc($query4)) : ?>
                                <option value="<?= $get_cities['CID'] ?>"><?= $get_cities['CNAME'] ?></option>
                            <?php endwhile; ?>
                        </select>
                        <button type="submit" class="buttonStyle">ADD</button>
                    </form>
                    <form method="post">
                        <label for="deleted_city">Delete a city</label>
                        <select name="deleted_city">
                            <option disabled selected value>Choose a city</option>
                            <?php while ($get_cities = oci_fetch_assoc($query5)) : ?>
                                <option value="<?= $get_cities['CID'] ?>"><?= $get_cities['CNAME'] ?></option>
                            <?php endwhile; ?>
                        </select>
                        <button type="submit" class="buttonStyle">DELETE</button>
                    </form>
                </fieldset>
            <?php endif; ?>
        </div>
    </main>
    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>