<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once("includes/db.php");

function checkRequiredField($value)
{
    return isset($value) && !empty($value);
}

$aid = -1;

if (isset($_GET['id'])) {
    $aid = $_GET['id'];
} else {
    $aid = $_SESSION['user_id'];
}

$account = oci_parse($db, "SELECT * FROM accounts WHERE aid={$aid}");
oci_execute($account);
$row = oci_fetch_assoc($account);

if ($_POST && isset($_GET['id'])) {

    if (checkRequiredField($_POST['fname']) && checkRequiredField($_POST['lname']) && checkRequiredField($_POST['city'])
        && checkRequiredField($_POST['service']) && checkRequiredField($_POST['phone']) && checkRequiredField($_POST['problem-description'])
        && checkRequiredField($_POST['num_of_hrs'])) {

        // Fetching the WID from table WORK_OFFERS
        $query = oci_parse($db, "SELECT WID, CHARGE_PER_HOUR FROM WORK_OFFERS
                                            WHERE SERVICE={$_POST['service']} AND CITY={$_POST['city']} AND PROFESSIONAL={$_GET['id']}");
        oci_execute($query);
        $wid = oci_fetch_assoc($query);

        // Inserting a row into REQUESTS
        $query_request = oci_parse($db, "INSERT INTO requests(user_id, work_offer, description, charge_per_hour, num_of_hrs)
              VALUES ({$_SESSION['user_id']}, {$wid['WID']}, '{$_POST['problem-description']}', {$wid['CHARGE_PER_HOUR']}, 
              {$_POST['num_of_hrs']}) returning rid INTO :id");

        oci_execute($query_request);
        oci_commit($db);

        oci_bind_by_name($query_request, ':id', $newId);
        oci_execute($query_request);
        oci_commit($db);

        $query_history = oci_parse($db, "INSERT INTO REQUESTS_HISTORY(datetime, status, request) VALUES (SYSDATE, 0, {$newId})");
        oci_execute($query_history);
        oci_commit($db);

        header('Location: requests.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link href="css/header.css" rel="stylesheet">
    <link href="css/profile.css" rel="stylesheet">
    <link rel="stylesheet" href="css/footer.css">

    <title>Profile</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>

        function reqListener () {
            document.getElementById("estimate_price").innerHTML = this.responseText + 'BAM';
        }

        function testJavascriptRequest() {
            var oReq = new XMLHttpRequest();
            oReq.addEventListener("load", reqListener);
            var city = $('#city').val();
            var service = $('#service').val();
            var numOfHrs = $('#num_of_hrs').val();
            oReq.open("GET", "service_price.php?p_id=<?= $_GET['id']?>&c_id=" + city + '&s_id=' + service + '&num_of_hrs=' + numOfHrs);
            oReq.send();
        }
    </script>

</head>
<body>

<div id="page-container">
    <?php include('includes/header.php'); ?>

    <main class="center">
        <div class="shadow">
            <div class="user flex-container">
                <img src="images/default-user.png" alt="default-user-image">
                <div>
                    <h3><?= $row['FNAME'] . ' ' . $row['LNAME'] ?></h3>
                    <p><?= $row['PRIMARY_CITY'] ?>, Bosnia and Herzegovina</p>
                </div>
            </div>
            <div class="about">
                <h4>About: </h4>
                <?php if($row['SHORT_BIOGRAPHY'] == null): ?>
                    <p><i>No profile description provided . . .</i></p>
                <?php else: ?>
                    <p><?= $row['SHORT_BIOGRAPHY'] ?></p>
                <?php endif; ?>

                <?php if ($row['ROLE'] == 1){ ?>
                    <h4>Areas Served: </h4>
                    <?php
                    $query = oci_parse($db, "SELECT DISTINCT CITY, CNAME FROM WORK_OFFERS
                                                            JOIN CITIES
                                                            ON CID = CITY
                                                            where professional = {$aid}
                                                            ORDER BY CITY");
                    oci_execute($query);

                    while ($row3 = oci_fetch_assoc($query)) :
                    ?>
                    <span><?= $row3['CNAME'] . ' ~ '?></span>
                    <?php endwhile; ?>

                    <br><br><h4>Categories </h4>
                    <?php
                    $query = oci_parse($db, "SELECT DISTINCT SERVICE, CATEGORY FROM WORK_OFFERS
                                                            JOIN SERVICES
                                                            ON SERVICE = SID
                                                            where professional = {$aid}
                                                            ORDER BY SERVICE");
                    oci_execute($query);

                    while ($row2 = oci_fetch_assoc($query)) :
                        ?>
                        <span><?= $row2['CATEGORY'] . ' ~ ' ?></span>
                    <?php endwhile; ?>
                <?php } ?>
                <br><br><h4>Contact:</h4>
                <p><?= '+' . $row['AREA_CODE'] . ' ' . $row['PHONE_NUMBER'] ?></p>
            </div>
        </div>

        <?php if(isset($_GET['id'])): ?>
            <div class="request-box shadow">
                <p>Request Service Form</p>
                <form class="flex-container" method="post">
                    <div class="request-details flex-container">
                        <label>Full name <span>*</span></label>
                        <div class="flex-container">
                            <input type="text" id="fname" name="fname" placeholder="First name" required>
                            <input type="text" id="lname" name="lname" placeholder="Last name" required>
                        </div>

                        <label>City and Service <span>*</span></label>
                        <div class="flex-container">
                            <?php
                            $query = oci_parse($db, "SELECT DISTINCT CITY, CNAME FROM WORK_OFFERS
                                                            JOIN CITIES
                                                            ON CID = CITY
                                                            where professional = {$aid}
                                                            ORDER BY CITY");
                            oci_execute($query); ?>

                            <select name="city" id="city" required>
                                <option disabled selected value>City</option>
                                <?php while ($row = oci_fetch_assoc($query)): ?>
                                    <option value="<?= $row['CITY'] ?>"><?= $row['CNAME'] ?></option>
                                <?php endwhile; ?>
                            </select>

                            <?php
                            $query = oci_parse($db, "SELECT DISTINCT SERVICE, CATEGORY FROM WORK_OFFERS
                                                            JOIN SERVICES
                                                            ON SERVICE = SID
                                                            where professional = {$aid}
                                                            ORDER BY SERVICE");
                            oci_execute($query); ?>
                            <select name="service" id="service" required>
                                <option disabled selected value>Service</option>
                                <?php while ($row = oci_fetch_assoc($query)): ?>
                                    <option value="<?= $row['SERVICE'] ?>"><?= $row['CATEGORY'] ?></option>
                                <?php endwhile; ?>
                            </select>

                        </div>

                        <label for="phone">Phone number <span>*</span></label>
                        <input type="tel" name="phone" id="phone" placeholder="Phone number" required>

                    </div>
                    <div class="description flex-container">
                        <label for="problem-description">Description of the problem:</label>
                        <textarea name="problem-description" id="problem-description"
                                  placeholder="Please describe your problem here"></textarea>
                        <label for="num_of_hrs">Number of hours <span>*</span></label>
                        <input type="number" name="num_of_hrs" id="num_of_hrs" placeholder="Number of hours" required>
                    </div>

                    <div class="flex-container" id="submit-box">
                        <a id="get-price" type="submit" onclick="getPrice()">Get Estimate Price</a>
                        <script>
                            function getPrice() {
                                document.getElementById('price').style.display = 'block';
                                document.getElementById('get-price').style.display = 'none';
                                testJavascriptRequest();
                            }
                        </script>
                    </div>

                    <div id="price">
                        <p id="estimate_price"></p>
                        <button type="submit">SEND SERVICE REQUEST</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>

    </main>

    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>