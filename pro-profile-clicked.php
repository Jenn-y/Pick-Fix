<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once("includes/db.php");

$aid = $_SESSION['user_id'];
$q = "SELECT * FROM accounts WHERE aid={$aid}";
$query = oci_parse($db, $q);
oci_execute($query);

$row = oci_fetch_assoc($query);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = oci_parse($db, "SELECT * FROM accounts WHERE aid = {$id}");
    oci_execute($result);
    $fetch_professional = oci_fetch_assoc($result);
}

function checkRequiredField($value)
{
    return isset($value) && !empty($value);
}

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
        $query_request = oci_parse($db, "INSERT INTO requests(user_id, work_offer, description, charge_per_hour, num_of_hours)
              VALUES ({$_SESSION['user_id']}, {$wid['WID']}, '{$_POST['problem-description']}', {$wid['CHARGE_PER_HOUR']}, 
              {$_POST['num_of_hrs']})");

        oci_execute($query_request);
        oci_commit($db);

              //returning rid INTO :id");

       // oci_bind_by_name($query_request, ':id', $newId);
        //var_dump($newId);

        $query = oci_parse($db, "SELECT * FROM REQUESTS
                                         ORDER BY RID DESC
                                         FETCH FIRST 1 ROWS ONLY");
        oci_execute($query);
        $rid = oci_fetch_assoc($query);


        $query_history = oci_parse($db, "INSERT INTO REQUESTS_HISTORY(datetime, status, request) VALUES (SYSDATE, 0, {$rid['RID']})");
        oci_execute($query_history);
        oci_commit($db);


        header('Location: pro-profile-requests.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link href="css/header.css" rel="stylesheet">
    <link href="css/pro-profile.css" rel="stylesheet">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/test.css">

    <title>Profile</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {

            $("#get-price").click(function() {

                //here the value is stored in variable.
                let x = $("#num_of_hrs").val();

                document.getElementById("estimate_price").innerHTML = x;
            });

        });
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
                    <h3><?= $fetch_professional['FNAME'] . ' ' . $fetch_professional['LNAME'] ?></h3>
                    <p><?= $fetch_professional['PRIMARY_CITY'] ?>, Bosnia and Herzegovina</p>
                </div>
            </div>
            <div class="about">
                <h4>About: </h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad aliquam aliquid aut consectetur
                    consequatur cum dolorum excepturi facere harum ipsam ipsum magni, minima mollitia numquam, porro
                    quia quod recusandae similique sit suscipit tenetur vitae voluptate voluptatum! Adipisci dolorem ea
                    earum eius, eligendi harum id obcaecati, omnis quibusdam quis tenetur, vero.</p>
                <h4>Areas Served: </h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi, est maiores modi officiis
                    perspiciatis recusandae totam vel veritatis voluptatem voluptatibus?</p>
                <h4>Categories </h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi, est maiores modi officiis
                    perspiciatis recusandae totam vel veritatis voluptatem voluptatibus?</p>
                <h4>Contact:</h4>
                <p><?= '+' . $fetch_professional['AREA_CODE'] . ' ' . $fetch_professional['PHONE_NUMBER'] ?></p>
            </div>
        </div>

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
                                                            where professional = {$_GET['id']}
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
                                                            where professional = {$_GET['id']}
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
                        }
                    </script>
                </div>

                <div id="price">
                    <p id="estimate_price"></p>
                    <button type="submit">SEND SERVICE REQUEST</button>
                </div>
            </form>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>